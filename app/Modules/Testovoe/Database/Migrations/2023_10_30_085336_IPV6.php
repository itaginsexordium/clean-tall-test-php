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
        Schema::create('ip_data_v6', function (Blueprint $table) {
            $table->id();
            $table->string('network');
            $table->integer('geoname_id');
            $table->integer('registered_country_geoname_id')->nullable();
            $table->integer('represented_country_geoname_id')->nullable();
            $table->boolean('is_anonymous_proxy');
            $table->boolean('is_satellite_provider');
            $table->timestamps();

        });

        // Вычисляемое поле для network
        DB::statement('ALTER TABLE ip_data_v6 ADD network_as_number BIGINT UNSIGNED GENERATED ALWAYS AS (INET6_ATON(network)) STORED');
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
};
