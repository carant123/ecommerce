<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@home');

// no se crea como un ruta de resources para que se mas facil de recordar

Route::get('/carrito', 'ShoppingCartsController@index');

Route::post('/carrito', 'ShoppingCartsController@checkout');

Route::get('/payments/store', 'PaymentsController@store');


Auth::routes();

Route::resource('products','ProductsController');

//creamos un ruta recurso con el nombre de la tabla, el controlador y el metodo only que solo utilizara dos metodos
Route::resource('in_shopping_carts','InShoppingCartsController',[
	'only' => ['store', 'destroy']
]);


Route::resource('compras','ShoppingCartsController',[
	'only' => ['show']
]);

// creamos una nueva routa para las ordenes

Route::resource('orders','OrdersController',[
	'only' => ['index','update']
]);

Route::get('/home', 'HomeController@index');


Route::get('products/images/{filename}',function($filename){

	$path = storage_path("app/images/$filename");

	if(!\File::exists($path)) abort(404);

	$file = \File::get($path);

	$type = \File::mimeType($path);

	$response = Response::make($file,200);

	$response->header("Content-Type",$type);

	return $response;
});