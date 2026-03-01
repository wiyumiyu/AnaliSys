
-- Estructura de tabla para la tabla `tbm_solicitud`
--
USE analisysbd;
CREATE TABLE IF NOT EXISTS `tbm_solicitud` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL COMMENT 'USUARIO',
  `id_cultivo` int(11) NOT NULL,
  `id_dirpais` int(11) NOT NULL,
  `id_dirprovincia` int(11) NOT NULL,
  `id_dircanton` int(11) NOT NULL,
  `id_dirdistrito` int(11) NOT NULL,
  `id_dirbarrio` int(11) NOT NULL,
  `otras_senas` text COLLATE latin1_general_ci NOT NULL,
  `id_laboratorio` int(11) NOT NULL,
  `entrega` int(11) NOT NULL DEFAULT '0',
  `area_muestreada` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `edadcultivo` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `coordenadax` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `coordenaday` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `observaciones` text COLLATE latin1_general_ci NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `id_moneda` int(11) NOT NULL,
  `exento` tinyint(1) NOT NULL,
  `id_access` int(11) NOT NULL DEFAULT '0',
  `id_categoria` int(11) NOT NULL DEFAULT '1',
  `responsable` int(11) NOT NULL DEFAULT '0',
  `id_cliente_subcliente` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_solicitud`),
  KEY `idx_s_exento` (`exento`),
  KEY `idx_solicitud_id_persona` (`id_persona`),
  KEY `idx_solicitud_id_laboratorio` (`id_laboratorio`),
  KEY `idx_solicitud_id_cliente` (`id_cliente`),
  KEY `idx_solicitud_id_cliente_subcliente` (`id_cliente_subcliente`),
  KEY `idx_solicitud_responsable` (`responsable`),
  KEY `idx_solicitud_fecha` (`fecha`),
  KEY `idx_solicitud_cliente` (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=42567 ;

--
-- Volcado de datos para la tabla `tbm_solicitud`
