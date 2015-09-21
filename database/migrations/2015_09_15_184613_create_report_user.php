<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for associating reports to users (Many-to-Many)
        Schema::create('report_user', function (Blueprint $table) {
            $table->integer('report_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('view_self')->default(false);
            $table->boolean('view_all')->default(false);
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('reports')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['report_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('report_user');
    }
}
