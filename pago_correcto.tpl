{capture name=path}{l s='Pedido completado' mod='cuatrob'}{/capture}
{include file="{$tpl_dir}/breadcrumb.tpl"} 
<h2>{l s='Pedido completado' mod='cuatrob'}</h2>

<div style="width:100%; height:120px; margin: 20px 0 20px 15px;">
	<div style="float:left; width:32%">
		<img src="{$this_path}modules/cuatrob/pago_correcto.gif" alt="Pago correcto" longdesc="Pago correcto" />
	</div>
	<div style="float:left; width:60%; text-align:left;">
		<p>{l s='Gracias por confiar en' mod='cuatrob'} <span class="bold">{$shop_name}</span>.</p><p>{l s='Su pedido ha sido registrado correctamente. En los proximos dias recibira su pedido.' mod='cuatrob'}</p>
	</div>
</div>
<div class="clear"></div>
<ul class="footer_links">
	<li><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$base_dir_ssl}my-account.php">{l s='Volver a su cuenta' mod='cuatrob'}</a></li>
	<li><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Inicio' mod='cuatrob'}</a></li>
</ul>