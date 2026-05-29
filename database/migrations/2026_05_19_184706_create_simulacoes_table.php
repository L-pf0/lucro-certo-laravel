<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulacoes', function (Blueprint $table) {
            $table->id();

            // 🔗 insumo afetado
            $table->foreignId('insumo_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔗 receita afetada (opcional)
            $table->foreignId('receita_afetada_id')
                ->nullable()
                ->constrained('receitas')
                ->nullOnDelete();

            // 💰 valores comparativos
            $table->decimal('preco_antigo', 10, 2);
            $table->decimal('preco_simulado', 10, 2);

            // 📊 impactos
            $table->decimal('impacto_cmv', 10, 4);
            $table->decimal('impacto_preco_venda', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulacoes');
    }
};