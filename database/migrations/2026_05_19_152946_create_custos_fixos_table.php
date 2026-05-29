<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custos_fixos', function (Blueprint $table) {
            $table->id();

            // 🔗 dono do custo fixo
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 📌 descrição do custo
            $table->string('descricao');

            // 💰 valor mensal fixo
            $table->decimal('valor_mensal', 10, 2);

            // 📅 período do custo (YYYY-MM)
            $table->string('mes_referencia', 7);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custos_fixos');
    }
};
