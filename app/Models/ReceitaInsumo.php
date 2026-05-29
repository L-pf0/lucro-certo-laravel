<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReceitaInsumo extends Pivot
{
    protected $table = 'receita_insumos';
    protected $fillable = ['receita_id', 'insumo_id', 'quantidade', 'custo_unitario', 'custo_total'];
    protected $casts = [
        'quantidade' => 'decimal:3',
        'custo_unitario' => 'decimal:2',
        'custo_total' => 'decimal:2',
    ];
}
