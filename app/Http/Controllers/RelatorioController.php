<?php

namespace App\Http\Controllers;

use App\Models\Relatorio;
use App\Models\Receita;
use App\Models\CmvCalculo;
use App\Models\User;
use App\Models\CustoFixo;
use App\Models\CustoVariavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
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
        $relatorios = Relatorio::orderBy('created_at', 'desc')->paginate(15);
        return view('relatorios.index', compact('relatorios'));
    }

    public function create()
    {
        $receitas = Receita::where('user_id', $this->getDataUserId())->get();
        return view('relatorios.create', compact('receitas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:pdf,dre',
            'periodo' => 'required|date_format:Y-m',
            'receita_id' => 'nullable|exists:receitas,id',
        ]);

        $arquivoUrl = null;
        $dados = $this->coletarDadosRelatorio($validated);

        Storage::disk('local')->makeDirectory('relatorios');

        if ($validated['tipo'] === 'pdf') {
            $pdf = Pdf::loadView('relatorios.pdf.pdf', $dados);
            $nomeArquivo = 'relatorio_pdf_' . $validated['periodo'] . '_' . time() . '.pdf';
            $caminho = 'relatorios/' . $nomeArquivo;
            Storage::put($caminho, $pdf->output());
            $arquivoUrl = Storage::url($caminho);
        } elseif ($validated['tipo'] === 'dre') {
            $pdf = Pdf::loadView('relatorios.pdf.dre', $dados);
            $nomeArquivo = 'dre_' . $validated['periodo'] . '_' . time() . '.pdf';
            $caminho = 'relatorios/' . $nomeArquivo;
            Storage::put($caminho, $pdf->output());
            $arquivoUrl = Storage::url($caminho);
        }

        $relatorio = Relatorio::create([
            'tipo' => $validated['tipo'],
            'periodo' => $validated['periodo'],
            'arquivo_url' => $arquivoUrl,
        ]);

        return redirect()->route('relatorios.index')->with('success', 'Relatório gerado com sucesso!');
    }

    public function show(Relatorio $relatorio)
    {
        return view('relatorios.show', compact('relatorio'));
    }

    // Agora qualquer usuário logado pode deletar (visualizador também)
    public function destroy(Relatorio $relatorio)
    {
        if ($relatorio->arquivo_url) {
            $caminho = str_replace('/storage/', '', $relatorio->arquivo_url);
            Storage::delete($caminho);
        }
        $relatorio->delete();
        return redirect()->route('relatorios.index')->with('success', 'Relatório removido.');
    }

    private function coletarDadosRelatorio($params)
    {
        $user = Auth::user();
        $periodo = $params['periodo'];
        $receitaId = $params['receita_id'] ?? null;
        $dataUserId = $this->getDataUserId();

        $dados = [
            'periodo' => $periodo,
            'usuario' => $user->name,
            'data_geracao' => now()->format('d/m/Y H:i'),
        ];

        $query = CmvCalculo::whereHas('receita', function ($q) use ($dataUserId, $receitaId) {
            $q->where('user_id', $dataUserId);
            if ($receitaId) {
                $q->where('id', $receitaId);
            }
        })->with('receita')->orderBy('created_at', 'desc');

        $dados['dados'] = $query->get()->map(function ($cmv) {
            return [
                'Receita' => $cmv->receita->nome,
                'CMV Unitário' => 'R$ ' . number_format($cmv->cmv_unitario, 4, ',', '.'),
                'Preço Sugerido' => 'R$ ' . number_format($cmv->preco_sugerido, 2, ',', '.'),
                'Margem de Contribuição' => 'R$ ' . number_format($cmv->margem_contribuicao, 4, ',', '.'),
                'Percentual Lucro' => $cmv->percentual_lucro . '%',
                'Data Cálculo' => $cmv->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        if ($params['tipo'] === 'dre') {
            $receitasMes = Receita::where('user_id', $dataUserId)
                ->whereMonth('created_at', substr($periodo, 5, 2))
                ->whereYear('created_at', substr($periodo, 0, 4))
                ->get();

            $totalVendas = 0;
            $totalCustos = $receitasMes->sum(function ($r) {
                $cmv = $r->cmvCalculos()->latest()->first();
                return $cmv ? $cmv->custo_insumos_total + $cmv->custo_mao_obra : 0;
            });
            $despesasFixas = CustoFixo::where('user_id', $dataUserId)->where('mes_referencia', $periodo)->sum('valor_mensal');
            $despesasVariaveis = CustoVariavel::where('user_id', $dataUserId)->where('tipo', 'geral')->sum('valor');

            $dados['dre'] = [
                'receitas_brutas' => $totalVendas,
                'custos_variaveis' => $totalCustos,
                'despesas_fixas' => $despesasFixas,
                'despesas_variaveis' => $despesasVariaveis,
                'lucro_liquido' => $totalVendas - ($totalCustos + $despesasFixas + $despesasVariaveis),
            ];
        }

        return $dados;
    }

    public function download(Relatorio $relatorio)
    {
        if (!$relatorio->arquivo_url) {
            abort(404);
        }

        $caminho = str_replace('/storage/', '', $relatorio->arquivo_url);

        if (!Storage::exists($caminho)) {
            abort(404);
        }

        return Storage::download($caminho);
    }
}
