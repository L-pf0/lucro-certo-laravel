<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('mao_de_obra', function (Blueprint $table) {
            $table->dropForeign(['receita_id']);
            $table->dropColumn('receita_id');
        });
    }

    public function down()
    {
        Schema::table('mao_de_obra', function (Blueprint $table) {
            $table->foreignId('receita_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
