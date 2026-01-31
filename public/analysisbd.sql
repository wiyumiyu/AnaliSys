/* =====================================================================
   SISTEMA: ANALISYSBD
   DESCRIPCIÓN:
   Base de datos para gestión de u
   ===================================================================== */

/* ============================================================
   1. ADMINISTRACIÓN DE BASE DE DATOS
   ============================================================ */

CREATE DATABASE IF NOT EXISTS analisysbd
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

-- drop database analisysbd;

USE analisysbd;

GRANT ALL PRIVILEGES ON analisysbd.* TO 'sysusuario'@'%';
FLUSH PRIVILEGES;

/* ============================================================
   2. TABLAS DE INFRAESTRUCTURA / SISTEMA
   ============================================================ */

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UPDATE migrations
SET migration = '0001_01_01_000000_create_users_table'
WHERE id = 1;

/* ============================================================
   3. TABLAS DE DOMINIO 
   ============================================================ */

CREATE TABLE tbl_persona (
  id_persona INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  apellido1 VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  apellido2 VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  id_persona_grado_academico INT NOT NULL DEFAULT 0,
  cedula VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  contrasena VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  id_estado INT NOT NULL DEFAULT 1,
  imagen TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_persona),
  UNIQUE KEY uk_persona_cedula (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE trn_roles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  descripcion VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_roles_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE trn_persona_roles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_persona INT UNSIGNED NOT NULL,
  rol_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_persona_rol (id_persona, rol_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE trn_persona_correo (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_persona INT UNSIGNED NOT NULL,
  correo VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  descripcion ENUM('PRINCIPAL','SECUNDARIO') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_persona_correo (id_persona, correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cat_telefono_tipo (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_tipo_telefono (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE trn_persona_telefono (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_persona INT UNSIGNED NOT NULL,
  id_telefono_tipo INT UNSIGNED NOT NULL,
  telefono VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_persona_telefono (id_persona, telefono)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tbl_password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_persona INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,

    UNIQUE KEY uk_tbl_password_resets_token (token_hash),
    INDEX idx_tbl_password_resets_persona (id_persona)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
  

-- Densidad Aparente
CREATE TABLE trn_densidadaparente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_densidadaparente_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_densidadaparente INT NOT NULL,
    idlab VARCHAR(100) NOT NULL,
    rep INT NOT NULL DEFAULT 1,
    id_tipo INT NOT NULL -- (0, 1 o 2)
);

CREATE TABLE trn_densidadaparente_resultado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_densidadaparente_muestras INT NOT NULL,
    resultado DECIMAL(10,2) NOT NULL,
    id_analisis INT NOT NULL
);

  CREATE TABLE tbl_bitacora (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    tabla VARCHAR(64) NOT NULL,
    usuario VARCHAR(100) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    accion ENUM('CREATE','UPDATE','DELETE') NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datos_antes JSON NULL,
    datos_despues JSON NULL
) ENGINE=InnoDB;
-- drop table tbl_bitacora
-- ALTER TABLE tbl_bitacora
-- ADD INDEX idx_operacion (operacion_id);

/* ============================================================
   4. RELACIONES (FOREIGN KEYS)
   ============================================================ */

ALTER TABLE trn_persona_roles
ADD CONSTRAINT fk_persona_roles_persona
FOREIGN KEY (id_persona)
REFERENCES tbl_persona(id_persona)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE trn_persona_roles
ADD CONSTRAINT fk_persona_roles_roles
FOREIGN KEY (rol_id)
REFERENCES trn_roles(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE trn_persona_correo
ADD CONSTRAINT fk_persona_correo_persona
FOREIGN KEY (id_persona)
REFERENCES tbl_persona(id_persona)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE trn_persona_telefono
ADD CONSTRAINT fk_persona_telefono_persona
FOREIGN KEY (id_persona)
REFERENCES tbl_persona(id_persona)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE trn_persona_telefono
ADD CONSTRAINT fk_persona_telefono_tipo
FOREIGN KEY (id_telefono_tipo)
REFERENCES cat_telefono_tipo(id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

ALTER TABLE tbl_password_resets
ADD CONSTRAINT fk_tbl_password_resets_persona
FOREIGN KEY (id_persona)
REFERENCES tbl_persona(id_persona)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Densidad Aparente
ALTER TABLE trn_densidadaparente_muestras
ADD CONSTRAINT fk_muestras_densidadaparente
FOREIGN KEY (id_densidadaparente)
REFERENCES trn_densidadaparente(id);

ALTER TABLE trn_densidadaparente_resultado
ADD CONSTRAINT fk_resultado_muestras
FOREIGN KEY (id_densidadaparente_muestras)
REFERENCES trn_densidadaparente_muestras(id);




/* ============================================================
   5. PROCEDIMIENTOS ALMACENADOS
   ============================================================ */

DELIMITER $$

/* ============================================================
   5.1 AUTENTICACIÓN
   ============================================================ */

CREATE PROCEDURE sp_login_persona (
    IN p_correo VARCHAR(100)
)
BEGIN
    SELECT
        p.id_persona,
        p.nombre,
        p.apellido1,
        p.apellido2,
        p.contrasena,
        r.nombre AS rol_nombre
    FROM tbl_persona p
    INNER JOIN trn_persona_correo pc
        ON pc.id_persona = p.id_persona
    LEFT JOIN trn_persona_roles pr
        ON pr.id_persona = p.id_persona
    LEFT JOIN trn_roles r
        ON r.id = pr.rol_id
    WHERE pc.correo COLLATE utf8mb4_unicode_ci
          = p_correo COLLATE utf8mb4_unicode_ci
      AND pc.descripcion = 'PRINCIPAL'
      AND p.id_estado = 1;
END $$

/* ============================================================
   5.2 PERSONAS – LECTURA
   ============================================================ */

CREATE PROCEDURE sp_listado_usuarios ()
BEGIN
    SELECT
        p.id_persona,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS nombre_completo,
        p.cedula,
        p.id_estado,
        r.nombre AS rol
    FROM tbl_persona p
    LEFT JOIN trn_persona_roles pr
        ON pr.id_persona = p.id_persona
    LEFT JOIN trn_roles r
        ON r.id = pr.rol_id
    ORDER BY p.nombre, p.apellido1;
END $$

CREATE PROCEDURE sp_obtener_persona (
    IN p_id_persona INT UNSIGNED
)
BEGIN
    SELECT
        id_persona,
        nombre,
        apellido1,
        apellido2,
        cedula,
        id_estado
    FROM tbl_persona
    WHERE id_persona = p_id_persona;
END $$

/* ============================================================
   5.3 PERSONAS – ESCRITURA
   ============================================================ */



CREATE PROCEDURE sp_crear_persona (
    IN p_nombre VARCHAR(50),
    IN p_apellido1 VARCHAR(50),
    IN p_apellido2 VARCHAR(50),
    IN p_id_persona_grado_academico INT,
    IN p_cedula VARCHAR(20),
    IN p_fecha_nacimiento DATE,
    IN p_contrasena VARCHAR(255),
    IN p_imagen TEXT
)
BEGIN
    INSERT INTO tbl_persona (
        nombre,
        apellido1,
        apellido2,
        id_persona_grado_academico,
        cedula,
        fecha_nacimiento,
        contrasena,
        id_estado,
        imagen
    ) VALUES (
        p_nombre,
        p_apellido1,
        p_apellido2,
        p_id_persona_grado_academico,
        p_cedula,
        p_fecha_nacimiento,
        p_contrasena,
        1,
        p_imagen
    );

    -- Return created person ID
    SELECT LAST_INSERT_ID() AS id_persona;
END$$




/* ============================================================
   5.4 PERSONAS – ESTADO / SEGURIDAD
   ============================================================ */

CREATE PROCEDURE sp_actualizar_estado_persona (
    IN p_id_persona INT UNSIGNED,
    IN p_estado INT
)
BEGIN
    UPDATE tbl_persona
    SET
        id_estado = p_estado,
        actualizado_en = CURRENT_TIMESTAMP
    WHERE id_persona = p_id_persona;
END $$

CREATE PROCEDURE sp_editar_persona (
    IN p_id_persona INT UNSIGNED,
    IN p_nombre VARCHAR(50),
    IN p_apellido1 VARCHAR(50),
    IN p_apellido2 VARCHAR(50),
    IN p_id_persona_grado_academico INT,
    IN p_cedula VARCHAR(20),
    IN p_fecha_nacimiento DATE,
    IN p_imagen TEXT
)
BEGIN
    UPDATE tbl_persona
    SET
        nombre = p_nombre,
        apellido1 = p_apellido1,
        apellido2 = p_apellido2,
        id_persona_grado_academico = p_id_persona_grado_academico,
        cedula = p_cedula,
        fecha_nacimiento = p_fecha_nacimiento,
        imagen = p_imagen,
        actualizado_en = CURRENT_TIMESTAMP
    WHERE id_persona = p_id_persona;
END $$

CREATE PROCEDURE sp_eliminar_persona (
    IN p_id_persona INT UNSIGNED
)
BEGIN
    UPDATE tbl_persona
    SET
        id_estado = 0,
        actualizado_en = CURRENT_TIMESTAMP
    WHERE id_persona = p_id_persona;
END $$

CREATE PROCEDURE sp_actualizar_contrasena (
    IN p_id_persona INT UNSIGNED,
    IN p_contrasena VARCHAR(255)
)
BEGIN
    UPDATE tbl_persona
    SET
        contrasena = p_contrasena,
        actualizado_en = CURRENT_TIMESTAMP
    WHERE id_persona = p_id_persona;
END $$



CREATE PROCEDURE sp_obtener_contrasena_persona (
    IN p_id_persona INT UNSIGNED
)
BEGIN
    SELECT contrasena
    FROM tbl_persona
    WHERE id_persona = p_id_persona;
END $$




CREATE PROCEDURE sp_editar_persona_correo (
    IN p_id INT UNSIGNED,
    IN p_descripcion ENUM('PRINCIPAL','SECUNDARIO'),
    IN p_correo VARCHAR(100)
)
BEGIN
    DECLARE v_id_persona INT UNSIGNED;

    -- Obtener persona dueña del correo
    SELECT id_persona
    INTO v_id_persona
    FROM trn_persona_correo
    WHERE id = p_id;

    -- Si se marca como PRINCIPAL, bajar el resto
    IF p_descripcion = 'PRINCIPAL' THEN
        UPDATE trn_persona_correo
        SET descripcion = 'SECUNDARIO'
        WHERE id_persona = v_id_persona
          AND descripcion = 'PRINCIPAL';
    END IF;

    -- Actualizar correo
    UPDATE trn_persona_correo
    SET
        correo = p_correo,
        descripcion = p_descripcion
    WHERE id = p_id;
END$$



/* ============================================================
   5.5 PERSONAS – CORREOS
   ============================================================ */

CREATE PROCEDURE sp_agregar_persona_correo (
    IN p_id_persona INT UNSIGNED,
    IN p_correo VARCHAR(100),
    IN p_descripcion ENUM('PRINCIPAL','SECUNDARIO')
)
BEGIN
    IF p_descripcion = 'PRINCIPAL' THEN
        UPDATE trn_persona_correo
        SET descripcion = 'SECUNDARIO'
        WHERE id_persona = p_id_persona
          AND descripcion = 'PRINCIPAL';
    END IF;

    INSERT INTO trn_persona_correo (id_persona, correo, descripcion)
    VALUES (p_id_persona, p_correo, p_descripcion);
END $$

CREATE PROCEDURE sp_eliminar_persona_correo (
    IN p_id INT UNSIGNED
)
BEGIN
    DELETE FROM trn_persona_correo
    WHERE id = p_id;
END $$

CREATE PROCEDURE sp_listar_correos_persona (
    IN p_id_persona INT UNSIGNED
)
BEGIN
    SELECT
        id,
        correo,
        descripcion
    FROM trn_persona_correo
    WHERE id_persona = p_id_persona;
END $$

/* ============================================================
   5.6 PERSONAS – TELÉFONOS
   ============================================================ */

CREATE PROCEDURE sp_agregar_persona_telefono (
    IN p_id_persona INT UNSIGNED,
    IN p_id_telefono_tipo INT UNSIGNED,
    IN p_telefono VARCHAR(20)
)
BEGIN
    INSERT INTO trn_persona_telefono (id_persona, id_telefono_tipo, telefono)
    VALUES (p_id_persona, p_id_telefono_tipo, p_telefono);
END $$

CREATE PROCEDURE sp_editar_persona_telefono (
    IN p_id INT UNSIGNED,
    IN p_id_telefono_tipo INT UNSIGNED,
    IN p_telefono VARCHAR(20)
)
BEGIN
    UPDATE trn_persona_telefono
    SET
        id_telefono_tipo = p_id_telefono_tipo,
        telefono = p_telefono
    WHERE id = p_id;
END $$

CREATE PROCEDURE sp_eliminar_persona_telefono (
    IN p_id INT UNSIGNED
)
BEGIN
    DELETE FROM trn_persona_telefono
    WHERE id = p_id;
END $$

CREATE PROCEDURE sp_listar_telefonos_persona (
    IN p_id_persona INT UNSIGNED
)
BEGIN
    SELECT
        t.id,
        t.telefono,
        t.id_telefono_tipo,
        tt.nombre AS tipo
    FROM trn_persona_telefono t
    JOIN cat_telefono_tipo tt ON tt.id = t.id_telefono_tipo
    WHERE t.id_persona = p_id_persona;
END $$

CREATE PROCEDURE sp_listar_tipos_telefono ()
BEGIN
    SELECT id, nombre
    FROM cat_telefono_tipo
    ORDER BY nombre;
END $$



CREATE PROCEDURE sp_listar_roles()
BEGIN
    SELECT id, nombre
    FROM trn_roles
    ORDER BY nombre;
END$$



CREATE PROCEDURE sp_asignar_rol_persona (
    IN p_id_persona INT UNSIGNED,
    IN p_rol_id INT UNSIGNED
)
BEGIN
    INSERT INTO trn_persona_roles (id_persona, rol_id)
    VALUES (p_id_persona, p_rol_id);
END$$



CREATE PROCEDURE sp_obtener_roles_persona (
    IN p_id_persona INT UNSIGNED
)
BEGIN
    SELECT r.id, r.nombre
    FROM trn_persona_roles pr
    INNER JOIN trn_roles r ON r.id = pr.rol_id
    WHERE pr.id_persona = p_id_persona;
END$$

CREATE PROCEDURE sp_actualizar_rol_persona (
    IN p_id_persona INT UNSIGNED,
    IN p_rol_id INT UNSIGNED
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM trn_persona_roles
        WHERE id_persona = p_id_persona
          AND rol_id = p_rol_id
    ) THEN
        -- No hacer nada: el rol ya es el mismo
        SELECT 'SIN_CAMBIOS' AS resultado;
    ELSE
        DELETE FROM trn_persona_roles
        WHERE id_persona = p_id_persona;

        INSERT INTO trn_persona_roles (id_persona, rol_id)
        VALUES (p_id_persona, p_rol_id);

        SELECT 'ACTUALIZADO' AS resultado;
    END IF;
END;

CREATE PROCEDURE sp_validar_correo_principal (
    IN p_correo VARCHAR(100)
)
BEGIN
    SELECT
        p.id_persona
    FROM tbl_persona p
    INNER JOIN trn_persona_correo pc
        ON pc.id_persona = p.id_persona
    WHERE pc.correo COLLATE utf8mb4_unicode_ci
          = p_correo COLLATE utf8mb4_unicode_ci
      AND pc.descripcion = 'PRINCIPAL'
      AND p.id_estado = 1;
END $$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE sp_bitacora_usuario (
    IN p_tabla VARCHAR(64),
    IN p_usuario INT,
    IN p_ip VARCHAR(45),
    IN p_accion ENUM('CREATE','UPDATE','DELETE'),
    IN p_datos_antes JSON,
    IN p_datos_despues JSON
)
BEGIN
    INSERT INTO tbl_bitacora (
        tabla,
        usuario,
        ip,
        accion,
        datos_antes,
        datos_despues
    )
    VALUES (
        p_tabla,
        p_usuario,
        p_ip,
        p_accion,
        p_datos_antes,
        p_datos_despues
    );
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_listar_bitacora()
BEGIN
    SELECT
        b.id,
        b.tabla,
        COALESCE(c.correo, b.usuario) AS usuario,
        b.ip,
        b.accion,
        b.fecha,
        IF(b.datos_antes IS NOT NULL, 1, 0)   AS tiene_antes,
        IF(b.datos_despues IS NOT NULL, 1, 0) AS tiene_despues
    FROM tbl_bitacora b
    LEFT JOIN trn_persona_correo c
        ON c.id_persona = b.usuario
        AND c.descripcion = 'PRINCIPAL'
    ORDER BY b.fecha DESC;
END$$

DELIMITER ;






/* ============================================================
   6. TRIGGERS
   ============================================================ */
DELIMITER $$

DROP TRIGGER IF EXISTS trg_tbl_persona_au$$
CREATE TRIGGER trg_tbl_persona_au
AFTER UPDATE ON tbl_persona
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.nombre     <=> NEW.nombre AND
        OLD.apellido1  <=> NEW.apellido1 AND
        OLD.apellido2  <=> NEW.apellido2 AND
        OLD.cedula     <=> NEW.cedula AND
        OLD.id_estado  <=> NEW.id_estado AND
        OLD.contrasena <=> NEW.contrasena AND
        OLD.imagen     <=> NEW.imagen
    ) THEN
        CALL sp_bitacora_usuario(
            'tbl_persona',
            COALESCE(@bitacora_usuario, 0),
            COALESCE(@bitacora_ip, 'UNKNOWN'),
            'UPDATE',
            JSON_OBJECT(
                'nombre', OLD.nombre,
                'apellido1', OLD.apellido1,
                'apellido2', OLD.apellido2,
                'cedula', OLD.cedula,
                'id_estado', OLD.id_estado
            ),
            JSON_OBJECT(
                'nombre', NEW.nombre,
                'apellido1', NEW.apellido1,
                'apellido2', NEW.apellido2,
                'cedula', NEW.cedula,
                'id_estado', NEW.id_estado
            )
        );
    END IF;
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_tbl_persona_ai$$
CREATE TRIGGER trg_tbl_persona_ai
AFTER INSERT ON tbl_persona
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'tbl_persona',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'CREATE',
        NULL,
        JSON_OBJECT(
            'id_persona', NEW.id_persona,
            'nombre', NEW.nombre,
            'apellido1', NEW.apellido1,
            'apellido2', NEW.apellido2,
            'cedula', NEW.cedula,
            'id_estado', NEW.id_estado
        )
    );
END$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS trg_tbl_persona_ad$$
CREATE TRIGGER trg_tbl_persona_ad
AFTER DELETE ON tbl_persona
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'tbl_persona',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'DELETE',
        JSON_OBJECT(
            'id_persona', OLD.id_persona,
            'nombre', OLD.nombre,
            'cedula', OLD.cedula
        ),
        NULL
    );
END$$

DELIMITER ;




DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_correo_ai$$
CREATE TRIGGER trg_persona_correo_ai
AFTER INSERT ON trn_persona_correo
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_persona_correo',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'CREATE',
        NULL,
        JSON_OBJECT(
            'id_persona', NEW.id_persona,
            'correo', NEW.correo,
            'descripcion', NEW.descripcion
        )
    );
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_correo_au$$
CREATE TRIGGER trg_persona_correo_au
AFTER UPDATE ON trn_persona_correo
FOR EACH ROW
BEGIN
    IF OLD.correo <> NEW.correo THEN
        CALL sp_bitacora_usuario(
            'trn_persona_correo',
            COALESCE(@bitacora_usuario, 0),
            COALESCE(@bitacora_ip, 'UNKNOWN'),
            'UPDATE',
            JSON_OBJECT('correo', OLD.correo),
            JSON_OBJECT('correo', NEW.correo)
        );
    END IF;
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_correo_ad$$
CREATE TRIGGER trg_persona_correo_ad
AFTER DELETE ON trn_persona_correo
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_persona_correo',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'DELETE',
        JSON_OBJECT(
            'id_persona', OLD.id_persona,
            'correo', OLD.correo,
            'descripcion', OLD.descripcion
        ),
        NULL
    );
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_telefono_ai$$
CREATE TRIGGER trg_persona_telefono_ai
AFTER INSERT ON trn_persona_telefono
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_persona_telefono',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'CREATE',
        NULL,
        JSON_OBJECT(
            'id_persona', NEW.id_persona,
            'telefono', NEW.telefono,
            'id_telefono_tipo', NEW.id_telefono_tipo
        )
    );
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_telefono_au$$
CREATE TRIGGER trg_persona_telefono_au
AFTER UPDATE ON trn_persona_telefono
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.telefono         <=> NEW.telefono AND
        OLD.id_telefono_tipo <=> NEW.id_telefono_tipo
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_persona_telefono',
            COALESCE(@bitacora_usuario, 0),
            COALESCE(@bitacora_ip, 'UNKNOWN'),
            'UPDATE',
            JSON_OBJECT(
                'telefono', OLD.telefono,
                'id_telefono_tipo', OLD.id_telefono_tipo
            ),
            JSON_OBJECT(
                'telefono', NEW.telefono,
                'id_telefono_tipo', NEW.id_telefono_tipo
            )
        );
    END IF;
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_telefono_ad$$
CREATE TRIGGER trg_persona_telefono_ad
AFTER DELETE ON trn_persona_telefono
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_persona_telefono',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'DELETE',
        JSON_OBJECT(
            'id_persona', OLD.id_persona,
            'telefono', OLD.telefono,
            'id_telefono_tipo', OLD.id_telefono_tipo
        ),
        NULL
    );
