<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomIDColumnToShoppingCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //se agrega un unique que es un index para que query sean mas rapido, se recomienda se utilizan para buscar elemetnos solo cuanod se compre se genere
        Schema::table('shopping_carts',function($table){
            $table->string('customid')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
