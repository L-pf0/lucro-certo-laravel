<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome',
        'unidade_medida',
        'preco_unitario',
        'quantidade_padrao',
        'preco_total'
    ];
    protected $casts = ['preco_unitario' => 'decimal:2'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receitas()
    {
        return $this->belongsToMany(Receita::class, 'receita_insumos')
            ->using(ReceitaInsumo::class)
            ->withPivot('quantidade', 'custo_unitario', 'custo_total')
            ->withTimestamps();
    }

    public function simulacoes()
    {
        return $this->hasMany(Simulacao::class);
    }
}
