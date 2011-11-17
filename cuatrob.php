<?php

class cuatrob extends PaymentModule
{
	const INSTALL_SQL_FILE = 'install.sql';

	private	$_html = '';
	private $_postErrors = array();

	public function __construct(){
		
		$this->name = 'cuatrob';
		$this->tab = 'payments_gateways';
		$this->version = 1.1;

		// Array config con los datos de configuración
		$config = Configuration::getMultiple(array('CUATROB_CLAVE','CUATROB_PRUEBAS'));
		
		// Establecer propiedades según los datos de configuración
		if (isset($config['CUATROB_CLAVE']))
			$this->clave = $config['CUATROB_CLAVE'];
		if (isset($config['CUATROB_PRUEBAS']))
			$this->enpruebas = $config['CUATROB_PRUEBAS'];
		parent::__construct();
				
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Pasarela 4B');
		$this->description = $this->l('Aceptar pagos con la pasarela 4B');
		
		// Mostrar aviso en la página principal de módulos si faltan datos de configuración.
		if (!isset($this->clave))
		$this->warning = $this->l('Te faltan datos a configurar el m&oacute;dulo 4B.');
		if (!sizeof(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('No establecido ninguna moneda para el m&oacute;dulo 4B.');
				
	}

	public function install()
	{
		//Instala la tabla para almacenar las operaciones en la pasarela
		if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return (false);
		else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return (false);
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$sql = preg_split("/;\s*[\r\n]+/",$sql);
		foreach ($sql AS $k=>$query)
			Db::getInstance()->Execute(trim($query));

		// Valores por defecto al instalar el módulo
		if (!parent::install() OR !Configuration::updateValue('CUATROB_PRUEBAS', 1) OR !$this->registerHook('payment'))
			return false;
	}

		public function uninstall()
	{
	   // Valores a quitar si desinstalamos el módulo
		if (!Configuration::deleteByName('CUATROB_CLAVE')
				OR !Configuration::deleteByName('CUATROB_PRUEBAS')
				OR Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'registro_carrito')
				OR !parent::uninstall())
			return false;
	}

	private function _postValidation(){
	
	    // Si al enviar los datos del formulario de configuración hay campos vacios, mostrar errores.
		if (isset($_POST['btnSubmit'])){				
			if (empty($_POST['clave']))
				$this->_postErrors[] = $this->l('Se requiere la Clave de Comercio.');				
		}
	}

	private function _postProcess(){
	    // Actualizar la configuración en la BBDD
		if (isset($_POST['btnSubmit'])){
		Configuration::updateValue('CUATROB_CLAVE', $_POST['clave']);
		Configuration::updateValue('CUATROB_PRUEBAS', $_POST['enpruebas']);
		}
		
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('ok').'" /> '.$this->l('Configuraci&oacute;n actualizada').'</div>';
	}


	private function _displaycuatrob()
	{
	    // Aparición el la lista de módulos
		$this->_html .= '<img src="../modules/cuatrob/cuatrob.gif" style="float:left; margin-right:15px;"><b>'.$this->l('Este m&oacute;dulo te permite aceptar pagos con tarjeta.').'</b><br /><br />
		'.$this->l('Si el cliente elije este modo de pago, podr&aacute; pagar de forma autom&aacute;tica.').'<br /><br /><br />';
	}


	private function _displayForm(){
	  	  
	    // Mostar formulario
		$this->_html .=
		'<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Configuraci&oacute;n del TPV').'</legend>

			<label>'.$this->l('Clave de Comercio').'</label>
				<div class="margin-form">
					<input size="34" type="text" name="clave"  value="'.(Tools::getValue('clave', Configuration::get('CUATROB_CLAVE'))).'" />
					<p class="clear">'.$this->l('Introduzca la clave de comercio que le ha facilitado su banco').'</p>
				</div>

				<label>'.$this->l('Operar en modo pruebas').'</label>
				<div class="margin-form">
					<input type="radio" name="enpruebas" id="text_list_on" value="1" '.(Tools::getValue('enpruebas', Configuration::get('CUATROB_PRUEBAS')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="text_list_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="enpruebas" id="text_list_off" value="0" '.(!Tools::getValue('enpruebas', Configuration::get('CUATROB_PRUEBAS')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="text_list_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
					<p class="clear">'.$this->l('Elija si desea trabajar en modo de pruebas con el banco').'</p>
				</div>

				<div class="margin-form">
					<p class="clear">'.$this->l('Una vez actualice los cambios debe configurar los datos en su pasarlea de pago para el correcto funcionamiento').'</p>
					<p class="clear">'.$this->l('Introduzca los siguientes datos es su pasarela de pagos 4B').'</p>
					<p class="clear">'.$this->l('<b>URL que devuelve el desglose de la compra:</b> http://www."nombredeldominio"/"rutadelatienda"/modules/cuatrob/operacion.php').'</p>
					<p class="clear">'.$this->l('<b>URL que graba el resultado en la BD del comercio (TRANSACCIONES AUTORIZADAS):</b> http://www."nombredeldominio"/"rutadelatienda"/modules/cuatrob/respuesta_tpv.php').'</p>
					<p class="clear">'.$this->l('<b>URL que graba el resultado en la BD del comercio (TRANSACCIONES DENEGADAS):</b> http://www."nombredeldominio"/"rutadelatienda"/modules/cuatrob/respuesta_tpv.php').'</p>
					<p class="clear">'.$this->l('<b>URL de continuacion posterior a la pagina de recibo:</b> http://www."nombredeldominio"/"rutadelatienda"/modules/cuatrob/resultado.php').'</p>
					<p class="clear">'.$this->l('"rutadetienda" solo es necesaria en caso de que su tienda no este instalada en el directorio raiz de su servidor (/www o /public_html)').'</p>
				</div>
			</fieldset>

			<br>
		<input class="button" name="btnSubmit" value="'.$this->l('Guardar configuraci&oacute;n').'" type="submit" />
					
			
		</form>';
	}

		private function _displayFormView()
	{
		global $cookie;


		$this->_html .= '<br /><br /><br />
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend><img src="../img/admin/contact.gif" alt="" title="" />'.$this->l('Operaciones con errores').'</legend>';


		require_once(dirname(__FILE__).'/class_cuatrob.php');

		$carritos = class_cuatrob::selectRegistro_carrito();

		$this->_html .= '
		<table class="table">
			<thead>
				<tr>
					<th class="item" style="text-align:center;width:150px;">'.$this->l('Fecha').'</th>
					<th class="item" style="width:325px;">'.$this->l('Cliente').'</th>
					<th class="item" style="text-align:center;width:75px;">'.$this->l('Importe').'</th>
					<th class="item" style="text-align:center;width:300px;">'.$this->l('Tipo error').'</th>
					<th class="item" style="text-align:center;width:50px;">'.$this->l('Acciones').'</th>
				</tr>
			</thead>
			<tbody>';

			foreach ($carritos as $registro)
			{

				$this->_html .= '
					<tr>
					<td class="first_item" style="text-align:center;">'.$registro['date_add'].'</td>
					<td class="item" style="text-align:left;"><span>'.$registro['customer_firstname'].' '.$registro['customer_lastname'].'</span></td>
					<td class="item" style="text-align:center;">'.$registro['import'].'</td>
					<td class="item" style="text-align:left;">'.$registro['tipo_error'].'</td>
					<td class="center">';
								
								$onClick = 'document.location = "'.__PS_BASE_URI__.'modules/'.$this->name.'/action.php?action=validar&id_cart='.$registro['id_cart'].'&import='.$registro['import'].'&back='.urlencode($_SERVER['REQUEST_URI']).'" ';
								$this->_html .= '<img onClick=\''.$onClick.'\' src="../img/admin/add.gif" style="cursor:pointer" alt="'.$this->l('Crear Pedido').'" title="'.$this->l('Crear Pedido').'" />';
								
								$onClick = 'document.location = "'.__PS_BASE_URI__.'modules/'.$this->name.'/action.php?action=eliminar&id_registro='.$registro['id_registro'].'&back='.urlencode($_SERVER['REQUEST_URI']).'" ';
								$this->_html .= '<img onClick=\'if (confirm("'.$this->l('Desea eliminar este error en el pago?').'")) '.$onClick.'\' style="cursor:pointer; margin-left:10px;" src="../img/admin/disabled.gif" alt="'.$this->l('Eliminar registro').'" title="'.$this->l('Eliminar registro').'" />

					</td>
				</tr>';
			}
		$this->_html .= '
			</tbody>
			</table>
				<div style="margin-left:10px; font-size:12px; color:#7a7a7a;">
					<p class="clear">'.$this->l('Tipos de errores').'</p>
					<p class="clear">'.$this->l('<b>El cliente no regreso.</b> Se produce cuando el cliente entra en la pasarela de pagos pero no regresa adecuadamente. El pedido puede estar pagado, compruebe el ingreso en su pasarela de pagos').'</p>
					<p class="clear">'.$this->l('<b>Pago no admitido.</b> Se produce cuando el banco a denegado la tarjeta del cliente. La pasarela no ha efectuado ningun cobro. Elimine el registro o contacte con el cliente para intentar el pago por otro metodo.').'</p>
				</div>
			</fieldset>
		</form>';
					
	}

		public function getContent()
	{
	    // Recoger datos
		$this->_html = '<h2>'.$this->displayName.'</h2>';
		if (!empty($_POST))
		{
			$this->_postValidation();
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors AS $err)
					$this->_html .= '<div class="alert error">'. $err .'</div>';
		}
		else
			$this->_html .= '<br />';
		$this->_displaycuatrob();
		$this->_displayForm();
		$this->_displayFormView();
		return $this->_html;
	}

	public function hookPayment($params){
		
        // Variables necesarias de fuera		
		global $smarty, $cookie, $cart;	            
					
		// El número de pedido es la id unica del carrito.
		// Obtenemos los datos que nos pasa cuatrob
		$transRef	= $params['cart']->id;
		$store	 = Tools::getValue('clave', $this->clave);
	
		$smarty->assign(array(
			'transRef' => $transRef,
			'store' => $store,
			'enpruebas' =>	Configuration::get('CUATROB_PRUEBAS'),
		));
		return $this->display(__FILE__, 'cuatrob.tpl');
    }

	public function datosoperacion(){
		
		$clave_tienda	 = Tools::getValue('clave', $this->clave);

		return $clave_tienda;

	}
	
	static public function strip_ip($ip)
	{
		return preg_replace('/\.\d{1,3}$/', '', $ip);
	}
	
	static public function validaIpPasarela()
	{
		$ip_prod = self::strip_ip(gethostbyname('tpv.4b.es'));
		$ip_pruebas = self::strip_ip(gethostbyname('tpv2.4b.es'));
		$ip_cliente = self::strip_ip($_SERVER['REMOTE_ADDR']);
		if(($ip_cliente == $ip_prod) || ($ip_cliente == $ip_pruebas)) {
			return true;
		}
		else {
			return false;
		}			
	}
}