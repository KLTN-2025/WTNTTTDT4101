<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->json('question_order')->nullable();
            $table->json('answers')->nullable();
            $table->integer('remaining_seconds')->nullable();
            $table->boolean('camera_enabled')->default(false);
            $table->boolean('camera_locked')->default(false);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('paused_at')->nullable();
            $table->dateTime('resumed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'exam_id']);
            $table->index(['user_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_sessions');
    }
};
