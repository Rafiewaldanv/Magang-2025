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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // foreign key to users table
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade'); // foreign key to users table
            $table->foreignId('packet_id')->constrained('packets')->onDelete('cascade'); // foreign key to users table
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
