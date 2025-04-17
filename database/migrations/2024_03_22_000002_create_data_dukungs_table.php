<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_dukung', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_id')->constrained('opds')->onDelete('cascade');
            $table->foreignId('indikator_id')->constrained('indikators')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_dukung');
    }
}; 