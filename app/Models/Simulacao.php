<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulacao extends Model
{
    use HasFactory;

    // Nome correto da tabela no banco
    protected $table = 'simulacoes';
    
    protected $fillable = [
        'insumo_id', 'receita_afetada_id', 'preco_antigo', 'preco_simulado',
        'impacto_cmv', 'impacto_preco_venda',
    ];

    protected $casts = [
        'preco_antigo' => 'decimal:2',
        'preco_simulado' => 'decimal:2',
        'impacto_cmv' => 'decimal:4',
        'impacto_preco_venda' => 'decimal:2',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    public function receitaAfetada()
    {
        return $this->belongsTo(Receita::class, 'receita_afetada_id');
    }
}