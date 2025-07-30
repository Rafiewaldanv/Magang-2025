<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacketsTable extends Migration
{
    public function up()
    {
        Schema::create('packets', function (Blueprint $table) {
            $table->increments('id'); // id_table
            $table->unsignedInteger('test_id'); // id test
            $table->unsignedInteger('part'); // part tes ke sekian
            $table->string('name', 255); // nama test
            $table->text('description'); // deskripsi singkat tes
            $table->string('type', 255); // tipe soal: isian / pilihan ganda
            $table->unsignedInteger('amount'); // jumlah soal
            $table->unsignedInteger('status'); // status: 1 (active)
            $table->timestamps(); // created_at dan updated_at
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('packets');
    }
}

