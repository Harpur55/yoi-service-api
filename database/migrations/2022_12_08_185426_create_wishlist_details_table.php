<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlist_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->mediumText('store_name');
            $table->bigInteger('amount');
            $table->float('price');
            $table->float('discount');
            $table->string('selected_size');
            $table->string('selected_color');
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
        Schema::dropIfExists('wishlist_details');
    }
}
