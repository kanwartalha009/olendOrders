<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->string('shopify_item_id')->nullable();
            $table->string('shopify_order_id')->nullable();
            $table->string('name')->nullable();
            $table->string('product_id')->nullable();
            $table->string('title')->nullable();
            $table->string('variant_id')->nullable();
            $table->string('variant_title')->nullable();
            $table->string('property')->nullable();
            $table->string('property_locale')->nullable();
            $table->bigInteger('order_id')->unsigned()->nullable();
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
        Schema::dropIfExists('line_items');
    }
}
