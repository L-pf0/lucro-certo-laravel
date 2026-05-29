<?php

namespace App\Http\Controllers;

use App\Models\CustoFixo;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustoFixoController extends Controller
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
        $custos = CustoFixo::where('user_id', $dataUserId)
            ->orderBy('mes_referencia', 'desc')
            ->paginate(15);
        return view('custos-fixos.index', compact('custos'));
    }

    public function create()
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        return view('custos-fixos.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_mensal' => 'required|numeric|min:0',
            'mes_referencia' => 'required|date_format:Y-m',
        ]);

        $custo = Auth::user()->custosFixos()->create($validated); // cria com o user_id do próprio gestor (ok)

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'insert',
            'tabela_afetada' => 'custos_fixos',
            'registro_id' => $custo->id,
            'descricao' => "Custo fixo '{$custo->descricao}' de R$ {$custo->valor_mensal} para {$custo->mes_referencia}",
        ]);

        return redirect()->route('custos-fixos.index')->with('success', 'Custo fixo cadastrado.');
    }

    public function show(CustoFixo $custoFixo)
    {
        return view('custos-fixos.show', compact('custoFixo'));
    }

    public function edit(CustoFixo $custoFixo)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        return view('custos-fixos.edit', compact('custoFixo'));
    }

    public function update(Request $request, CustoFixo $custoFixo)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_mensal' => 'required|numeric|min:0',
            'mes_referencia' => 'required|date_format:Y-m',
        ]);

        $custoFixo->update($validated);

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'update',
            'tabela_afetada' => 'custos_fixos',
            'registro_id' => $custoFixo->id,
            'descricao' => "Custo fixo '{$custoFixo->descricao}' atualizado",
        ]);

        return redirect()->route('custos-fixos.index')->with('success', 'Custo fixo atualizado.');
    }

    public function destroy(CustoFixo $custoFixo)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $descricao = $custoFixo->descricao;
        $custoFixo->delete();

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'delete',
            'tabela_afetada' => 'custos_fixos',
            'registro_id' => $custoFixo->id,
            'descricao' => "Custo fixo '{$descricao}' removido",
        ]);

        return redirect()->route('custos-fixos.index')->with('success', 'Custo fixo removido.');
    }
}
