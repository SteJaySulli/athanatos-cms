<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('athanatos_entities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athanatos_article_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('athanatos_entities')->onDelete('cascade');
            $table->string('type');
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('athanatos_entities');
    }
};
