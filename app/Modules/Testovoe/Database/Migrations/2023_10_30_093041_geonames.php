<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geonames', function (Blueprint $table) {
            $table->id();
            $table->integer('geoname_id');
            $table->string('locale_code');
            $table->string('continent_code');
            $table->string('continent_name');
            $table->string('country_iso_code');
            $table->string('country_name');
            $table->boolean('is_in_european_union');
            $table->timestamps();
        });

        Schema::table('geonames', function (Blueprint $table) {
            $table->index('geoname_id');
            $table->index('country_iso_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('geonames');
    }
};
