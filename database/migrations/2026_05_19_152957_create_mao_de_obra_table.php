<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mao_de_obra', function (Blueprint $table) {
            $table->id();

            // 🔗 dono do registro
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔗 opcional: vinculada a uma receita específica
            $table->foreignId('receita_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // 👷 descrição do trabalho
            $table->string('descricao');

            // 💰 custo total de mão de obra
            $table->decimal('valor_total', 10, 2);

            // ⏱ tempo gasto na produção (horas)
            $table->decimal('tempo_horas', 5, 2);

            // 💰 custo por hora (opcional, mas MUITO útil)
            $table->decimal('valor_hora', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mao_de_obra');
    }
};
