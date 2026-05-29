<?php

namespace App\Http\Controllers;

use App\Models\MaoDeObra;
use App\Models\Receita;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaoDeObraController extends Controller
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
        $maoDeObra = MaoDeObra::where('user_id', $this->getDataUserId())->with('receitas')->paginate(15);
        return view('mao-de-obra.index', compact('maoDeObra'));
    }

    public function create()
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        $receitas = Receita::where('user_id', $this->getDataUserId())->get();
        return view('mao-de-obra.create', compact('receitas'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_total' => 'nullable|numeric|min:0',
            'tempo_horas' => 'required|numeric|min:0',
            'valor_hora' => 'nullable|numeric|min:0',
            'receita_id' => 'nullable|exists:receitas,id',
        ]);

        if (empty($validated['valor_total']) && !empty($validated['valor_hora'])) {
            $validated['valor_total'] = $validated['tempo_horas'] * $validated['valor_hora'];
        } elseif (empty($validated['valor_hora']) && !empty($validated['valor_total'])) {
            $validated['valor_hora'] = $validated['valor_total'] / $validated['tempo_horas'];
        }

        $receitaId = $validated['receita_id'] ?? null;
        unset($validated['receita_id']);

        $registro = Auth::user()->maoDeObra()->create($validated);

        if ($receitaId) {
            $registro->receitas()->sync([$receitaId => ['horas' => $validated['tempo_horas']]]);
            $this->atualizarCustoMaoObraReceita($receitaId);
        }

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'insert',
            'tabela_afetada' => 'mao_de_obra',
            'registro_id' => $registro->id,
            'descricao' => "Registro de mão de obra '{$registro->descricao}' - R$ {$registro->valor_total}",
        ]);

        return redirect()->route('mao-de-obra.index')->with('success', 'Registro cadastrado.');
    }

    public function show(MaoDeObra $maoDeObra)
    {
        $dataUserId = $this->getDataUserId();
        if ($maoDeObra->user_id !== $dataUserId) {
            abort(403);
        }
        $maoDeObra->load('receitas');
        return view('mao-de-obra.show', compact('maoDeObra'));
    }

    public function edit(MaoDeObra $maoDeObra)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }
        $receitas = Receita::where('user_id', $this->getDataUserId())->get();
        $maoDeObra->load('receitas');
        return view('mao-de-obra.edit', compact('maoDeObra', 'receitas'));
    }

    public function update(Request $request, MaoDeObra $maoDeObra)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_total' => 'nullable|numeric|min:0',
            'tempo_horas' => 'required|numeric|min:0',
            'valor_hora' => 'nullable|numeric|min:0',
            'receita_id' => 'nullable|exists:receitas,id',
        ]);

        if (empty($validated['valor_total']) && !empty($validated['valor_hora'])) {
            $validated['valor_total'] = $validated['tempo_horas'] * $validated['valor_hora'];
        } elseif (empty($validated['valor_hora']) && !empty($validated['valor_total'])) {
            $validated['valor_hora'] = $validated['valor_total'] / $validated['tempo_horas'];
        }

        $receitaAntiga = $maoDeObra->receitas()->first()?->id;
        $receitaNova = $validated['receita_id'] ?? null;
        unset($validated['receita_id']);

        $maoDeObra->update($validated);

        if ($receitaNova) {
            $maoDeObra->receitas()->sync([$receitaNova => ['horas' => $validated['tempo_horas']]]);
        } else {
            $maoDeObra->receitas()->detach();
        }

        if ($receitaAntiga) {
            $this->atualizarCustoMaoObraReceita($receitaAntiga);
        }
        if ($receitaNova && $receitaNova != $receitaAntiga) {
            $this->atualizarCustoMaoObraReceita($receitaNova);
        }

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'update',
            'tabela_afetada' => 'mao_de_obra',
            'registro_id' => $maoDeObra->id,
            'descricao' => "Registro de mão de obra '{$maoDeObra->descricao}' atualizado",
        ]);

        return redirect()->route('mao-de-obra.index')->with('success', 'Registro atualizado.');
    }

    public function destroy(MaoDeObra $maoDeObra)
    {
        if (auth()->user()->isVisualizador()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado.');
        }

        $maoDeObraId = $maoDeObra->id;
        $receitaId = $maoDeObra->receitas()->first()?->id;
        $descricao = $maoDeObra->descricao;

        $maoDeObra->receitas()->detach();
        MaoDeObra::whereKey($maoDeObraId)->delete();

        if ($receitaId) {
            $this->atualizarCustoMaoObraReceita($receitaId);
        }

        Log::create([
            'user_id' => Auth::id(),
            'acao' => 'delete',
            'tabela_afetada' => 'mao_de_obra',
            'registro_id' => $maoDeObra->id,
            'descricao' => "Registro de mão de obra '{$descricao}' removido",
        ]);

        return redirect()->route('mao-de-obra.index')->with('success', 'Registro removido.');
    }

    private function atualizarCustoMaoObraReceita($receitaId)
    {
        $receita = Receita::query()->whereKey($receitaId)->first();
        if ($receita) {
            $totalMaoObra = $receita->maosDeObra()->sum('mao_de_obra.valor_total');
            $receita->update(['custo_mao_obra_total' => $totalMaoObra]);
        }
    }
}
