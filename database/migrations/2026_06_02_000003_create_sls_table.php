<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sls', function (Blueprint $table) {
            $table->string('idsls')->primary();
            $table->string('iddesa')->index();
            $table->string('kdsls');
            $table->string('nmsls');
            $table->timestamps();

            $table->foreign('iddesa')->references('iddesa')->on('villages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sls');
    }
};
