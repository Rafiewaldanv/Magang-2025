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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('packet_id');
$table->foreign('packet_id')->references('id')->on('packets')->onDelete('cascade');

        $table->text('text');
        $table->string('option_a');
        $table->string('option_b');
        $table->string('option_c');
        $table->string('option_d');
        $table->enum('correct_answer', ['A', 'B', 'C', 'D']);
        $table->timestamps();
$table->integer('number'); // nomor urut soal
$table->text('description');
$table->boolean('is_example')->default(false);

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
