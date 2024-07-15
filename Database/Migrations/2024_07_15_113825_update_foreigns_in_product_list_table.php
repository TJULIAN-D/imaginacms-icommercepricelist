<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateForeignsInProductListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('icommercepricelist__product_lists', function (Blueprint $table) {
          $table->dropForeign('product_list_product_foreign');
          $table->dropForeign('product_price_list_foreign');

          $table->foreign('product_id','product_list_product_foreign')->references('id')->on('icommerce__products')->onDelete('cascade');
          $table->foreign('price_list_id','product_price_list_foreign')->references('id')->on('icommercepricelist__price_lists')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('icommercepricelist__product_lists', function (Blueprint $table) {
        $table->dropForeign('product_list_product_foreign');
        $table->dropForeign('product_price_list_foreign');
      });
    }
}
