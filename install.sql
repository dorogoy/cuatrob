CREATE TABLE IF NOT EXISTS `PREFIX_registro_carrito` (
  `id_registro` int(10) unsigned NOT NULL auto_increment,
  `id_customer` int(10) unsigned NOT NULL,
  `id_cart` int(10) unsigned NOT NULL,
  `import` decimal(13,6) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `tipo_error` varchar(128) character set utf8 NOT NULL,
  PRIMARY KEY  (`id_registro`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;