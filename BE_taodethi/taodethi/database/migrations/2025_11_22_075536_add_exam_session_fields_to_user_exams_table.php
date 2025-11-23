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
        Schema::table('user_exams', function (Blueprint $table) {
            $table->integer('duration_minutes')->nullable()->after('status');
            $table->dateTime('started_at')->nullable()->after('submitted_at');
            $table->dateTime('completed_at')->nullable()->after('started_at');
            $table->decimal('score', 5, 2)->nullable()->after('completed_at');
            $table->decimal('max_score', 5, 2)->nullable()->after('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_exams', function (Blueprint $table) {
            $table->dropColumn([
                'duration_minutes',
                'started_at',
                'completed_at',
                'score',
                'max_score',
            ]);
        });
    }
};
