<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmvCalculo extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'receita_id',
        'custo_insumos_total',
        'custo_mao_obra',
        'custo_fixo_rateado',
        'custo_variavel_rateado',
        'cmv_unitario',
        'margem_contribuicao',
        'percentual_lucro',
        'preco_sugerido',
    ];

    protected $casts = [
        'custo_insumos_total' => 'decimal:4',
        'custo_mao_obra' => 'decimal:4',
        'custo_fixo_rateado' => 'decimal:4',
        'custo_variavel_rateado' => 'decimal:4',
        'cmv_unitario' => 'decimal:4',
        'margem_contribuicao' => 'decimal:4',
        'percentual_lucro' => 'decimal:2',
        'preco_sugerido' => 'decimal:2',
    ];

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }
}
