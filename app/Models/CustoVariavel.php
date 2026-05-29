<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustoVariavel extends Model
{
    use HasFactory;

    protected $table = 'custos_variaveis';

    protected $fillable = [
        'user_id',
        'receita_id',
        'descricao',
        'valor',
        'tipo',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }
}