END$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_roles_ai$$
CREATE TRIGGER trg_persona_roles_ai
AFTER INSERT ON trn_persona_roles
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_persona_roles',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'CREATE',
        NULL,
        JSON_OBJECT(
            'id_persona', NEW.id_persona,
            'rol_id', NEW.rol_id
        )
    );
END$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS trg_persona_roles_ad$$
CREATE TRIGGER trg_persona_roles_ad
AFTER DELETE ON trn_persona_roles
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_persona_roles',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'DELETE',
        JSON_OBJECT(
            'id_persona', OLD.id_persona,
            'rol_id', OLD.rol_id
        ),
        NULL
    );
END$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS trg_densidadaparente_ai$$
CREATE TRIGGER trg_densidadaparente_ai
AFTER INSERT ON trn_densidadaparente
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_densidadaparente',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'CREATE',
        NULL,
        JSON_OBJECT(
            'id', NEW.id,
            'fecha', NEW.fecha,
            'analista', NEW.analista
        )
    );
END$$

DELIMITER ;

DELIMITER $$

DROP TRIGGER IF EXISTS trg_densidadaparente_ad$$
CREATE TRIGGER trg_densidadaparente_ad
AFTER DELETE ON trn_densidadaparente
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_densidadaparente',
        COALESCE(@bitacora_usuario, 0),
        COALESCE(@bitacora_ip, 'UNKNOWN'),
        'DELETE',
        JSON_OBJECT(
            'id', OLD.id,
            'fecha', OLD.fecha,
            'analista', OLD.analista
        ),
        NULL
    );
