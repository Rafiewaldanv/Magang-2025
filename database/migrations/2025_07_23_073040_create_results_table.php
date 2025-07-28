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
         Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // sekarang boleh kosong

            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('packet_id')->nullable()->constrained('packets')->onDelete('cascade');
            $table->text('json'); 
            $table->integer('company_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
