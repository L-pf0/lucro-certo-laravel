<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaoDeObra;
use App\Models\User;

class MaoDeObraSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // ou pegue o primeiro gestor
        MaoDeObra::create([
            'user_id' => $user->id,
            'descricao' => 'Confeiteiro',
            'valor_hora' => 25.00,
            'tempo_horas' => 0, // será preenchido na receita
            'valor_total' => 0,
        ]);
        MaoDeObra::create([
            'user_id' => $user->id,
            'descricao' => 'Auxiliar',
            'valor_hora' => 15.00,
            'tempo_horas' => 0,
            'valor_total' => 0,
        ]);
    }
}