END$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS trg_densidadaparente_au$$
CREATE TRIGGER trg_densidadaparente_au
AFTER UPDATE ON trn_densidadaparente
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_densidadaparente',
            COALESCE(@bitacora_usuario, 0),
            COALESCE(@bitacora_ip, 'UNKNOWN'),
            'UPDATE',
            JSON_OBJECT(
                'fecha', OLD.fecha,
                'archivo', OLD.archivo,
                'analista', OLD.analista
            ),
            JSON_OBJECT(
                'fecha', NEW.fecha,
                'archivo', NEW.archivo,
                'analista', NEW.analista
            )
        );
    END IF;
END$$

DELIMITER ;




/* ============================================================
   6. DATOS INICIALES
   ============================================================ */
   
INSERT INTO `analisysbd`.`migrations`
(`id`, `migration`, `batch`) VALUES 
(1, '001_01_01_000000_create_users_table', 1);

INSERT INTO `analisysbd`.`migrations`
(`id`, `migration`, `batch`) VALUES 
(2, '0001_01_01_000001_create_cache_table', 1);

INSERT INTO `analisysbd`.`migrations`
(`id`, `migration`, `batch`) VALUES 
(3, '0001_01_01_000002_create_jobs_table', 1);

/* ============================================================
   6. Inserciones
   ============================================================ */
   
