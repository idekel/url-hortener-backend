<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShortUrlLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_url_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('short_url_id');
            $table->string('ip');
            $table->string('cookie')->nullable(true);

            $table->index('short_url_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('short_url_log');
    }
}
