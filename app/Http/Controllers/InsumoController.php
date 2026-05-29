<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InsumoController extends Controller
{
    use AuthorizesRequests;

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
        $insumos = Insumo::where('user_id', $this->getDataUserId())->paginate(15);
        return view('insumos.index', compact('insumos'));
    }

    public function create()
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        return view('insumos.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'nome'             => 'required|string|max:255',
            'unidade_medida'   => 'required|in:kg,g,l,ml,unidade',
            'quantidade_padrao' => 'nullable|numeric|min:0',
            'preco_total'      => 'nullable|numeric|min:0',
            'preco_unitario'   => 'nullable|numeric|min:0',
        ]);

        if (!empty($validated['quantidade_padrao']) && !empty($validated['preco_total'])) {
            $precoUnitario = $validated['preco_total'] / $validated['quantidade_padrao'];
        } elseif (!empty($validated['preco_unitario'])) {
            $precoUnitario = $validated['preco_unitario'];
        } else {
            return back()->withErrors(['preco_unitario' => 'Informe o preço unitário OU a quantidade padrão com o preço total.']);
        }

        $insumo = Auth::user()->insumos()->create([
            'nome'             => $validated['nome'],
            'unidade_medida'   => $validated['unidade_medida'],
            'quantidade_padrao' => $validated['quantidade_padrao'] ?? null,
            'preco_total'      => $validated['preco_total'] ?? null,
            'preco_unitario'   => $precoUnitario,
        ]);

        Log::create([
            'user_id'        => Auth::id(),
            'acao'           => 'insert',
            'tabela_afetada' => 'insumos',
            'registro_id'    => $insumo->id,
            'descricao'      => "Insumo '{$insumo->nome}' criado (preço unitário R$ {$insumo->preco_unitario})",
        ]);

        return redirect()->route('insumos.index')->with('success', 'Insumo cadastrado.');
    }

    public function show(Insumo $insumo)
    {
        $dataUserId = $this->getDataUserId();
        if ($insumo->user_id !== $dataUserId) {
            abort(403);
        }
        return view('insumos.show', compact('insumo'));
    }

    public function edit(Insumo $insumo)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        return view('insumos.edit', compact('insumo'));
    }

    public function update(Request $request, Insumo $insumo)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'nome'             => 'required|string|max:255',
            'unidade_medida'   => 'required|in:kg,g,l,ml,unidade',
            'quantidade_padrao' => 'nullable|numeric|min:0',
            'preco_total'      => 'nullable|numeric|min:0',
            'preco_unitario'   => 'nullable|numeric|min:0',
        ]);

        if (!empty($validated['quantidade_padrao']) && !empty($validated['preco_total'])) {
            $precoUnitario = $validated['preco_total'] / $validated['quantidade_padrao'];
        } elseif (!empty($validated['preco_unitario'])) {
            $precoUnitario = $validated['preco_unitario'];
        } else {
            $precoUnitario = $insumo->preco_unitario;
        }

        $insumo->update([
            'nome'             => $validated['nome'],
            'unidade_medida'   => $validated['unidade_medida'],
            'quantidade_padrao' => $validated['quantidade_padrao'] ?? null,
            'preco_total'      => $validated['preco_total'] ?? null,
            'preco_unitario'   => $precoUnitario,
        ]);

        Log::create([
            'user_id'        => Auth::id(),
            'acao'           => 'update',
            'tabela_afetada' => 'insumos',
            'registro_id'    => $insumo->id,
            'descricao'      => "Insumo '{$insumo->nome}' atualizado - novo preço unitário R$ {$insumo->preco_unitario}",
        ]);

        return redirect()->route('insumos.index')->with('success', 'Insumo atualizado.');
    }

    public function destroy(Insumo $insumo)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $nome = $insumo->nome;
        $insumo->delete();

        Log::create([
            'user_id'        => Auth::id(),
            'acao'           => 'delete',
            'tabela_afetada' => 'insumos',
            'registro_id'    => $insumo->id,
            'descricao'      => "Insumo '{$nome}' removido",
        ]);

        return redirect()->route('insumos.index')->with('success', 'Insumo removido.');
    }
}
