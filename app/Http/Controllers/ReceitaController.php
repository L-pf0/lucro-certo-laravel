<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Insumo;
use App\Models\MaoDeObra;
use App\Models\CmvCalculo;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceitaController extends Controller
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
        $receitas = Receita::where('user_id', $dataUserId)
            ->with(['insumos', 'cmvCalculos' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->paginate(15);

        return view('receitas.index', compact('receitas'));
    }

    public function create()
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        $insumos = Insumo::where('user_id', $this->getDataUserId())->orderBy('nome')->get();
        $maosDeObra = MaoDeObra::where('user_id', $this->getDataUserId())->orderBy('descricao')->get();
        return view('receitas.create', compact('insumos', 'maosDeObra'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'nome'                => 'required|string|max:255',
            'rendimento_lote'     => 'required|integer|min:1',
            'tempo_preparo_horas' => 'required|numeric|min:0',
            'insumos'             => 'required|array|min:1',
            'insumos.*.id'        => 'required|exists:insumos,id',
            'insumos.*.quantidade' => 'required|numeric|min:0.001',
            'mao_de_obra'         => 'nullable|array',
        ]);

        $insumosUnicos = collect($validated['insumos'])->unique('id')->values()->toArray();
        if (count($insumosUnicos) < count($validated['insumos'])) {
            return back()->withErrors('Existem insumos duplicados na lista. Remova as repetições.');
        }

        DB::beginTransaction();
        try {
            $receita = Auth::user()->receitas()->create([
                'nome'                => $validated['nome'],
                'rendimento_lote'     => $validated['rendimento_lote'],
                'tempo_preparo_horas' => $validated['tempo_preparo_horas'],
            ]);

            foreach ($insumosUnicos as $item) {
                $insumo = Insumo::find($item['id']);
                $custoTotal = $insumo->preco_unitario * $item['quantidade'];
                $receita->insumos()->attach($insumo->id, [
                    'quantidade'      => $item['quantidade'],
                    'custo_unitario'  => $insumo->preco_unitario,
                    'custo_total'     => $custoTotal,
                ]);
            }

            if ($request->has('mao_de_obra')) {
                $syncData = [];
                foreach ($request->mao_de_obra as $modId => $dados) {
                    if (isset($dados['selecionado']) && $dados['selecionado'] == 1 && !empty($dados['horas'])) {
                        $syncData[$modId] = ['horas' => (float) $dados['horas']];
                    }
                }
                $receita->maosDeObra()->sync($syncData);
            }

            $this->calcularCmv($receita);

            Log::create([
                'user_id'        => Auth::id(),
                'acao'           => 'insert',
                'tabela_afetada' => 'receitas',
                'registro_id'    => $receita->id,
                'descricao'      => "Receita '{$receita->nome}' criada",
            ]);

            DB::commit();
            return redirect()->route('receitas.index')->with('success', 'Receita criada e CMV calculado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function show(Receita $receita)
    {
        $dataUserId = $this->getDataUserId();
        if ($receita->user_id !== $dataUserId) {
            abort(403);
        }
        $receita->load('insumos', 'cmvCalculos', 'maosDeObra');
        return view('receitas.show', compact('receita'));
    }

    public function edit(Receita $receita)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        $insumos = Insumo::where('user_id', $this->getDataUserId())->orderBy('nome')->get();
        $maosDeObra = MaoDeObra::where('user_id', $this->getDataUserId())->orderBy('descricao')->get();
        $maosVinculadas = $receita->maosDeObra;
        return view('receitas.edit', compact('receita', 'insumos', 'maosDeObra', 'maosVinculadas'));
    }

    public function update(Request $request, Receita $receita)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'nome'                => 'required|string|max:255',
            'rendimento_lote'     => 'required|integer|min:1',
            'tempo_preparo_horas' => 'required|numeric|min:0',
            'insumos'             => 'required|array|min:1',
            'insumos.*.id'        => 'required|exists:insumos,id',
            'insumos.*.quantidade' => 'required|numeric|min:0.001',
            'mao_de_obra'         => 'nullable|array',
        ]);

        $insumosUnicos = collect($validated['insumos'])->unique('id')->values()->toArray();
        if (count($insumosUnicos) < count($validated['insumos'])) {
            return back()->withErrors('Existem insumos duplicados. Remova as repetições.');
        }

        DB::beginTransaction();
        try {
            $receita->update([
                'nome'                => $validated['nome'],
                'rendimento_lote'     => $validated['rendimento_lote'],
                'tempo_preparo_horas' => $validated['tempo_preparo_horas'],
            ]);

            $syncData = [];
            foreach ($insumosUnicos as $item) {
                $insumo = Insumo::find($item['id']);
                $custoTotal = $insumo->preco_unitario * $item['quantidade'];
                $syncData[$insumo->id] = [
                    'quantidade'      => $item['quantidade'],
                    'custo_unitario'  => $insumo->preco_unitario,
                    'custo_total'     => $custoTotal,
                ];
            }
            $receita->insumos()->sync($syncData);

            if ($request->has('mao_de_obra')) {
                $syncMao = [];
                foreach ($request->mao_de_obra as $modId => $dados) {
                    if (isset($dados['selecionado']) && $dados['selecionado'] == 1 && !empty($dados['horas'])) {
                        $syncMao[$modId] = ['horas' => (float) $dados['horas']];
                    }
                }
                $receita->maosDeObra()->sync($syncMao);
            } else {
                $receita->maosDeObra()->detach();
            }

            $this->calcularCmv($receita);

            Log::create([
                'user_id'        => Auth::id(),
                'acao'           => 'update',
                'tabela_afetada' => 'receitas',
                'registro_id'    => $receita->id,
                'descricao'      => "Receita '{$receita->nome}' atualizada",
            ]);

            DB::commit();
            return redirect()->route('receitas.index')->with('success', 'Receita atualizada e CMV recalculado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Erro ao atualizar: ' . $e->getMessage());
        }
    }

    public function destroy(Receita $receita)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $nome = $receita->nome;
        $receita->delete();

        Log::create([
            'user_id'        => Auth::id(),
            'acao'           => 'delete',
            'tabela_afetada' => 'receitas',
            'registro_id'    => $receita->id,
            'descricao'      => "Receita '{$nome}' removida",
        ]);

        return redirect()->route('receitas.index')->with('success', 'Receita removida.');
    }

    public function calcularCmv(Receita $receita)
    {
        $custoInsumos = $receita->insumos->sum(function ($insumo) {
            return $insumo->pivot->custo_total;
        });

        $custoMaoObra = $receita->maosDeObra->sum(function ($mod) {
            return $mod->pivot->horas * $mod->valor_hora;
        });

        $cmvTotal = $custoInsumos + $custoMaoObra;
        $cmvUnitario = $receita->rendimento_lote > 0 ? $cmvTotal / $receita->rendimento_lote : $cmvTotal;

        $percentualLucro = 30;
        $precoSugerido = $cmvUnitario / (1 - ($percentualLucro / 100));
        $margemContribuicao = $precoSugerido - $cmvUnitario;

        CmvCalculo::create([
            'receita_id'          => $receita->id,
            'custo_insumos_total' => $custoInsumos,
            'custo_mao_obra'      => $custoMaoObra,
            'custo_fixo_rateado'  => 0,
            'custo_variavel_rateado' => 0,
            'cmv_unitario'        => $cmvUnitario,
            'margem_contribuicao' => $margemContribuicao,
            'percentual_lucro'    => $percentualLucro,
            'preco_sugerido'      => $precoSugerido,
        ]);
    }
}
