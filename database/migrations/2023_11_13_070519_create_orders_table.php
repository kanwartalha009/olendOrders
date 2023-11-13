<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('order_name')->nullable();
            $table->string('browser_ip')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('currency')->nullable();
            $table->string('current_subtotal_price')->nullable();
            $table->string('current_total_discounts')->nullable();
            $table->string('current_total_tax')->nullable();
            $table->string('current_total_price')->nullable();
            $table->string('financial_status')->nullable();
            $table->string('fulfillment_status')->nullable();
            $table->string('note')->nullable();
            $table->longText('order_json')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
