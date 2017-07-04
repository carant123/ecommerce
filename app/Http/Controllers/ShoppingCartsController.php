<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\ShoppingCart;
use App\PayPal;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use App\Order;

class ShoppingCartsController extends Controller
{

    //llamamos a middileware del shopping

    public function __construct(){
        $this->middleware("shoppingcart");
    }

    //

    //el id representa al customid, cuando se quiere buscar por primary key se utiliza find pero cuando es otro elemento utiliza where

    public function show($id){
        $shopping_cart = ShoppingCart::where('customid',$id)->first();

        $order = $shopping_cart->order();

        return view("shopping_carts.completed",["shopping_cart" => $shopping_cart, "order" => $order]);
    }

    public function checkout(Request $request){

        $shopping_cart = $request->shopping_cart;

        $paypal = new PayPal($shopping_cart);

        $payment = $paypal->generate();

        return redirect($payment->getApprovalLink());

    }

	public function index(Request $request){

        // $shopping_cart_id = \Session::get('shopping_cart_id');
        // $shopping_cart = ShoppingCart::findOrCreateBySessionID($shopping_cart_id);

        $shopping_cart = $request->shopping_cart;
        $products = $shopping_cart->products()->get();
        $total = $shopping_cart->total();
        return view("shopping_carts.index", ["products" => $products, "total" => $total ]);

	}

}
