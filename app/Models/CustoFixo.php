<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustoFixo extends Model
{
    use HasFactory;

    protected $table = 'custos_fixos';

    protected $fillable = [
        'user_id',
        'descricao',
        'valor_mensal',
        'mes_referencia',
    ];

    protected $casts = [
        'valor_mensal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
