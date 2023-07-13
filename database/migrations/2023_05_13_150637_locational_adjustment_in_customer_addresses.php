<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocationalAdjustmentInCustomerAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->bigInteger('prov_id');
            $table->mediumText('prov_name');
            $table->bigInteger('city_id');
            $table->mediumText('city_name');
            $table->bigInteger('district_id');
            $table->mediumText('district_name');
            $table->bigInteger('subdistrict_id');
            $table->mediumText('subdistrict_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            //
        });
    }
}
