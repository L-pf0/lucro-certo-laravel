<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isGestor()
    {
        return $this->role === 'gestor';
    }

    public function isVisualizador()
    {
        return $this->role === 'visualizador';
    }

    // Relacionamentos
    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }

    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }

    public function custosFixos()
    {
        return $this->hasMany(CustoFixo::class);
    }

    public function custosVariaveis()
    {
        return $this->hasMany(CustoVariavel::class);
    }

    public function maoDeObra()
    {
        return $this->hasMany(MaoDeObra::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
