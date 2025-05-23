<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('page', 255);
            $table->string('url', 255);
            $table->string('image', 300)->nullable();
            $table->longText('content')->nullable();
            $table->string('type', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting');
    }
}; 