<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('file', 255);
            $table->string('path', 255);
            $table->timestamps();
            $table->integer('slide_show')->nullable()->comment('Status untuk media slideshow (1 = true, 0/null = false)');
            $table->integer('hits')->default(0)->comment('Memunculkan berapa kali file di download');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
}; 