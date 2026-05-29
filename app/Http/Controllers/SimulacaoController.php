<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumo;
use App\Models\Receita;
use App\Models\Simulacao;
use App\Models\CmvCalculo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SimulacaoController extends Controller
{
    private function getDataUserId()
    {
        $user = Auth::user();
        if ($user->isVisualizador()) {
            $gestor = User::where('role', 'gestor')->first();
            return $gestor ? $gestor->id : $user->id;
        }
        return $user->id;
    }

    public function index()
    {
        $dataUserId = $this->getDataUserId();
        $simulacoes = Simulacao::with('insumo')
            ->whereHas('insumo', fn($q) => $q->where('user_id', $dataUserId))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('simulacoes.index', compact('simulacoes'));
    }

    // Liberado para visualizador
    public function create()
    {
        $insumos = Insumo::where('user_id', $this->getDataUserId())->orderBy('nome')->get();
        return view('simulacoes.create', compact('insumos'));
    }

    // Liberado para visualizador (salva simulação)
    public function store(Request $request)
    {
        return redirect()->route('simulacoes.index');
    }

    public function show(Simulacao $simulacao)
    {
        $dataUserId = $this->getDataUserId();
        if ($simulacao->insumo->user_id !== $dataUserId) abort(403);
        $simulacoesRelacionadas = Simulacao::with('receitaAfetada')
            ->where('insumo_id', $simulacao->insumo_id)
            ->where('preco_simulado', $simulacao->preco_simulado)
            ->where('preco_antigo', $simulacao->preco_antigo)
            ->whereBetween('created_at', [
                $simulacao->created_at->subMinute(),
                $simulacao->created_at->addMinute()
            ])
            ->get();
        return view('simulacoes.show', compact('simulacao', 'simulacoesRelacionadas'));
    }

    // Liberado para visualizador? O edit é para reexecutar simulação, mas não salva preço. Pode liberar.
    public function edit(Simulacao $simulacao)
    {
        $dataUserId = $this->getDataUserId();
        if ($simulacao->insumo->user_id !== $dataUserId) abort(403);
        return view('simulacoes.edit', compact('simulacao'));
    }

    public function update(Request $request, Simulacao $simulacao)
    {
        return redirect()->route('simulacoes.index');
    }

    // Liberado para visualizador (apenas simular, não aplicar)
    public function simularVariacaoInsumo(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'insumo_id' => 'required|exists:insumos,id',
            'novo_preco' => 'required|numeric|min:0',
        ]);

        $dataUserId = $this->getDataUserId();
        $insumo = Insumo::where('user_id', $dataUserId)
            ->with(['receitas.insumos', 'receitas.custosVariaveis', 'receitas.cmvCalculos'])
            ->findOrFail($request->insumo_id);

        $novoPreco  = (float) $request->novo_preco;
        $precoAtual = (float) $insumo->preco_unitario;

        $resultados = [];

        foreach ($insumo->receitas as $receita) {
            $cmvAtualObj = $receita->cmvCalculos->first();
            $cmvAtual    = $cmvAtualObj ? (float) $cmvAtualObj->cmv_unitario : (float) $receita->calcularCMV();

            $custoInsumosNovo = 0;
            foreach ($receita->insumos as $insumoReceita) {
                $precoUsar = ($insumoReceita->id == $insumo->id)
                    ? $novoPreco
                    : (float) $insumoReceita->preco_unitario;

                $custoInsumosNovo += (float) $insumoReceita->pivot->quantidade * $precoUsar;
            }

            $custoMaoObra    = (float) $receita->custo_mao_obra_total;
            $custoVariavel   = (float) $receita->custosVariaveis->sum('valor');
            $cmvTotalNovo    = $custoInsumosNovo + $custoMaoObra + $custoVariavel;
            $rendimento      = $receita->rendimento_lote > 0 ? $receita->rendimento_lote : 1;
            $cmvUnitarioNovo = $cmvTotalNovo / $rendimento;

            $margemDecimal       = 0.30;
            $precoSugeridoAntigo = $cmvAtual > 0 ? round($cmvAtual / (1 - $margemDecimal), 2) : 0;
            $precoSugeridoNovo   = $cmvUnitarioNovo > 0 ? round($cmvUnitarioNovo / (1 - $margemDecimal), 2) : 0;
            $impactoPercentual   = $cmvAtual > 0 ? round(($cmvUnitarioNovo - $cmvAtual) / $cmvAtual * 100, 2) : 0;

            $resultados[] = [
                'receita_id'            => $receita->id,
                'receita_nome'          => $receita->nome,
                'cmv_atual'             => number_format($cmvAtual, 4, '.', ''),
                'cmv_novo'              => number_format($cmvUnitarioNovo, 4, '.', ''),
                'impacto_percentual'    => number_format($impactoPercentual, 2, '.', ''),
                'preco_sugerido_antigo' => number_format($precoSugeridoAntigo, 2, '.', ''),
                'preco_sugerido_novo'   => number_format($precoSugeridoNovo, 2, '.', ''),
            ];
        }

        // Salva simulação (histórico) – permite visualizador salvar
        $primeiroId = null;
        foreach ($resultados as $i => $res) {
            $sim = Simulacao::create([
                'insumo_id'           => $insumo->id,
                'receita_afetada_id'  => $res['receita_id'],
                'preco_antigo'        => $precoAtual,
                'preco_simulado'      => $novoPreco,
                'impacto_cmv'         => $res['impacto_percentual'],
                'impacto_preco_venda' => round((float)$res['preco_sugerido_novo'] - (float)$res['preco_sugerido_antigo'], 2),
            ]);
            if ($i === 0) $primeiroId = $sim->id;
        }

        return response()->json([
            'success'           => true,
            'simulacao_id'      => $primeiroId,
            'insumo'            => $insumo->nome,
            'preco_atual'       => number_format($precoAtual, 2, '.', ''),
            'preco_novo'        => number_format($novoPreco, 2, '.', ''),
            'receitas_afetadas' => $resultados,
        ]);
    }

    // Mantém bloqueado para visualizador (essa ação altera preço do insumo)
    public function aplicarSimulacao(Request $request)
    {
        $user = Auth::user();

        if (method_exists($user, 'isVisualizador') && $user->isVisualizador()) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }

        $request->validate([
            'tipo'  => 'required|in:insumo,margem',
            'id'    => 'required',
            'valor' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            if ($request->tipo === 'insumo') {
                $dataUserId = $this->getDataUserId();
                $insumo = Insumo::where('user_id', $dataUserId)
                    ->with('receitas.insumos')
                    ->findOrFail($request->id);

                $precoAntigo = $insumo->preco_unitario;
                $novoPreco   = (float) $request->valor;

                $insumo->preco_unitario = $novoPreco;
                $insumo->save();

                foreach ($insumo->receitas as $receita) {
                    $pivotInsumo = $receita->insumos()
                        ->wherePivot('insumo_id', $insumo->id)
                        ->first();

                    if ($pivotInsumo) {
                        $quantidade = (float) $pivotInsumo->pivot->quantidade;
                        $receita->insumos()->updateExistingPivot($insumo->id, [
                            'custo_unitario' => $novoPreco,
                            'custo_total'    => round($quantidade * $novoPreco, 4),
                        ]);
                    }

                    CmvCalculo::calcularParaReceita($receita->id);
                }

                \App\Models\Log::create([
                    'user_id'        => $user->id,
                    'acao'           => 'update',
                    'tabela_afetada' => 'insumos',
                    'registro_id'    => $insumo->id,
                    'descricao'      => "Preço alterado de R$ {$precoAntigo} para R$ {$novoPreco} via simulação",
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function simularMargem(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'receita_id'      => 'required|exists:receitas,id',
            'margem_desejada' => 'required|numeric|min:0|max:100',
        ]);

        $dataUserId = $this->getDataUserId();
        $receita = Receita::where('user_id', $dataUserId)->findOrFail($request->receita_id);
        $cmv = (float) $receita->calcularCMV();
        $margem = (float) $request->margem_desejada / 100;
        $precoSugerido = $margem < 1 ? round($cmv / (1 - $margem), 2) : 0;

        return response()->json([
            'success'         => true,
            'receita_nome'    => $receita->nome,
            'cmv_unitario'    => number_format($cmv, 4, '.', ''),
            'preco_sugerido'  => number_format($precoSugerido, 2, '.', ''),
            'margem_desejada' => $request->margem_desejada,
        ]);
    }
}
