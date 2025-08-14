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
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade'); // foreign key to tests table
            $table->Integer('part'); // part tes ke sekian
            $table->string('name', 255); // nama test
            $table->text('description'); // deskripsi singkat tes
            $table->string('type', 255); // tipe soal: isian / pilihan ganda
            $table->Integer('amount'); // jumlah soal
            $table->boolean('status')->default(1); // status: 1 (active)
            $table->timestamps(); // created_at dan updated_at
            $table->unsignedInteger('test_id'); // waktu pengerjaan dalam detik, nullable jika tidak wajib

        });
    }

    public function down()
    {
        Schema::dropIfExists('packets');
    }
}

