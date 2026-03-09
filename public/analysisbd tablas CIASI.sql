use analisysbd;
-- drop table tbm_solicitud_cultivo
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
(2026,1001,'Muestra 1 - Finca La Esperanza Lote A','0',1,0,1,0.00,0,1),
(2026,1002,'Muestra 2 - Finca La Esperanza Lote B','0',1,0,1,0.00,0,2),
(2026,1003,'Muestra 3 - Parcela Ensayo Piña Norte','0',1,0,1,0.00,0,3),
(2026,1004,'Muestra 4 - Parcela Ensayo Piña Sur','0',1,0,1,0.00,0,4),
(2026,1005,'Muestra 5 - Finca El Progreso Sector 1','0',1,0,1,0.00,0,5),
(2026,1006,'Muestra 6 - Finca El Progreso Sector 2','0',1,0,1,0.00,0,6),
(2026,1007,'Muestra 7 - Lote Experimental Café','0',1,0,1,0.00,0,7),
(2026,1008,'Muestra 8 - Lote Experimental Arroz','0',1,0,1,0.00,0,8),

-- SOLICITUD 2
(2026,1009,'Muestra 9 - Finca Santa María Lote 1','0',2,0,1,0.00,0,9),
(2026,1010,'Muestra 10 - Finca Santa María Lote 2','0',2,0,1,0.00,0,10),
(2026,1011,'Muestra 11 - Parcela Demostrativa Maíz','0',2,0,1,0.00,0,11),
(2026,1015,'Muestra 12 - Finca Los Ángeles','0',2,0,1,0.00,0,12),
(2026,1016,'Muestra 13 - Finca Los Ángeles Sector Bajo','0',2,0,1,0.00,0,13),
(2026,1017,'Muestra 14 - Finca Los Ángeles Sector Alto','0',2,0,1,0.00,0,14),
(2026,1018,'Muestra 15 - Lote Prueba Fertilización','0',2,0,1,0.00,0,15),
(2026,1019,'Muestra 16 - Parcela Testigo','0',2,0,1,0.00,0,16),

-- SOLICITUD 3
(2026,1020,'Muestra 17 - Finca La Colina','0',3,0,1,0.00,0,17),
(2026,1021,'Muestra 18 - Finca La Colina Sector 2','0',3,0,1,0.00,0,18),
(2026,1022,'Muestra 19 - Parcela Investigación A','0',3,0,1,0.00,0,19),
(2026,1023,'Muestra 20 - Parcela Investigación B','0',3,0,1,0.00,0,20),
(2026,1024,'Muestra 21 - Finca El Bosque','0',3,0,1,0.00,0,3),
(2026,1025,'Muestra 22 - Finca El Bosque Sector Bajo','0',3,0,1,0.00,0,5),
(2026,1026,'Muestra 23 - Lote Evaluación Nutrientes','0',3,0,1,0.00,0,7),
(2026,1027,'Muestra 24 - Parcela Control','0',3,0,1,0.00,0,9),
(2026,1028,'Muestra 25 - Ensayo Productividad Suelo','0',3,0,1,0.00,0,11);





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