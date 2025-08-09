<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user yang mengerjakan
            $table->foreignId('test_id')->constrained()->onDelete('cascade'); // tes mana
            $table->integer('score')->nullable(); // nilai akhir
            $table->integer('duration')->nullable(); // durasi pengerjaan dalam menit
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_histories');
    }
};
