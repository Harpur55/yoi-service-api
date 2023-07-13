<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->string('name');
            $table->float('price');
            $table->float('discount');
            $table->mediumText('keyword');
            $table->mediumText('description');
            $table->string('sku');
            $table->boolean('stock_status')->default(false);
            $table->mediumText('selected_service');
            $table->string('selected_size');
            $table->string('selected_color');
            $table->float('weight');
            $table->float('long');
            $table->float('wide');
            $table->float('tall');
            $table->boolean('visibility')->default(true);
            $table->mediumText('additional_note');
            $table->boolean('show_additional_note')->default(false);
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
        Schema::dropIfExists('product_details');
    }
}
