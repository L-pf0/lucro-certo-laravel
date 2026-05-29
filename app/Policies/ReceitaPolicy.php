<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Receita;

class ReceitaPolicy
{
    public function view(User $user, Receita $receita)
    {
        return $user->id === $receita->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'gestor';
    }

    public function update(User $user, Receita $receita)
    {
        return $user->role === 'gestor' && $user->id === $receita->user_id;
    }

    public function delete(User $user, Receita $receita)
    {
        return $user->role === 'gestor' && $user->id === $receita->user_id;
    }
}
