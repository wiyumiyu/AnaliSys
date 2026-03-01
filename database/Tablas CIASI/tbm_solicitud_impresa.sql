
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbm_solicitud_impresa`
--
USE analisysbd;


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

CALL sp_listar_reportes_clientes('2026', 0, '');
SELECT 
    s.id_solicitud,
    s.numero,
    s.fecha,
    c.nombre
FROM tbm_solicitud s
LEFT JOIN tbm_cliente c 
    ON c.id_cliente = s.id_cliente
WHERE YEAR(s.fecha) = 2025
LIMIT 10;

SELECT DISTINCT YEAR(2025)
FROM tbm_solicitud
ORDER BY 1 DESC;

SELECT COUNT(*)
FROM tbm_solicitud
WHERE YEAR(fecha) = 2025;

SELECT fecha 
FROM tbm_solicitud 
LIMIT 10;
SELECT DISTINCT YEAR(fecha) 
FROM tbm_solicitud;