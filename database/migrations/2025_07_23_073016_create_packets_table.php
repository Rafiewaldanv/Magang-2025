<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('packets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('test_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('part')->nullable();
        $table->timestamps();
        $table->string('description')->nullable();
$table->string('type')->default('pilihan_ganda');
$table->integer('amount')->default(0);
$table->boolean('status')->default(1);

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packets');
    }
};
