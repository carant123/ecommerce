<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{

	// al utilizar un create tenemos que tener un avariable fillable que indica cuales son los valores que pasaran

    protected $fillable = ['status'];

    // toma todos los registro de nuestro carrito

    public function approve(){
    	$this->updateCustomIDAndStatus();
    }

    //Id genera el id para identificar al carrito

    public function generateCustomID(){
    	return md5("$this->id $this->updated_at");
    }

    //actualiza el status customid generan y graban

    public function updateCustomIDAndStatus(){
    	$this->status = "approved";
    	$this->customid = $this->generateCustomID();
    	$this->save();
    }

	// toma todos los registro de nuestro carrito

	public function inShoppingCarts(){
		return $this->hasMany('App\InShoppingCart');
	}

	// toma todos los productos del carrito dando el modelo y la tabla donde esta relacionados el carrito y los productos

	public function products(){
		return $this->belongsToMany('App\Product','in_shopping_carts');
	}

    //Orde tiene una lreacion pero un carrito de compras solo debe ser pagado una vez
    

    public function order(){
        return $this->hasOne("App\Order")->first();
    }

	// nos da la cantidad de productos

    public function productsSize(){
    	return $this->products()->count();
    }

    // llaman al metodo products y suma todos de la columna pricing
    // hay que tratar de hacerlo via sql

    public function total(){
    	return $this->products()->sum("pricing");
    }

    // suma total en dolares

    public function totalUSD(){
    	return $this->products()->sum("pricing") / 100;
    }

    //verifica si la session ha sido crear para crear un carrito, si existe busca el carrito por session y si no, lo crea sin session

    public static function findOrCreateBySessionID($shopping_cart_id){
 		if($shopping_cart_id)

 			return ShoppingCart::findBySession($shopping_cart_id);

 		else
 
			return ShoppingCart::createWithoutSession();

    }

	public static function findBySession($shopping_cart_id){

		return ShoppingCart::find($shopping_cart_id);

	}

	public static function createWithoutSession(){

		return ShoppingCart::create([
				"status" => "incompleted"
			]);
	}

	

}
