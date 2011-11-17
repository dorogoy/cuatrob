<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/cuatrob.php');
include(dirname(__FILE__).'/class_cuatrob.php');

//Obtenemos el numero de pedido (numero unico de carrito en PrestasShop) y numero de la tienda.
$transRef	= $_GET["order"];
$store	 = $_GET["store"];

// Creamos objeto
$cuatrob = new cuatrob();

//Comprobamos si estamos ante un pedido de nuestra tienda.
if ($transRef!="" && $store==$cuatrob->datosoperacion()) {
			
	//Pasamos el valor del numero de carrito a la variable id del carrito para calcular el total de la compra.
	$cart = new Cart($transRef);
	$importe = number_format(Tools::convertPrice($cart->getOrderTotal(true, 3), $currency), 2, '.', '');
	

//Insertamos en la tabla registro de carritos un error en el regreso del cliente de la pasarela por si no regresara.
	class_cuatrob::insertRegistro_carrito($cart->id_customer, $transRef, $importe, "El cliente no regreso.");


	// Calculamos el total y lo multiplicamos por cien para eliminar la coma, porque así lo requiere 4B
	$total = $importe * 100;

	//Mostramos los datos para el cobro.
	echo "M978$total\n";
	echo "1\n";    
	echo "1\n";        
	echo "Compra en tienda online\n";        
	echo "1\n";            
	echo "$total\n";        
	echo "\n"; 

}