<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('data_dukung', function (Blueprint $table) {
            // Ubah kolom file menjadi nullable
            $table->string('file')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('data_dukung', function (Blueprint $table) {
            // Kembalikan kolom file menjadi not null
            $table->string('file')->nullable(false)->change();
        });
    }
};