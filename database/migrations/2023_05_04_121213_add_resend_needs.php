<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResendNeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_activations', function (Blueprint $table) {
            $table->integer('max_resend')->default(3);
            $table->integer('resend_counter')->default(0);
            $table->timestamp('resend_avail_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_activations', function (Blueprint $table) {
            //
        });
    }
}
