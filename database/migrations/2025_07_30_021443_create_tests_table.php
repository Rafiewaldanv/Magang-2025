<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id'); // id test
            $table->string('name', 255); // nama test
            $table->string('code', 255)->unique(); // code test uniq untuk get data tes
            $table->integer('num_order'); // urutan, default biasanya 0
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
