<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('artist_name');
            $table->string('currency');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dob');
            $table->string('line1');
            $table->string('city');
            $table->string('postal_code');
            $table->string('stripe_id')->nullable();
            $table->string('state')->nullable();
            $table->string('personal_id')->nullable();
            $table->string('stripe_token')->nullable();
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
        Schema::drop('artists');
    }
}
