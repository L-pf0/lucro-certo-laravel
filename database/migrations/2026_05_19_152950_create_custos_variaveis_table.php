<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custos_variaveis', function (Blueprint $table) {
            $table->id();

            // 🔗 dono do custo
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔗 opcional: custo ligado a uma receita específica
            $table->foreignId('receita_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // 📌 descrição do custo
            $table->string('descricao');

            // 💰 valor do custo variável
            $table->decimal('valor', 10, 2);

            // 🧠 tipo do custo
            $table->enum('tipo', ['produto', 'geral']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custos_variaveis');
    }
};
