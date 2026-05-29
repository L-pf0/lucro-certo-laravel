<?php

namespace App\Http\Controllers;

use App\Models\CustoVariavel;
use App\Models\Receita;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustoVariavelController extends Controller
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

    public function index(Request $request)
    {
        $dataUserId = $this->getDataUserId();
        $query = CustoVariavel::where('user_id', $dataUserId)->with('receita');
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        $custos = $query->paginate(15);
        return view('custos-variaveis.index', compact('custos'));
    }

    public function create()
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        $receitas = Receita::where('user_id', $this->getDataUserId())->get();
        return view('custos-variaveis.create', compact('receitas'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'tipo' => 'required|in:produto,geral',
            'receita_id' => 'nullable|exists:receitas,id',
        ]);

        if ($validated['tipo'] == 'produto' && empty($validated['receita_id'])) {
            return back()->withErrors('Para custo do tipo produto, é necessário vincular a uma receita.');
        }

        $custo = Auth::user()->custosVariaveis()->create($validated);

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'insert',
            'tabela_afetada' => 'custos_variaveis',
            'registro_id' => $custo->id,
            'descricao' => "Custo variável '{$custo->descricao}' - R$ {$custo->valor}",
        ]);

        return redirect()->route('custos-variaveis.index')->with('success', 'Custo variável cadastrado.');
    }

    public function show(CustoVariavel $custoVariavel)
    {
        // Verificar se o registro pertence ao usuário dono dos dados (gestor)
        $dataUserId = $this->getDataUserId();
        if ($custoVariavel->user_id !== $dataUserId) {
            abort(403);
        }
        return view('custos-variaveis.show', compact('custoVariavel'));
    }

    public function edit(CustoVariavel $custoVariavel)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        $receitas = Receita::where('user_id', $this->getDataUserId())->get();
        return view('custos-variaveis.edit', compact('custoVariavel', 'receitas'));
    }

    public function update(Request $request, CustoVariavel $custoVariavel)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'tipo' => 'required|in:produto,geral',
            'receita_id' => 'nullable|exists:receitas,id',
        ]);

        if ($validated['tipo'] == 'produto' && empty($validated['receita_id'])) {
            return back()->withErrors('Para custo do tipo produto, é necessário vincular a uma receita.');
        }

        $custoVariavel->update($validated);

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'update',
            'tabela_afetada' => 'custos_variaveis',
            'registro_id' => $custoVariavel->id,
            'descricao' => "Custo variável '{$custoVariavel->descricao}' atualizado",
        ]);

        return redirect()->route('custos-variaveis.index')->with('success', 'Custo variável atualizado.');
    }

    public function destroy(CustoVariavel $custoVariavel)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $descricao = $custoVariavel->descricao;
        $custoVariavel->delete();

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'delete',
            'tabela_afetada' => 'custos_variaveis',
            'registro_id' => $custoVariavel->id,
            'descricao' => "Custo variável '{$descricao}' removido",
        ]);

        return redirect()->route('custos-variaveis.index')->with('success', 'Custo variável removido.');
    }
}
