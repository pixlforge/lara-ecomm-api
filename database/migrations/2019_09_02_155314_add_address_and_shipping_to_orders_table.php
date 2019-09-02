<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressAndShippingToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('address_id')->nullable()->index()->after('user_id');
            $table->unsignedBigInteger('shipping_method_id')->nullable()->index()->after('user_id');

            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_address_id_foreign');
            $table->dropForeign('orders_shipping_method_id_foreign');
            $table->dropColumn(['address_id', 'shipping_method_id']);
        });
    }
}
