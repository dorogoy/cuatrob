<p class="payment_module">
	<a href="javascript:$('#cuatrob_form').submit();" title="{l s='Conectar con el TPV' mod='cuatrob'}">
		<img src="{$module_dir}tarjeta.gif" alt="{l s='Conectar con el TPV' mod='cuatrob'}" />
		{l s='Pago con tarjeta. (Conexion segura con Pasat 4B)' mod='cuatrob'} 
	</a>
</p>

<form action="{if $enpruebas} https://tpv2.4b.es/simulador/teargral.exe {else} https://tpv.4b.es/tpvv/teargral.exe {/if}" method="post" id="cuatrob_form" class="hidden">	
	<input type="hidden" name="uid" value="{$transRef}" />
	<input type="hidden" name="cc" value="{$store}" />
	<input type="hidden" name="lang" value="{$lang_iso}" />
</form>