<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlurtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('blurts'))
        {
            Schema::create('blurts', function (Blueprint $table)
                                     {
                                        $table->increments('id');
                                        $table->unsignedInteger('user_id');
                                        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                                        $table->string('input_string');
                                        $table->timestamps();
                                     }
                          );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blurts');
    }
}
