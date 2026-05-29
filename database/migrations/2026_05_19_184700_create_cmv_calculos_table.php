<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cmv_calculos', function (Blueprint $table) {
            $table->id();

            // 🔗 receita base do cálculo
            $table->foreignId('receita_id')
                ->constrained()
                ->cascadeOnDelete();

            // 📊 custos principais
            $table->decimal('custo_insumos_total', 10, 4);
            $table->decimal('custo_mao_obra', 10, 4);
            $table->decimal('custo_fixo_rateado', 10, 4);
            $table->decimal('custo_variavel_rateado', 10, 4);

            // 💰 resultado final
            $table->decimal('cmv_unitario', 10, 4);
            $table->decimal('margem_contribuicao', 10, 4);

            $table->decimal('percentual_lucro', 5, 2);
            $table->decimal('preco_sugerido', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cmv_calculos');
    }
};