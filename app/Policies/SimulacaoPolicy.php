<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Simulacao;

class SimulacaoPolicy
{
    public function view(User $user, Simulacao $simulacao)
    {
        // Simulação pertence a um insumo, que tem user_id
        return $user->id === $simulacao->insumo->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'gestor';
    }

    public function update(User $user, Simulacao $simulacao)
    {
        return $user->role === 'gestor' && $user->id === $simulacao->insumo->user_id;
    }

    public function delete(User $user, Simulacao $simulacao)
    {
        return $user->role === 'gestor' && $user->id === $simulacao->insumo->user_id;
    }
}
