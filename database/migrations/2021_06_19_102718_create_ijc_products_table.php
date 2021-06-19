<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIjcProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ijc_products', function (Blueprint $table) {
            $table->id();
            $table->string('itemid');
            $table->string('skuid');
            $table->string('sellersku');
            $table->longText('name');
            $table->string('model');
            $table->string('status');
            $table->string('url');
            $table->string('brand');
            $table->integer('qty');
            $table->integer('qty_local')->default(0);
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
        Schema::dropIfExists('ijc_products');
    }
}
