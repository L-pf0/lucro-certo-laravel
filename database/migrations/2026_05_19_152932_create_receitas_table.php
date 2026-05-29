<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->id();

            // 🔗 dono da receita
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🍞 dados principais da receita
            $table->string('nome');

            $table->integer('rendimento_lote');
            // ex: 10 bolos, 50 pães etc.

            $table->decimal('custo_mao_obra_total', 10, 2);

            $table->decimal('tempo_preparo_horas', 5, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receitas');
    }
};
