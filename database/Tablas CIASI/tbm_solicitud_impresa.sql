
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbm_solicitud_impresa`
--
USE analisysbd;
CREATE TABLE IF NOT EXISTS `tbm_solicitud_impresa` (
  `tbm_solicitud_impresa` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `enviada` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_envio` datetime NOT NULL,
  `enviada_por` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tbm_solicitud_impresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8687 ;
DROP PROCEDURE IF EXISTS sp_listar_reportes_clientes;

-- drop procedure sp_listar_reportes_clientes
DELIMITER $$

CREATE PROCEDURE sp_listar_reportes_clientes (
    IN p_periodo INT,
    IN p_tipo INT,          -- 0 = pendientes, 1 = generadas
    IN p_buscar VARCHAR(100)
)
BEGIN

    SELECT 
        s.id_solicitud,
        s.numero,
        s.fecha,
        c.nombre,
        CASE 
            WHEN si.id_solicitud IS NULL THEN 'Pendiente'
            ELSE 'Generada'
        END AS estado_reporte

    FROM tbm_solicitud s
    left JOIN tbm_cliente c 
        ON c.id_cliente = s.id_cliente

    LEFT JOIN tbm_solicitud_impresa si
        ON si.id_solicitud = s.id_solicitud

    WHERE YEAR(s.fecha) = p_periodo

    AND (
        (p_tipo = 0 AND si.id_solicitud IS NULL)
        OR
        (p_tipo = 1 AND si.id_solicitud IS NOT NULL)
    )

    AND (
        p_buscar IS NULL
        OR p_buscar = ''
        OR s.numero LIKE CONCAT('%', p_buscar, '%')
        OR c.nombre LIKE CONCAT('%', p_buscar, '%')
    )

    ORDER BY s.fecha DESC;

END$$
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_obtener_reporte_cliente (
    IN p_id INT
)
BEGIN

    SELECT 
        s.*,
        c.nombre
    FROM tbm_solicitud s
    INNER JOIN tbm_cliente c
        ON c.id_cliente = s.id_cliente
    WHERE s.id_solicitud = p_id;

END$$

DELIMITER ;

CALL sp_listar_reportes_clientes(2026, 0, '');
SELECT DISTINCT YEAR(2026) 
FROM tbm_solicitud
ORDER BY 1 DESC;
