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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subject'); // Toán, Văn, Anh, etc.
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date');
            $table->enum('status', ['upcoming', 'in_progress', 'submitted', 'graded'])->default('upcoming');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};

