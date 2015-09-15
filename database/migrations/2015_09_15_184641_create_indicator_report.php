<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for associating reports to indicators (Many-to-Many)
        Schema::create('indicator_report', function (Blueprint $table) {
            $table->integer('indicator_id')->unsigned();
            $table->integer('report_id')->unsigned();
            $table->boolean('show_value')->default(false);
            $table->boolean('show_points')->default(false);
            $table->timestamps();

            $table->foreign('indicator_id')->references('id')->on('indicators')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['indicator_id', 'report_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('indicator_report');
    }
}
