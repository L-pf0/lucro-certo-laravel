<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome',
        'rendimento_lote',
        'tempo_preparo_horas'
    ];

    protected $casts = [
        'tempo_preparo_horas' => 'decimal:2',
        'rendimento_lote' => 'integer'
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'receita_insumos')
            ->using(ReceitaInsumo::class)
            ->withPivot('quantidade', 'custo_unitario', 'custo_total')
            ->withTimestamps();
    }

    public function maosDeObra()
    {
        return $this->belongsToMany(MaoDeObra::class, 'receita_mao_de_obra')
            ->withPivot('horas')
            ->withTimestamps();
    }

    public function custosVariaveis()
    {
        return $this->hasMany(CustoVariavel::class);
    }

    public function cmvCalculos()
    {
        return $this->hasMany(CmvCalculo::class);
    }

    public function simulacoes()
    {
        return $this->hasMany(Simulacao::class, 'receita_afetada_id');
    }
}
