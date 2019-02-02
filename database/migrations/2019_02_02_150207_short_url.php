<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShortUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->text('long_url');
            $table->string('hash', 7);
            $table->integer('visit_count')->default(0);
            $table->timestamps();

            $table->unique('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
