<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subsls', function (Blueprint $table) {
            $table->string('idsubsls')->primary();
            $table->string('idsls')->index();
            $table->string('kdsubsls');
            $table->timestamps();

            $table->foreign('idsls')->references('idsls')->on('sls')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subsls');
    }
};
