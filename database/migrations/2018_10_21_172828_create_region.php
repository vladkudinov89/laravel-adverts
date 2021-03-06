<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions' , function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug');
            $table->integer('parent_id')
                ->nullable()
                ->references('id')
                ->on('regions')
                ->onDelete('CASCADE');
            $table->unique(['parent_id' , 'slug']);
            $table->unique(['parent_id' , 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
