<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaoDeObra extends Model
{
    use HasFactory;

    protected $table = 'mao_de_obra';

    protected $fillable = [
        'user_id',
        'descricao',
        'valor_total',
        'tempo_horas',
        'valor_hora',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento muitos-para-muitos com Receita
    public function receitas()
    {
        return $this->belongsToMany(Receita::class, 'receita_mao_de_obra')
            ->withPivot('horas')
            ->withTimestamps();
    }
}
