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

            // 購入者
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // 購入された商品（1商品につき1注文）
            $table->foreignId('item_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');

            // 支払い方法
            $table->string('payment_method');

            // Stripe決済ID
            $table->string('stripe_id')->nullable();

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
