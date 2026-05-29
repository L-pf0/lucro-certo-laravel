<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Receita;
use App\Models\Insumo;
use App\Models\CustoFixo;
use App\Models\CustoVariavel;
use App\Models\MaoDeObra;
use App\Models\CmvCalculo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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

        // ----- KPIs -----
        $totalReceitas = Receita::where('user_id', $dataUserId)->count();
        $totalInsumos = Insumo::where('user_id', $dataUserId)->count();

        // Últimos cálculos de CMV (para médias)
        $ultimosCalculos = CmvCalculo::whereHas('receita', function ($q) use ($dataUserId) {
            $q->where('user_id', $dataUserId);
        })->latest()->take(10)->get();

        $cmvMedio = $ultimosCalculos->avg('cmv_unitario') ?? 0;
        $margemMedia = $ultimosCalculos->avg('percentual_lucro') ?? 0;

        // Lucro estimado (simplificado)
        $totalCustosFixosMes = CustoFixo::where('user_id', $dataUserId)
            ->where('mes_referencia', now()->format('Y-m'))
            ->sum('valor_mensal');
        $lucroEstimado = $totalReceitas * ($margemMedia / 100) - $totalCustosFixosMes;

        // ----- Resumo de Custos (real) -----
        $totalCustosFixos = $totalCustosFixosMes;

        $totalCustosVariaveis = CustoVariavel::where('user_id', $dataUserId)->sum('valor');

        // Custo total de mão de obra (soma de horas * valor_hora de todas as associações)
        $totalMaoObra = DB::table('receita_mao_de_obra')
            ->join('mao_de_obra', 'receita_mao_de_obra.mao_de_obra_id', '=', 'mao_de_obra.id')
            ->where('mao_de_obra.user_id', $dataUserId)
            ->sum(DB::raw('receita_mao_de_obra.horas * mao_de_obra.valor_hora'));

        // Custo total de insumos (considerando o último CMV de cada receita)
        $totalInsumosCusto = 0;
        $receitas = Receita::where('user_id', $dataUserId)
            ->with(['cmvCalculos' => function ($q) {
                $q->latest()->limit(1);
            }])->get();
        foreach ($receitas as $receita) {
            $cmv = $receita->cmvCalculos->first();
            if ($cmv) {
                $totalInsumosCusto += $cmv->custo_insumos_total;
            }
        }

        // ----- Dados auxiliares -----
        $ultimosInsumos = Insumo::where('user_id', $dataUserId)->latest('updated_at')->take(3)->get();

        // Receitas mais lucrativas (baseado na margem do último cálculo)
        $receitasLucrativas = [];
        foreach ($receitas as $receita) {
            $cmv = $receita->cmvCalculos->first();
            if ($cmv && $cmv->preco_sugerido > 0) {
                $margem = $cmv->percentual_lucro;
                $preco = $cmv->preco_sugerido;
                $receitasLucrativas[] = [
                    'nome'   => $receita->nome,
                    'margem' => $margem,
                    'preco'  => $preco,
                ];
            }
        }
        $receitasLucrativas = collect($receitasLucrativas)->sortByDesc('margem')->take(3)->toArray();

        // Alertas importantes
        $receitasSemPreco = Receita::where('user_id', $dataUserId)->whereDoesntHave('cmvCalculos')->count();

        return view('dashboard', compact(
            'totalReceitas',
            'totalInsumos',
            'cmvMedio',
            'margemMedia',
            'lucroEstimado',
            'totalCustosFixos',
            'totalCustosVariaveis',
            'totalMaoObra',
            'totalInsumosCusto',
            'ultimosInsumos',
            'receitasLucrativas',
            'receitasSemPreco'
        ));
    }
}
