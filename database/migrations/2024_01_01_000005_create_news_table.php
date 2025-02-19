<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->integer('kategori_id')->default(0);
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->integer('counter')->default(0);
            $table->string('flag', 20)->default('kegiatan');

            $table->foreign('kategori_id')->references('id')->on('kategori');
            $table->index('created_by', 'news_created_by_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
}; 