<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCourierShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_courier_shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->string('receiver_name');
            $table->mediumInteger('receiver_phone');
            $table->string('city');
            $table->string('district');
            $table->mediumText('full_address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
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
        Schema::dropIfExists('store_courier_shipping_addresses');
    }
}
