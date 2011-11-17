<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/../../header.php');

// Obtenemos algunos de los datos que nos pasa 4B
$result	 = $_GET["result"];

	// Si el pago ha sido correcto, pasamos el pedido como pagado
	if ($result == 0) {

		$smarty->assign(array('this_path' => __PS_BASE_URI__));
		$smarty->display(_PS_MODULE_DIR_.'cuatrob/pago_correcto.tpl');

	}

	// Si se ha denegado lo mostramos, pasamos el pedido con error
	if ($result > 0) {

		$smarty->assign(array('this_path' => __PS_BASE_URI__));
		$smarty->display(_PS_MODULE_DIR_.'cuatrob/pago_error.tpl');

	}

	include(dirname(__FILE__).'/../../footer.php');

?>
