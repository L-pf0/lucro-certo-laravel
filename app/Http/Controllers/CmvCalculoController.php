<?php

namespace App\Http\Controllers;

use App\Models\CmvCalculo;
use App\Models\Receita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmvCalculoController extends Controller
{
    /**
     * Retorna o ID do usuário que deve ser usado para filtrar os dados.
     * Se for visualizador, busca o primeiro gestor; caso contrário, retorna o próprio ID.
     */
    private function getDataUserId()
    {
        $user = Auth::user();
        if ($user->isVisualizador()) {
            $gestor = User::where('role', 'gestor')->first();
            return $gestor ? $gestor->id : $user->id;
        }
        return $user->id;
    }

    public function index(Request $request)
    {
        $dataUserId = $this->getDataUserId();

        $query = CmvCalculo::whereHas('receita', function ($q) use ($dataUserId) {
            $q->where('user_id', $dataUserId);
        })->with('receita');

        if ($request->filled('receita_id')) {
            $query->where('receita_id', $request->receita_id);
        }

        $calculos = $query->orderBy('created_at', 'desc')->paginate(20);
        $receitas = Receita::where('user_id', $dataUserId)->get();

        return view('cmv.index', compact('calculos', 'receitas'));
    }

    public function show($id)
    {
        $dataUserId = $this->getDataUserId();

        $calculo = CmvCalculo::whereHas('receita', function ($q) use ($dataUserId) {
            $q->where('user_id', $dataUserId);
        })->findOrFail($id);

        return view('cmv.show', compact('calculo'));
    }

    public function recalcular(Request $request, $receitaId)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $dataUserId = $this->getDataUserId();
        $receita = Receita::where('user_id', $dataUserId)->findOrFail($receitaId);

        $receitaController = new ReceitaController();
        $receitaController->calcularCmv($receita);

        return redirect()->route('cmv.index', ['receita_id' => $receita->id])
            ->with('success', 'CMV recalculado com sucesso.');
    }

    public function comparar(Request $request)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'receita_id' => 'required|exists:receitas,id',
            'percentual_lucro' => 'required|numeric|min:0|max:100',
        ]);

        $dataUserId = $this->getDataUserId();
        $receita = Receita::where('user_id', $dataUserId)->findOrFail($request->receita_id);

        $ultimoCmv = $receita->cmvCalculos()->latest()->first();
        if (!$ultimoCmv) {
            return back()->withErrors('Nenhum cálculo de CMV encontrado para esta receita.');
        }

        $novoPreco = $ultimoCmv->cmv_unitario / (1 - ($request->percentual_lucro / 100));
        $novaMargem = $novoPreco - $ultimoCmv->cmv_unitario;

        return view('cmv.comparar', compact('receita', 'ultimoCmv', 'novoPreco', 'novaMargem', 'request'));
    }
}
