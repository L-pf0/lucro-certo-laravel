<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CustoFixo;

class CustoFixoPolicy
{
    public function view(User $user, CustoFixo $custoFixo)
    {
        return $user->id === $custoFixo->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'gestor';
    }

    public function update(User $user, CustoFixo $custoFixo)
    {
        return $user->role === 'gestor' && $user->id === $custoFixo->user_id;
    }

    public function delete(User $user, CustoFixo $custoFixo)
    {
        return $user->role === 'gestor' && $user->id === $custoFixo->user_id;
    }
}
