<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CustoVariavel;

class CustoVariavelPolicy
{
    public function view(User $user, CustoVariavel $custoVariavel)
    {
        return $user->id === $custoVariavel->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'gestor';
    }

    public function update(User $user, CustoVariavel $custoVariavel)
    {
        return $user->role === 'gestor' && $user->id === $custoVariavel->user_id;
    }

    public function delete(User $user, CustoVariavel $custoVariavel)
    {
        return $user->role === 'gestor' && $user->id === $custoVariavel->user_id;
    }
}
