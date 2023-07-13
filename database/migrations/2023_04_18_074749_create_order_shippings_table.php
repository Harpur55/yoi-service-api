<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->string('status')->default('waiting');
            $table->string('etd');
            $table->string('service_code');
            $table->string('service_name');
            $table->string('courier_code');
            $table->string('courier_name');
            $table->bigInteger('price');
            $table->mediumText('order_shipping_code');
            $table->bigInteger('origin_code');
            $table->bigInteger('destination_code');
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
        Schema::dropIfExists('order_shippings');
    }
}
