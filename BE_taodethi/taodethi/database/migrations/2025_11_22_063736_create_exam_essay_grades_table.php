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
    public function up(): void
    {
        Schema::create('exam_essay_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_answer_id')->constrained('exam_answers')->cascadeOnDelete();
            $table->foreignId('graded_by')->constrained('users')->cascadeOnDelete();
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->json('rubric_scores')->nullable();
            $table->text('feedback')->nullable();
            $table->text('ai_suggestion')->nullable();
            $table->enum('status', ['pending', 'graded', 'reviewed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_essay_grades');
    }
};
