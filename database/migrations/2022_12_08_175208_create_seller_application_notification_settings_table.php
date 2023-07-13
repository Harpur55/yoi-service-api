<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerApplicationNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_application_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->boolean('enable_all')->default(false);
            $table->boolean('new_order')->default(false);
            $table->boolean('in_progress_order')->default(false);
            $table->boolean('reject_order')->default(false);
            $table->boolean('finish_order')->default(false);
            $table->boolean('success_withdraw')->default(false);
            $table->boolean('fail_withdraw')->default(false);
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
        Schema::dropIfExists('seller_application_notification_settings');
    }
}
