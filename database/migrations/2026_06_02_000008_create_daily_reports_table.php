<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->unsignedBigInteger('assignment_id')->index();
            $table->integer('usaha_today')->default(0);
            $table->integer('ruta_today')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate entry for a single assignment on the same date
            $table->unique(['report_date', 'assignment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
