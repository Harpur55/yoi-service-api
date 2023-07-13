<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreTermAndConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_term_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->boolean('show_term_and_condition')->default(false);
            $table->mediumText('term_description');
            $table->boolean('show_time_operational')->default(false);
            $table->mediumText('day_operation');
            $table->timestamp('opening_time_operational')->nullable();
            $table->mediumText('opening_time_operational_description');
            $table->timestamp('closing_time_operational')->nullable();
            $table->mediumText('closing_time_operational_description');
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
        Schema::dropIfExists('store_term_and_conditions');
    }
}
