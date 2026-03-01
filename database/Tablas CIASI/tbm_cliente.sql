
--
USE analisysbd;

CREATE TABLE IF NOT EXISTS `tbm_cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `identificacion` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `id_tipo_identificacion` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `nombre_comercial` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `id_pais` int(11) NOT NULL,
  `id_provincia` int(11) NOT NULL,
  `id_canton` int(11) NOT NULL,
  `id_distrito` int(11) NOT NULL,
  `id_barrio` int(11) NOT NULL,
  `otras_senas` text COLLATE latin1_general_ci NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `observaciones` text COLLATE latin1_general_ci NOT NULL,
  `id_fundevi` int(11) NOT NULL DEFAULT '0',
  `id_access` int(11) NOT NULL DEFAULT '0',
  `id_categoria` int(11) NOT NULL DEFAULT '1',
  `validado` tinyint(1) NOT NULL DEFAULT '0',
  `exentodeimpuestos` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8025 ;


