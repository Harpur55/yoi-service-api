<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_email_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->boolean('enable_all')->default(false);
            $table->boolean('order_status')->default(false);
            $table->boolean('seller_information')->default(false);
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
        Schema::dropIfExists('customer_email_notification_settings');
    }
}
