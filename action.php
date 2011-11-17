<?php
 
/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/cuatrob.php');
require_once(dirname(__FILE__).'/class_cuatrob.php');

$action = Tools::getValue('action');
$id_registro = Tools::getValue('id_registro');
$id_cart = Tools::getValue('id_cart');
$import = Tools::getValue('import');
	
switch ($action) {
	case 'validar':
		$cuatrob = new cuatrob();
		$cuatrob->validateOrder($id_cart, _PS_OS_PAYMENT_, $import, $cuatrob->displayName, NULL);
		class_cuatrob::removeRegistro_carritoCART($id_cart);

	break;
	case 'eliminar':
		class_cuatrob::removeRegistro_carritoREGISTRO($id_registro);

}

$back = Tools::getValue('back');
Tools::redirectAdmin($back);

?>