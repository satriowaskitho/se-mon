<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->string('idkec')->primary();
            $table->string('kdkec');
            $table->string('nmkec');
            $table->string('idkab')->index();
            $table->string('kdkab');
            $table->string('nmkab');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
