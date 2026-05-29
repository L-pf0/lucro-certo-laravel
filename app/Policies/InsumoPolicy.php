<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Insumo;

class InsumoPolicy
{
    public function view(User $user, Insumo $insumo)
    {
        return $user->id === $insumo->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'gestor';
    }

    public function update(User $user, Insumo $insumo)
    {
        return $user->role === 'gestor' && $user->id === $insumo->user_id;
    }

    public function delete(User $user, Insumo $insumo)
    {
        return $user->role === 'gestor' && $user->id === $insumo->user_id;
    }
}
