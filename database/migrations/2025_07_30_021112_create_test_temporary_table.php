<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTemporaryTable extends Migration
{
    public function up()
    {
        Schema::create('test_temporary', function (Blueprint $table) {
            $table->bigIncrements('id'); // id_table
            $table->unsignedBigInteger('id_user')->nullable(); // user id
            $table->unsignedBigInteger('test_id')->nullable(); // id test
            $table->unsignedBigInteger('packet_id')->nullable(); // packet id
            $table->integer('part')->nullable(); // part ke sekian
            $table->text('json')->nullable(); // menyimpan jawaban yang dipilih
            $table->text('result_temp')->nullable(); // menyimpan hasil scoring
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_temporary');
    }
}
