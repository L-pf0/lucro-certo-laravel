<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CmvCalculo;

class CmvCalculoPolicy
{
    public function view(User $user, CmvCalculo $cmvCalculo)
    {
        return $user->id === $cmvCalculo->receita->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'gestor';
    }

    // Geralmente não se edita ou deleta CMV diretamente, mas deixaremos a regra padrão
    public function update(User $user, CmvCalculo $cmvCalculo)
    {
        return $user->role === 'gestor' && $user->id === $cmvCalculo->receita->user_id;
    }

    public function delete(User $user, CmvCalculo $cmvCalculo)
    {
        return $user->role === 'gestor' && $user->id === $cmvCalculo->receita->user_id;
    }
}
