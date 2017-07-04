<?php

namespace App;

class PayPal{
	private $_apiContext;
	private $shopping_cart;
	private $_ClientId = 'AU50AjboDWQ7bWHeQPxVPIx6_HIkYyEdGYuw2WRgN-de1UsPXcBMszeO8-LrULos-KruzsU09Ad82ZIo';
	private $_ClientSecret = 'EM4FFmZodRv4Eid1q8g9jrQAaKSzpc4FVwoaSlzcoUK8QbAsdR1pg3qYjQm227FZLRfv4jnGHQh-OAvz';

	public function __construct($shopping_cart){

		// utilizamos el alias que creamos del prvodier

		$this->_apiContext = \PaypalPayment::ApiContext($this->_ClientId
			, $this->_ClientSecret);

		// utilizamos utilizamos el config

		$config = config("paypal_payment");

		$flatConfig = array_dot($config);

		// utilizamos la configuracion flat porque el metodo asi lo espera

		$this->_apiContext->setConfig($flatConfig);

		// utilizamos el carrito de compras

		$this->shopping_cart = $shopping_cart;

	}


	public function generate(){
		$payment = \PaypalPayment::payment()->setIntent("sale")
						->setPayer($this->payer())
						->setTransactions([$this->transaction()])
						->setRedirectUrls($this->redirectURLs());

		try{
			$payment->create($this->_apiContext);
		}catch(\Exception $ex){
			dd($ex);
			exit(1);
		}

		return $payment;

	}

	public function payer(){
		// Return payment info
		return \PaypalPayment::payer()->setPaymentMethod("paypal");
	}

	public function redirectURLs(){
		//Returns transaccion info
		$baseURL = url('/');
		return \PaypalPayment::redirectUrls()
						->setReturnUrl("$baseURL/payments/store")
						->setCancelUrl("$baseURL/carrito");
	}

	public function transaction(){
		return \PaypalPayment::transaction()
						->setAmount($this->amount())
						->setItemList($this->items())
						->setDescription("Tu compra de ProductsFacilito")
						->setInvoiceNumber(uniqid());
	}

	public function items(){
		$items = [];

		$products = $this->shopping_cart->products()->get();

		foreach ($products as $product) {
			array_push($items, $product->paypalItem());
		}

		return \PaypalPayment::itemList()->setItems($items);

	}

	public function amount(){
		return \PaypalPayment::amount()->setCurrency("USD")
		->setTotal($this->shopping_cart->totalUSD());
	}


	public function execute($paymentId,$payerId){
		$payment = \PaypalPayment::getById($paymentId,$this->_apiContext);

		$execution = \PaypalPayment::PaymentExecution()->setPayerId($payerId);

		// quien lo va a pagar

		return $payment->execute($execution,$this->_apiContext);
	}


}

