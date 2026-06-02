<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('idsubsls')->index();
            $table->unsignedBigInteger('pcl_id')->index();
            $table->unsignedBigInteger('pml_id')->index();
            $table->integer('target_usaha')->default(0);
            $table->timestamps();

            $table->foreign('idsubsls')->references('idsubsls')->on('subsls')->onDelete('cascade');
            $table->foreign('pcl_id')->references('id')->on('pcls')->onDelete('cascade');
            $table->foreign('pml_id')->references('id')->on('pmls')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