INSERT INTO trn_roles (nombre, descripcion)
VALUES
  ('ADMIN', 'Administrador del sistema'),
  ('ANALISTA', 'Analista del sistema');

INSERT INTO cat_telefono_tipo (nombre) VALUES
('Móvil'),
('Casa'),
('Trabajo'),
('Otro');

INSERT INTO tbl_persona (
  nombre,
  apellido1,
  apellido2,
  id_persona_grado_academico,
  cedula,
  fecha_nacimiento,
  contrasena,
  id_estado,
  imagen
) VALUES
(
  'Administrador',
  'Sistema',
  'Principal',
  0,
  'ADMIN-001',
  '1990-01-01',
  '$2y$10$f4Onfc5ENSM9.ov.sbft4.3ajT5lRVxbxVnehUKEKLosqR7UllzBq',
  1,
  ''
),
(
  'María',
  'González',
  'Rojas',
  1,
  'COORD-001',
  '1988-05-10',
  '$2y$10$f4Onfc5ENSM9.ov.sbft4.3ajT5lRVxbxVnehUKEKLosqR7UllzBq',
  1,
  ''
),
(
  'Juan',
  'Pérez',
  'Mora',
  2,
  'ANALISTA-001',
  '1995-03-22',
  '$2y$10$f4Onfc5ENSM9.ov.sbft4.3ajT5lRVxbxVnehUKEKLosqR7UllzBq',
  1,
  ''
);


