<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupIndicatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for associating groups to indicators (Many-to-Many)
        Schema::create('group_indicator', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
            $table->integer('indicator_id')->unsigned();

            $table->foreign('group_id')->references('id')->on('groups')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('indicator_id')->references('id')->on('indicators')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['group_id', 'indicator_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_indicator');
    }
}
