<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('variant_id')->nullable();
            $table->string('title')->nullable();
            $table->string('shopify_product_id')->nullable();
            $table->string('country_code')->nullable();
            $table->string('stock')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('price')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
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
        Schema::dropIfExists('variants');
    }
}
