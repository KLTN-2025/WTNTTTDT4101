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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable(); // Nội dung câu hỏi (hỗ trợ MathJax)
            $table->enum('type', ['single', 'multi', 'boolean', 'text', 'order', 'match', 'essay'])->default('single');
            $table->foreignId('difficulty_id')->nullable()->constrained()->nullOnDelete();
            $table->json('options')->nullable(); // Đáp án/options tùy theo type
            $table->json('correct_answer')->nullable(); // Đáp án đúng
            $table->text('explanation')->nullable(); // Giải thích
            $table->string('image_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_flagged')->default(false); // Đánh dấu lỗi
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

