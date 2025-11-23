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
        Schema::create('study_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject'); // Toán, Văn, Anh, etc.
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->integer('total_lessons')->default(0);
            $table->integer('completed_lessons')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'subject']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_progress');
    }
};