INSERT INTO trn_persona_correo (id_persona, correo, descripcion)
VALUES
(1, 'admin@analisys.lab', 'PRINCIPAL'),
(2, 'coordinadora@analisys.lab', 'PRINCIPAL'),
(3, 'analista1@analisys.lab', 'PRINCIPAL');


INSERT INTO trn_persona_roles (id_persona, rol_id)
VALUES
(1, 1), -- ADMIN
(2, 1), -- ADMIN (si luego decides que sea COORD, cambias aquí)
(3, 2); -- ANALISTA


INSERT INTO tbl_persona (
  nombre,
  apellido1,
  apellido2,
  id_persona_grado_academico,
  cedula,
  fecha_nacimiento,
  contrasena,
  id_estado,
  imagen
) VALUES

(
  'YSDCSD',
  'Pérez',
  'Mora',
  2,
  'ANALISTA-004',
  '1995-03-22',
  '$2y$10$f4Onfc5ENSM9.ov.sbft4.3ajT5lRVxbxVnehUKEKLosqR7UllzBq',
  1,
  ''
);

INSERT INTO trn_persona_correo (id_persona, correo, descripcion)
VALUES
(4, 'mtorres70208@ufide.ac.cr', 'PRINCIPAL');
INSERT INTO trn_persona_roles (id_persona, rol_id)
VALUES
(4, 2); -- ANALISTA


SELECT * 
FROM tbl_bitacora 
ORDER BY fecha DESC;

