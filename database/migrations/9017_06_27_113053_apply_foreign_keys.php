<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplyForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('releases', function ($table)
        {
            $table->foreign('artist_id')->references('id')->on('artists');//TODO ->onDelete('cascade');
        });

        Schema::table('tracks', function ($table)
        {

            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');

        });

        Schema::table('artists', function ($table)
        {

            $table->foreign('user_id')->references('id')->on('users');//TODO ->onDelete('cascade');;

        });


        Schema::table('users', function ($table)
        {

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->foreign('listener_id')->references('id')->on('listeners')->onDelete('cascade');


        });

        Schema::table('likes', function ($table)
        {

            $table->foreign('track_id')->references('id')->on('tracks');//TODO ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');//TODO ->onDelete('cascade');


        });


        Schema::table('loves', function ($table)
        {

            $table->foreign('track_id')->references('id')->on('tracks');//TODO ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');//TODO ->onDelete('cascade');


        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('releases', function ($table)
        {
            $table->dropForeign('releases_artist_id_foreign');

        });

        Schema::table('tracks', function ($table)
        {
            $table->dropForeign('tracks_release_id_foreign');

        });

        Schema::table('artists', function ($table)
        {
            $table->dropForeign('artists_user_id_foreign');

        });

        Schema::table('users', function ($table)
        {

            $table->dropForeign('users_artist_id_foreign');
            $table->dropForeign('users_listener_id_foreign');


        });


        Schema::table('likes', function ($table)
        {

            $table->dropForeign('likes_track_id_foreign');
            $table->dropForeign('likes_user_id_foreign');


        });


        Schema::table('loves', function ($table)
        {

            $table->dropForeign('loves_track_id_foreign');
            $table->dropForeign('loves_user_id_foreign');


        });
    }
}
