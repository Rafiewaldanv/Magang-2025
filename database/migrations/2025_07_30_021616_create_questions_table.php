<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id'); // id table
            $table->unsignedInteger('packet_id'); // id packet
            $table->integer('number'); // nomor urut soal
            $table->text('description'); // isi soal
            $table->unsignedInteger('is_example')->default(0); // 0 = bukan contoh
            $table->timestamps(); // created_at dan updated_at
            $table->string('image')->nullable(); // created_at dan updated_at
            $table->foreignId('packet_id')->constrained('packets')->onDelete('cascade'); // foreign key to packets table


        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
