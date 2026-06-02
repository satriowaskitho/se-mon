<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->string('iddesa')->primary();
            $table->string('idkec')->index();
            $table->string('kddesa');
            $table->string('nmdesa');
            $table->timestamps();

            $table->foreign('idkec')->references('idkec')->on('districts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
