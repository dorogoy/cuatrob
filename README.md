# Módulo 4B para Prestashop 1.4.X #

Módulo probado con Prestashop 1.4.5.1. Tiene unos cuantos cambios que quizá puedan interesar a la comunidad. Lo que más me ha preocupado de este módulo es su falta de seguridad. Después de estudiar un poco el código es demasiado sencillo validar un pedido en la tienda sin haberlo pagado.

Las mejoras añadidas son:

1. Preparado para usarse con otros idiomas aparte del castellano (hay que configurar en la pasarela el idioma de integración como "XX - VARIOS")
2. Comprueba que la IP que contacta respuesta_tpv.php está en el rango que pertenece a tpv.4b.es. La detección de la IP del banco se hace automáticamente en el caso de que cambiara en algún momento (cosa no muy probable)
3. Al validar la orden se añade el "secure_key" del cliente. Por lo tanto no aparece en el admin del pedido el famoso mensaje: "Warning : the secure key is empty, check your payment account before validation"

Por otra parte, además, sugiero que se renombre respuesta_tpv.php al implementar el módulo y que, consecuentemente, se configure la pasarela para que las urls de transacciones autorizadas y denegadas vayan a donde corresponde.

Lo ideal, evidentemente, sería implementar la comprobación de la MAC con la clave pública RSA. Seguramente lo intentaré hacer si tengo que implementar más veces esta pasarela en alguna otra tienda, pero la comprobación de la IP ya me deja mucho más tranquilo de momento.
Aún así, por favor, comprobad que los pagos se han hecho correctamente en la pasarela antes de hacer cualquier envío de productos.

### NOTA ###

Agradecimiento a todos los que han creado y contribuido código para este módulo.