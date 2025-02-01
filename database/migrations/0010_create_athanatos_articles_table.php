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
            $table->string('slug')->unique();

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
