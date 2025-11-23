<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skill_matrix', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->integer('weight')->default(1); // Trọng số kỹ năng trong câu hỏi (1-10)
            $table->integer('level')->default(1); // Mức độ kỹ năng (1-5)
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['question_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_matrix');
    }
};

