<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('athanatos_articles', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('uri')->unique();
            $table->boolean('published')->default(false);
            $table->boolean('routable')->default(true);
            $table->unsignedBigInteger('version')->default(1);

            $table->json('title');
            $table->json('description');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('athanatos_articles');
    }
};
