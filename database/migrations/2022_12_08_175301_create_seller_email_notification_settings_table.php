<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_email_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->boolean('enable_all')->default(false);
            $table->boolean('order')->default(false);
            $table->boolean('withdraw')->default(false);
            $table->boolean('promotion')->default(false);
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
        Schema::dropIfExists('seller_email_notification_settings');
    }
}
