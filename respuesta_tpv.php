<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/cuatrob.php');
include(dirname(__FILE__).'/class_cuatrob.php');

if (cuatrob::validaIpPasarela() == false) {
	/**
	* Ejemplo de llamada a la función mail() para chivarse de la IP que ha intentado acceder a este fichero
	* Modificar para@email.com y desde@email.com
	**/
	// 	mail('para@email.com', 'Error de IP en modulo 4B', $_SERVER['REMOTE_ADDR'], null, '-fdesde@email.com');
	die('Intento de acceso no autorizado. Su IP ha sido registrada.');
}

// Obtenemos algunos de los datos que nos pasa 4B
$result	 = $_GET["result"];
$transRef	= $_GET["pszPurchorderNum"];
$store	 = $_GET["store"];

$cart = new Cart($transRef);
$customer = new Customer((int)$cart->id_customer);

$importe = number_format(Tools::convertPrice($cart->getOrderTotal(true, 3), $currency), 2, '.', '');
// Creamos objeto
$cuatrob = new cuatrob();

if ($transRef!="" && $store==$cuatrob->datosoperacion() && $result!="") {

	// Si el pago ha sido correcto, pasamos el pedido como pagado
	if ($result == 0) {
		$cuatrob->validateOrder($transRef, _PS_OS_PAYMENT_, $importe, $cuatrob->displayName, NULL, NULL, $currency, false, $customer->secure_key);
		//Todo esta correcto elimitamos el carrito de la tabla de regristros de la pasarela
		class_cuatrob::removeRegistro_carritoCART($transRef);
	}

	// Si se ha denegado lo mostramos, pasamos el pedido con error
	if ($result > 0) {
		//La pasarela no admitio el pago, actualizamos el error del carrito en el registro de la pasarela
		class_cuatrob::updateRegistro_carrito($transRef, "Pago no admitido");
	}

}

?>
