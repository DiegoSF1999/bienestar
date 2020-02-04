<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestrictionsRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restrictions_relation', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('app_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('app_id')->references('id')->on('apps');
            $table->integer('maximun_time');
            $table->integer('start_hour');
            $table->integer('finish_hour');
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
        Schema::dropIfExists('restrictions_relation');
    }
}
