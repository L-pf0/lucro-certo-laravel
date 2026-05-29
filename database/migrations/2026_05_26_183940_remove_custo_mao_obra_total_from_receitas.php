<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->dropColumn('custo_mao_obra_total');
        });
    }

    public function down()
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->decimal('custo_mao_obra_total', 10, 2)->default(0);
        });
    }
};