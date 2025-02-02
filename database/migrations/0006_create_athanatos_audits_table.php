<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('athanatos_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->morphs('auditable');
            $table->string('field')->index();
            $table->string('event')->default('change')->index();
            $table->json('old')->nullable();
            $table->json('new')->nullable();
            $table->unsignedBigInteger('version')->default(1);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('athanatos_audits');
    }
};
