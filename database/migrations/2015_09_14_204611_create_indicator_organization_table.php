<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for associating organizations to indicators (Many-to-Many)
        Schema::create('indicator_organization', function (Blueprint $table) {
            $table->integer('indicator_id')->unsigned();
            $table->integer('organization_id')->unsigned();
            $table->float('coefficient');
            $table->timestamps();

            $table->foreign('indicator_id')->references('id')->on('indicators')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['indicator_id', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('indicator_organization');
    }
}
