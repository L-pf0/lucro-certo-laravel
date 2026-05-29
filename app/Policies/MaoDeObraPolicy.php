<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MaoDeObra;

class MaoDeObraPolicy
{
    public function view(User $user, MaoDeObra $maoDeObra)
    {
        return $user->id === $maoDeObra->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'gestor';
    }

    public function update(User $user, MaoDeObra $maoDeObra)
    {
        return $user->role === 'gestor' && $user->id === $maoDeObra->user_id;
    }

    public function delete(User $user, MaoDeObra $maoDeObra)
    {
        return $user->role === 'gestor' && $user->id === $maoDeObra->user_id;
    }
}
