<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\ShoppingCart;
use App\Paypal;
use App\Order;

class PaymentsController extends Controller
{
    //
    public function __construct(){
        $this->middleware("shoppingcart");
    }

        public function store(Request $request){
            
/*    		$shopping_cart_id = \Session::get('shopping_cart_id');
        	$shopping_cart = ShoppingCart::findOrCreateBySessionID($shopping_cart_id);*/

            $shopping_cart = $request->shopping_cart;

        	$paypal = new Paypal($shopping_cart);

        	$response = $paypal->execute($request->paymentId,$request->PayerID);

        	if($response->state == "approved"){
                //esto nos permite desacernos del Id de la session del carrito
                \Session::remove("shopping_cart_id");
        		$order = Order::createFromPayPalResponse($response,$shopping_cart);
        		$shopping_cart->approve();
        	}

        	//dd($order);

  			return view("shopping_carts.completed",["shopping_cart" => $shopping_cart, "order" => $order]);

        }

}
