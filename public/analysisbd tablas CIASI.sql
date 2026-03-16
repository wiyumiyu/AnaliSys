use analisysbd;
-- drop table tbm_solicitud_cultivo



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


CREATE TABLE IF NOT EXISTS `tbm_solicitud_impresa` (
  `tbm_solicitud_impresa` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `enviada` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_envio` datetime NOT NULL,
  `enviada_por` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tbm_solicitud_impresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;



INSERT INTO tbm_solicitud_impresa
(id_solicitud, fecha, enviada, fecha_envio, enviada_por)
VALUES
(1, '2025-01-15 10:15:00', 1, '2025-01-15 11:00:00', 2),
(2, '2025-01-20 14:30:00', 1, '2025-01-20 15:00:00', 3),
(3, '2025-02-05 09:10:00', 1, '2025-02-05 10:00:00', 2),
(4, '2025-02-18 16:45:00', 1, '2025-02-18 17:10:00', 4),
(5, '2025-03-02 08:25:00', 1, '2025-03-02 09:00:00', 2),
(6, '2025-03-10 13:40:00', 1, '2025-03-10 14:10:00', 3),
(7, '2025-04-12 11:05:00', 1, '2025-04-12 11:45:00', 2),
(8, '2025-05-03 15:20:00', 1, '2025-05-03 16:00:00', 5),
(9, '2025-06-17 09:00:00', 1, '2025-06-17 09:45:00', 3),
(10, '2025-07-08 10:30:00', 1, '2025-07-08 11:00:00', 4),
(11, '2025-08-19 14:00:00', 1, '2025-08-19 14:40:00', 2),
(12, '2025-09-01 08:00:00', 1, '2025-09-01 08:30:00', 3),
(13, '2025-10-05 12:10:00', 1, '2025-10-05 12:50:00', 5),
(14, '2025-11-11 16:15:00', 1, '2025-11-11 17:00:00', 4),
(15, '2025-12-03 09:45:00', 1, '2025-12-03 10:15:00', 2);


CREATE TABLE IF NOT EXISTS `tbm_solicitud_cultivo` (
  `id_solicitud_cultivo` int(11) NOT NULL AUTO_INCREMENT,
  `cultivo` varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_solicitud_cultivo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=0 ;

INSERT INTO tbm_solicitud_cultivo (cultivo) VALUES
('PIÑA'),
('CAFÉ'),
('ARROZ'),
('FRESAS'),
('PAPA'),
('MAÍZ'),
('BANANO'),
('PLÁTANO'),
('CACAO'),
('CAÑA DE AZÚCAR'),
('YUCA'),
('TOMATE'),
('CEBOLLA'),
('LECHUGA'),
('ZANAHORIA'),
('PEPINO'),
('CHILE DULCE'),
('MELÓN'),
('SANDÍA'),
('AGUACATE'),
('SIN CULTIVO');
UPDATE tbm_solicitud_cultivo SET id_solicitud_cultivo = 0 WHERE id_solicitud_cultivo = 21;





CREATE TABLE IF NOT EXISTS `tbm_solicitud_muestras` (
  `id_muestras` int(11) NOT NULL AUTO_INCREMENT,
  `agno` int(11) NOT NULL,
  `idlab` int(20) NOT NULL,
  `etiqueta` varchar(350) COLLATE latin1_general_ci NOT NULL,
  `analisis` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_temporal` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_access` int(11) NOT NULL DEFAULT '0',
  `id_cultivo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_muestras`),
  KEY `idx_idlab` (`idlab`),
  KEY `idx_muestras_solicitud` (`id_solicitud`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

INSERT INTO tbm_solicitud_muestras
(agno, idlab, etiqueta, analisis, id_solicitud, id_temporal, estado, descuento, id_access, id_cultivo)
VALUES

-- SOLICITUD 1
(2026,1001,'Muestra 1 - Finca La Esperanza Lote A','0',428,0,1,0.00,0,1),
(2026,1002,'Muestra 2 - Finca La Esperanza Lote B','0',428,0,1,0.00,0,2),
(2026,1003,'Muestra 3 - Parcela Ensayo Piña Norte','0',428,0,1,0.00,0,3),
(2026,1004,'Muestra 4 - Parcela Ensayo Piña Sur','0',428,0,1,0.00,0,4),
(2026,1005,'Muestra 5 - Finca El Progreso Sector 1','0',428,0,1,0.00,0,5),
(2026,1006,'Muestra 6 - Finca El Progreso Sector 2','0',428,0,1,0.00,0,6),
(2026,1007,'Muestra 7 - Lote Experimental Café','0',428,0,1,0.00,0,7),
(2026,1008,'Muestra 8 - Lote Experimental Arroz','0',428,0,1,0.00,0,8),

-- SOLICITUD 2
(2026,1009,'Muestra 9 - Finca Santa María Lote 1','0',429,0,1,0.00,0,9),
(2026,1010,'Muestra 10 - Finca Santa María Lote 2','0',429,0,1,0.00,0,10),
(2026,1011,'Muestra 11 - Parcela Demostrativa Maíz','0',429,0,1,0.00,0,11),
(2026,1015,'Muestra 12 - Finca Los Ángeles','0',429,0,1,0.00,0,12),
(2026,1016,'Muestra 13 - Finca Los Ángeles Sector Bajo','0',429,0,1,0.00,0,13),
(2026,1017,'Muestra 14 - Finca Los Ángeles Sector Alto','0',429,0,1,0.00,0,14),
(2026,1018,'Muestra 15 - Lote Prueba Fertilización','0',429,0,1,0.00,0,15),
(2026,1019,'Muestra 16 - Parcela Testigo','0',429,0,1,0.00,0,16),

-- SOLICITUD 3
(2026,1020,'Muestra 17 - Finca La Colina','0',430,0,1,0.00,0,17),
(2026,1021,'Muestra 18 - Finca La Colina Sector 2','0',430,0,1,0.00,0,18),
(2026,1022,'Muestra 19 - Parcela Investigación A','0',430,0,1,0.00,0,19),
(2026,1023,'Muestra 20 - Parcela Investigación B','0',430,0,1,0.00,0,20),
(2026,1024,'Muestra 21 - Finca El Bosque','0',430,0,1,0.00,0,3),
(2026,1025,'Muestra 22 - Finca El Bosque Sector Bajo','0',430,0,1,0.00,0,5),
(2026,1026,'Muestra 23 - Lote Evaluación Nutrientes','0',430,0,1,0.00,0,7),
(2026,1027,'Muestra 24 - Parcela Control','0',430,0,1,0.00,0,9),
(2026,1028,'Muestra 25 - Ensayo Productividad Suelo','0',430,0,1,0.00,0,11);





CREATE TABLE IF NOT EXISTS `tbm_producto` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(160) COLLATE latin1_general_ci NOT NULL,
  `posicion` int(11) NOT NULL,
  `id_producto_tipo` int(11) NOT NULL,
  `notas` varchar(5) COLLATE latin1_general_ci NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `id_laboratorio` int(11) NOT NULL,
  `siglas` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `dias_entrega` int(11) NOT NULL DEFAULT '0',
  `nombre_detallado` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `id_producto_impuesto` int(11) NOT NULL DEFAULT '2',
  `conteo` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=359 ;

CREATE TABLE IF NOT EXISTS `tbm_producto_precio` (
  `id_producto_precio` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_moneda` int(11) NOT NULL,
  `tipo_cambio` decimal(10,4) NOT NULL,
  `codigo_barras` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `id_fundevi` int(11) NOT NULL,
  PRIMARY KEY (`id_producto_precio`),
  KEY `idx_pp_producto` (`id_producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=622 ;



CREATE TABLE IF NOT EXISTS `tbm_solicitud_muestras_analisis` (
  `id_solicitud_muestras_analisis` int(11) NOT NULL AUTO_INCREMENT,
  `id_muestras` int(11) NOT NULL,
  `id_producto_precio` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_solicitud_muestras_analisis`),
  KEY `idx_ma_muestras` (`id_muestras`),
  KEY `idx_ma_producto_precio` (`id_producto_precio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1;


INSERT INTO tbm_solicitud_muestras_analisis
(id_muestras, id_producto_precio, precio, cantidad)
VALUES
(1,149,6000,1),
(2,149,6000,1),
(3,149,6000,1),
(4,149,6000,1),
(5,149,6000,1),
(6,149,6000,1),
(7,149,6000,1),
(8,149,6000,1),
(9,149,6000,1),
(10,149,6000,1),
(11,149,6000,1),
(12,149,6000,1),
(13,149,6000,1),
(14,149,6000,1),
(15,149,6000,1),
(16,149,6000,1),
(17,149,6000,1),
(18,149,6000,1),
(19,149,6000,1),
(20,149,6000,1),
(21,149,6000,1),
(22,149,6000,1),
(23,149,6000,1),
(24,149,6000,1),
(25,149,6000,1);




CREATE TABLE IF NOT EXISTS `tbm_dircanton` (
  `id_canton` int(11) NOT NULL AUTO_INCREMENT,
  `canton` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `id_provincia` int(11) NOT NULL,
  PRIMARY KEY (`id_canton`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=89 ;









CREATE TABLE IF NOT EXISTS `tbm_solicitud_detalle` (
  `id_solicitud_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `id_solicitud_detalle_descripcion` int(11) NOT NULL,
  PRIMARY KEY (`id_solicitud_detalle`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `tbm_solicitud_detalle_descripcion` (
  `id_solicitud_detalle_descripcion` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_solicitud_detalle_descripcion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `tbm_solicitud_detalle_descripcion`
--

INSERT INTO `tbm_solicitud_detalle_descripcion` (`id_solicitud_detalle_descripcion`, `descripcion`) VALUES
(1, 'Reporte original impreso'),
(2, 'Reporte de incertidumbre'),
(3, 'Interpretación de Análisis'),
(4, 'Firmado para Refrendo Colegio de Químicos'),
(5, 'Reportes por separado'),
(6, 'Firmado por gestorías');



CREATE TABLE IF NOT EXISTS `tbm_solicitud_contacto` (
  `id_solicitud_contacto` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `id_contacto` int(11) NOT NULL,
  PRIMARY KEY (`id_solicitud_contacto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_solicitud_contacto_correo` (
  `id_correo` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud_contacto` int(11) NOT NULL,
  `id_contacto_correo` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  PRIMARY KEY (`id_correo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_solicitud_contacto_telefono` (
  `id_telefono` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud_contacto` int(11) NOT NULL,
  `id_contacto_telefono` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  PRIMARY KEY (`id_telefono`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_dirprovincia` (
  `id_provincia` int(11) NOT NULL AUTO_INCREMENT,
  `provincia` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `id_pais` int(11) NOT NULL,
  PRIMARY KEY (`id_provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=35 ;

--
-- Volcado de datos para la tabla `tbm_dirprovincia`
--



INSERT INTO `tbm_dirprovincia` (`id_provincia`, `provincia`, `id_pais`) VALUES
(1, 'SAN JOSÉ', 1),
(2, 'ALAJUELA', 1),
(3, 'CARTAGO', 1),
(4, 'HEREDIA', 1),
(5, 'GUANACASTE', 1),
(6, 'PUNTARENAS', 1),
(7, 'LIMÓN', 1),
(8, 'MANAGUA', 4),
(9, 'COCHABAMBA', 10),
(10, 'CHIRIQUÍ', 3),
(11, 'CIUDAD DE GUATEMALA', 2),
(12, 'PETÉN', 2),
(13, 'CIUDAD DE PANAMÁ', 3),
(14, 'BOCAS DEL TORO', 3),
(15, 'VERAGUAS', 3),
(16, 'MATAGALPA', 4),
(17, 'MASAYA', 4),
(18, 'JINOTEGA', 4),
(19, 'SAN CARLOS', 4),
(20, 'LEÓN', 4),
(21, 'VERACRUZ', 6),
(22, 'IOWA', 7),
(23, 'CALIFORNIA', 7),
(24, 'LOS ÁNGELES', 7),
(25, 'NEW YORK', 7),
(26, 'QUITO', 8),
(27, 'AMAZONÍA', 8),
(28, 'ARMENIA', 13),
(29, 'TEGUCIGALPA', 14),
(30, 'OCOTEPEQUE', 14),
(31, 'SAN PEDRO', 14),
(32, 'SULLANA', 16),
(33, 'TURKU', 32),
(34, 'LA ISLA ELEUTHERA', 58);



CREATE TABLE IF NOT EXISTS `tbm_cliente_subcliente` (
  `id_cliente_subcliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) COLLATE latin1_general_ci NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cliente_subcliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_cliente_telefono` (
  `id_cliente_telefono` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_telefono_tipo` int(11) NOT NULL DEFAULT '0',
  `telefono` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `extension` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `favorito` int(11) NOT NULL,
  PRIMARY KEY (`id_cliente_telefono`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_cliente_correo` (
  `id_cliente_correo` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `correo` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `descripcion` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `favorito` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_cliente_correo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tbm_cliente_contacto_correo` (
  `id_contacto_correo` int(11) NOT NULL AUTO_INCREMENT,
  `id_contacto` int(11) NOT NULL,
  `correo` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `descripcion` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `favorito` tinyint(1) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_contacto_correo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_cliente_contacto_telefono` (
  `id_contacto_telefono` int(11) NOT NULL AUTO_INCREMENT,
  `id_contacto` int(11) NOT NULL,
  `id_telefono_tipo` int(11) NOT NULL DEFAULT '0',
  `telefono` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `extension` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `favorito` tinyint(1) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_contacto_telefono`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_cliente_contacto` (
  `id_contacto` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `observaciones` text COLLATE latin1_general_ci NOT NULL,
  `responsable` tinyint(1) NOT NULL DEFAULT '0',
  `interesado` tinyint(1) NOT NULL DEFAULT '0',
  `estado` tinyint(1) NOT NULL,
  `general` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_contacto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbm_dirbarrio` (
  `id_barrio` int(11) NOT NULL AUTO_INCREMENT,
  `barrio` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `id_distrito` int(11) NOT NULL,
  PRIMARY KEY (`id_barrio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tbm_dirdistrito` (
  `id_distrito` int(11) NOT NULL AUTO_INCREMENT,
  `distrito` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `id_canton` int(11) NOT NULL,
  PRIMARY KEY (`id_distrito`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `tbm_dirpais` (
  `id_pais` int(11) NOT NULL AUTO_INCREMENT,
  `pais` varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Volcado de datos para la tabla `tbm_dirpais`
--

INSERT INTO `tbm_dirpais` (`id_pais`, `pais`) VALUES
(9, 'NO DEFINIDO'),
(1, 'COSTA RICA'),
(2, 'GUATEMALA'),
(3, 'PANAMA'),
(4, 'NICARAGUA'),
(5, 'HOLANDA'),
(6, 'MEXICO'),
(7, 'ESTADOS UNIDOS'),
(8, 'ECUADOR');

CALL sp_reporte_cliente_textura(1);

SELECT idlab, rep, estado
FROM trn_textura_muestras
WHERE id_textura in (
  SELECT id
  FROM trn_textura
  WHERE periodo = 2025
)
ORDER BY idlab;



INSERT INTO tbm_solicitud_impresa
(id_solicitud, fecha, enviada, fecha_envio, enviada_por)
VALUES
('SOL016', '2025-04-15 10:00:00', 1, '2025-04-15 10:30:00', 2),
('SOL017', '2025-04-18 11:00:00', 1, '2025-04-18 11:40:00', 3),
('SOL018', '2025-04-22 12:00:00', 1, '2025-04-22 12:50:00', 4),
('SOL019', '2025-05-10 13:00:00', 1, '2025-05-10 13:30:00', 2),
('SOL020', '2025-05-15 14:00:00', 1, '2025-05-15 14:45:00', 3);

CREATE INDEX idx_textura_idlab
ON trn_textura_muestras(idlab);

CREATE INDEX idx_textura_periodo
ON trn_textura(periodo);

CREATE INDEX idx_solicitud_muestras
ON tbm_solicitud_muestras(idlab, agno);