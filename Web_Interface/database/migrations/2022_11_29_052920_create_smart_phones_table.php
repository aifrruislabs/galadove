<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userId')->nullable();
            $table->string('title')->nullable();
            $table->string('info')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('connectionStatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smart_phones');
    }
}
