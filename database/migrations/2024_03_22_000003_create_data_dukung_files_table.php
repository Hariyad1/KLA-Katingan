<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_dukung_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_dukung_id')->constrained('data_dukung')->onDelete('cascade');
            $table->string('file');
            $table->string('original_name');
            $table->string('mime_type');
            $table->integer('size');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_dukung_files');
    }
}; 