<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id'); // id
            $table->unsignedInteger('user_id'); // user id
            $table->unsignedInteger('company_id')->default(1); // company id (dummy isi 1)
            $table->unsignedInteger('test_id'); // id test
            $table->unsignedInteger('packet_id'); // id packet
            $table->text('result'); // hasil tes
            $table->timestamps(); // created_at & updated_at
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
            $table->foreign('packet_id')->references('id')->on('packets')->onDelete('cascade');


        });
    }

    public function down()
    {
        Schema::dropIfExists('results');
    }
}
