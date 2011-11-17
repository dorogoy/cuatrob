<?php

class class_cuatrob extends ObjectModel
{
	/** @var integer Wishlist ID */
	public		$id_registro;

	/** @var integer Customer ID */
	public 		$id_customer;

	/** @var integer Token */
	public 		$id_cart;

	/** @var string Object creation date */
	public 		$date_add;

	/** @var string Object creation date */
	public 		$tipo_error;

	static public function selectRegistro_carrito()
	{
		return (Db::getInstance()->ExecuteS('
		SELECT w.`id_registro`, w.`id_customer`, w.`id_cart`, w.`import`, w.`date_add`, w.`tipo_error`, c.`lastname` AS customer_lastname, c.`firstname` AS customer_firstname
		  FROM `'._DB_PREFIX_.'registro_carrito` w
		LEFT JOIN `'._DB_PREFIX_.'customer` c ON w.`id_customer` = c.`id_customer`
		  ORDER BY w.`date_add` DESC'));
	}

// Introduce cesta en el registro @boolean
	static public function insertRegistro_carrito($id_customer, $id_cart, $import, $tipo_error)
	{
		if (!Validate::isUnsignedId($id_customer) OR
			!Validate::isUnsignedId($id_cart))
			die (Tools::displayError());

			return (Db::getInstance()->Execute('
			INSERT INTO `'._DB_PREFIX_.'registro_carrito` (`id_customer`, `id_cart`, `import`, `date_add`, `tipo_error`) VALUES(
			'.intval($id_customer).',
			'.intval($id_cart).',
			'.floatval($import).',
			\''.pSQL(date('Y-m-d H:i:s')).'\',
			\''.pSQL($tipo_error).'\')'));
	}

// Elimina cesta del registro @boolean	
	static public function removeRegistro_carritoCART($id_cart)
	{
		if (!Validate::isUnsignedId($id_cart))
			die (Tools::displayError());

		$result = Db::getInstance()->getRow('
		SELECT `id_cart`
		  FROM `'._DB_PREFIX_.'registro_carrito`
		WHERE `id_cart` = '.intval($id_cart));

		if (empty($result) === true OR
			$result === false OR
			!sizeof($result) OR
			$result['id_cart'] != $id_cart)
			return (false);
		$result = Db::getInstance()->Execute('
		DELETE FROM `'._DB_PREFIX_.'registro_carrito`
		WHERE `id_cart` = '.intval($id_cart));
	}

	// Elimina cesta del registro @boolean	
	static public function removeRegistro_carritoREGISTRO($id_registro)
	{
		if (!Validate::isUnsignedId($id_registro))
			die (Tools::displayError());

		$result = Db::getInstance()->getRow('
		SELECT `id_registro`
		  FROM `'._DB_PREFIX_.'registro_carrito`
		WHERE `id_registro` = '.intval($id_registro));

		if (empty($result) === true OR
			$result === false OR
			!sizeof($result) OR
			$result['id_registro'] != $id_registro)
			return (false);
		$result = Db::getInstance()->Execute('
		DELETE FROM `'._DB_PREFIX_.'registro_carrito`
		WHERE `id_registro` = '.intval($id_registro));
	}

		// Elimina cesta del registro @boolean	
	static public function crea_pedidoREGISTRO($id_registro, $id_cart, $importe)
	{
		if (!Validate::isUnsignedId($id_registro))
			die (Tools::displayError());

		$result = Db::getInstance()->getRow('
		SELECT `id_registro`
		  FROM `'._DB_PREFIX_.'registro_carrito`
		WHERE `id_registro` = '.intval($id_registro));

		if (empty($result) === true OR
			$result === false OR
			!sizeof($result) OR
			$result['id_registro'] != $id_registro)
			return (false);

		$result = Db::getInstance()->Execute('
		DELETE FROM `'._DB_PREFIX_.'registro_carrito`
		WHERE `id_registro` = '.intval($id_registro));
	}

// Actualiza cesta del registro @boolean	
	static public function updateRegistro_carrito($id_cart, $tipo_error)
	{
		if (!Validate::isUnsignedId($id_cart))
			die (Tools::displayError());

		return (Db::getInstance()->Execute('
		UPDATE `'._DB_PREFIX_.'registro_carrito` SET
		`tipo_error` = \''.pSQL($tipo_error).'\'
		WHERE `id_cart` = '.intval($id_cart)));
	}
}

?>