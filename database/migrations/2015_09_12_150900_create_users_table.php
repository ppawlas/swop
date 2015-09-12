<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('login')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->boolean('active');
            $table->integer('organization_id')->unsigned();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')->on('organizations')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
