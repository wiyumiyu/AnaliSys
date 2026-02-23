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
  

/* ============================================================
   INGRESO DE MUESTRAS (TABLAS)
   ============================================================ */

CREATE TABLE trn_analisis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    analisis VARCHAR(100) NOT NULL,
    siglas VARCHAR(50) NOT NULL,
    origen VARCHAR(50) 
  
);


-- Textura
CREATE TABLE trn_textura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);
CREATE TABLE trn_textura_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_textura INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL,

    material int NOT NULL,
    tipo int NOT NULL,
    posicion int NOT NULL,

    estado BOOLEAN NOT NULL DEFAULT 1,
    ri BOOLEAN NOT NULL DEFAULT 0
);
CREATE TABLE trn_textura_resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_textura_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),

    estado boolean DEFAULT 1
);


-- Densidad Aparente
CREATE TABLE trn_densidad_aparente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_densidad_aparente_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_densidad_aparente INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL,

    material int NOT NULL,
    tipo int NOT NULL,
    posicion int NOT NULL,

    estado BOOLEAN NOT NULL DEFAULT 1,
    ri BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE trn_densidad_aparente_resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_densidad_aparente_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),

    estado BOOLEAN DEFAULT 1
);


-- Densidad de Particulas
CREATE TABLE trn_densidad_particulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_densidad_particulas_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_densidad_particulas INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL,

    material int NOT NULL,
    tipo int NOT NULL,
    posicion int NOT NULL,

    estado BOOLEAN NOT NULL DEFAULT 1,
    ri BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE trn_densidad_particulas_resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_densidad_particulas_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),

    estado BOOLEAN DEFAULT 1
);

-- Humedad Gravimétrica
CREATE TABLE trn_humedad_gravimetrica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_humedad_gravimetrica_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_humedad_gravimetrica INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL,

    material INT NOT NULL,
    tipo INT NOT NULL,
    posicion INT NOT NULL,

    estado BOOLEAN NOT NULL DEFAULT 1,
    ri BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE trn_humedad_gravimetrica_resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_humedad_gravimetrica_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),

    estado BOOLEAN DEFAULT 1
);

-- Conductividad Hidráulica
CREATE TABLE trn_conductividad_hidraulica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_conductividad_hidraulica_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_conductividad_hidraulica INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL,

    material INT NOT NULL,
    tipo INT NOT NULL,
    posicion INT NOT NULL,

    estado BOOLEAN NOT NULL DEFAULT 1,
    ri BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE trn_conductividad_hidraulica_resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_conductividad_hidraulica_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),

    estado BOOLEAN DEFAULT 1
);

-- Retención de Humedad
CREATE TABLE trn_retencion_humedad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_retencion_humedad_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_retencion_humedad INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL,

    material INT NOT NULL,
    tipo INT NOT NULL,
    posicion INT NOT NULL,

    estado BOOLEAN NOT NULL DEFAULT 1,
    ri BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE trn_retencion_humedad_resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_retencion_humedad_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),

    estado BOOLEAN DEFAULT 1
);

-- Granulometria
CREATE TABLE trn_granulometria (
     id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255) NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_granulometria_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_granulometria INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL,
    material INT NOT NULL,
    tipo INT NOT NULL,
    posicion INT NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT 1,
    ri BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE trn_granulometria_resultado (

    id INT AUTO_INCREMENT PRIMARY KEY,
    id_granulometria_muestras INT NOT NULL,
	id_analisis INT NOT NULL,
    resultado VARCHAR(25),
    estado BOOLEAN DEFAULT 1
);

-- Estabilidad de agregados
CREATE TABLE trn_estabilidad_agregados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255),
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_estabilidad_agregados_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estabilidad_agregados INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL DEFAULT 1,
    material INT NOT NULL,
    tipo INT NOT NULL,
    posicion INT NOT NULL,
    estado BOOLEAN DEFAULT 1,
    ri BOOLEAN DEFAULT 0
);

CREATE TABLE trn_estabilidad_agregados_resultado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estabilidad_agregados_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),
    estado BOOLEAN DEFAULT 1
);

-- Coeficiente de Extensibilidad
CREATE TABLE trn_coeficiente_extensibilidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
    archivo VARCHAR(255),
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    analista INT NOT NULL
);

CREATE TABLE trn_coeficiente_extensibilidad_muestras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_coeficiente_extensibilidad INT NOT NULL,
    idlab VARCHAR(25) NOT NULL,
    rep INT NOT NULL DEFAULT 1,
    material INT NOT NULL,
    tipo INT NOT NULL,
    posicion INT NOT NULL,
    estado BOOLEAN DEFAULT 1,
    ri BOOLEAN DEFAULT 0
);

CREATE TABLE trn_coeficiente_extensibilidad_resultado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_coeficiente_extensibilidad_muestras INT NOT NULL,
    id_analisis INT NOT NULL,
    resultado VARCHAR(25),
    estado BOOLEAN DEFAULT 1
);

/* ============================================================
   CONTROLES (TABLAS)
   ============================================================ */
CREATE TABLE trn_controles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consecutivo INT NOT NULL,
    tipo INT NOT NULL,
    fecha DATE NOT NULL DEFAULT (CURDATE()),
    id_persona INT NOT NULL
);

CREATE TABLE trn_controles_lista (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_control INT NOT NULL,
    id_archivo INT NOT NULL
);

/* ============================================================
   BITÁCORA (TABLAS)
   ============================================================ */

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



-- Textura
ALTER TABLE trn_textura_muestras
ADD CONSTRAINT fk_textura_muestras_textura
FOREIGN KEY (id_textura)
REFERENCES trn_textura(id)
ON DELETE CASCADE;

ALTER TABLE trn_textura_resultados
ADD CONSTRAINT fk_textura_resultados_resultados
FOREIGN KEY (id_textura_muestras)
REFERENCES trn_textura_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_textura_resultados
ADD CONSTRAINT fk_textura_resultados_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);

-- Densidad Aparente
ALTER TABLE trn_densidad_aparente_muestras
ADD CONSTRAINT fk_da_muestras_densidad
FOREIGN KEY (id_densidad_aparente)
REFERENCES trn_densidad_aparente(id)
ON DELETE CASCADE;

ALTER TABLE trn_densidad_aparente_resultados
ADD CONSTRAINT fk_da_resultados_muestras
FOREIGN KEY (id_densidad_aparente_muestras)
REFERENCES trn_densidad_aparente_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_densidad_aparente_resultados
ADD CONSTRAINT fk_da_resultados_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);


-- Densidad de Particulas
ALTER TABLE trn_densidad_particulas_muestras
ADD CONSTRAINT fk_dp_muestras_densidad
FOREIGN KEY (id_densidad_particulas)
REFERENCES trn_densidad_particulas(id)
ON DELETE CASCADE;

ALTER TABLE trn_densidad_particulas_resultados
ADD CONSTRAINT fk_dp_resultados_muestras
FOREIGN KEY (id_densidad_particulas_muestras)
REFERENCES trn_densidad_particulas_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_densidad_particulas_resultados
ADD CONSTRAINT fk_dp_resultados_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);

-- Humedad Gravimétrica
ALTER TABLE trn_humedad_gravimetrica_muestras
ADD CONSTRAINT fk_hg_muestras_humedad
FOREIGN KEY (id_humedad_gravimetrica)
REFERENCES trn_humedad_gravimetrica(id)
ON DELETE CASCADE;

ALTER TABLE trn_humedad_gravimetrica_resultados
ADD CONSTRAINT fk_hg_resultados_muestras
FOREIGN KEY (id_humedad_gravimetrica_muestras)
REFERENCES trn_humedad_gravimetrica_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_humedad_gravimetrica_resultados
ADD CONSTRAINT fk_hg_resultados_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);

-- Conductividad Hidráulica

ALTER TABLE trn_conductividad_hidraulica_muestras
ADD CONSTRAINT fk_ch_muestras_conductividad
FOREIGN KEY (id_conductividad_hidraulica)
REFERENCES trn_conductividad_hidraulica(id)
ON DELETE CASCADE;

ALTER TABLE trn_conductividad_hidraulica_resultados
ADD CONSTRAINT fk_ch_resultados_muestras
FOREIGN KEY (id_conductividad_hidraulica_muestras)
REFERENCES trn_conductividad_hidraulica_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_conductividad_hidraulica_resultados
ADD CONSTRAINT fk_ch_resultados_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);


-- Retención de Humedad
ALTER TABLE trn_retencion_humedad_muestras
ADD CONSTRAINT fk_rh_muestras_retencion
FOREIGN KEY (id_retencion_humedad)
REFERENCES trn_retencion_humedad(id)
ON DELETE CASCADE;

ALTER TABLE trn_retencion_humedad_resultados
ADD CONSTRAINT fk_rh_resultados_muestras
FOREIGN KEY (id_retencion_humedad_muestras)
REFERENCES trn_retencion_humedad_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_retencion_humedad_resultados
ADD CONSTRAINT fk_rh_resultados_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);

-- Granulometria
ALTER TABLE trn_granulometria_muestras
ADD CONSTRAINT fk_gran_muestras_granulometria
FOREIGN KEY (id_granulometria)
REFERENCES trn_granulometria(id)
ON DELETE CASCADE;

ALTER TABLE trn_granulometria_resultado
ADD CONSTRAINT fk_gran_resultados_muestras
FOREIGN KEY (id_granulometria_muestras)
REFERENCES trn_granulometria_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_granulometria_resultado
ADD CONSTRAINT fk_gran_resultados_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);

-- Estabilidad de Agregados
ALTER TABLE trn_estabilidad_agregados_muestras
ADD CONSTRAINT fk_ea_muestras
FOREIGN KEY (id_estabilidad_agregados)
REFERENCES trn_estabilidad_agregados(id)
ON DELETE CASCADE;

ALTER TABLE trn_estabilidad_agregados_resultado
ADD CONSTRAINT fk_ea_resultado_muestras
FOREIGN KEY (id_estabilidad_agregados_muestras)
REFERENCES trn_estabilidad_agregados_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_estabilidad_agregados_resultado
ADD CONSTRAINT fk_ea_resultado_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);

-- Coeficiente de Extensibilidad 

ALTER TABLE trn_coeficiente_extensibilidad_muestras
ADD CONSTRAINT fk_ce_muestras
FOREIGN KEY (id_coeficiente_extensibilidad)
REFERENCES trn_coeficiente_extensibilidad(id)
ON DELETE CASCADE;

ALTER TABLE trn_coeficiente_extensibilidad_resultado
ADD CONSTRAINT fk_ce_resultado_muestras
FOREIGN KEY (id_coeficiente_extensibilidad_muestras)
REFERENCES trn_coeficiente_extensibilidad_muestras(id)
ON DELETE CASCADE;

ALTER TABLE trn_coeficiente_extensibilidad_resultado
ADD CONSTRAINT fk_ce_resultado_analisis
FOREIGN KEY (id_analisis)
REFERENCES trn_analisis(id);

ALTER TABLE trn_controles_lista
ADD CONSTRAINT fk_controles_lista
FOREIGN KEY (id_control)
REFERENCES trn_controles(id)
ON DELETE CASCADE;


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

END$$


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

-- Textura
CREATE PROCEDURE sp_listar_textura_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        t.id                     AS id_archivo,
        t.periodo,
        t.fecha,
        t.archivo,
        t.analista               AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_textura t
    INNER JOIN tbl_persona p
        ON p.id_persona = t.analista
    WHERE t.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY t.fecha DESC, t.id DESC;
END$$

DELIMITER ;

DELIMITER $$
-- drop procedure  sp_listar_muestras_textura_detalle 
CREATE PROCEDURE sp_listar_muestras_textura_detalle (
    IN p_id_textura INT
)
BEGIN
    SELECT
        m.id                         AS id_muestra,
        m.idlab,
        m.rep,
		m.estado, 
        MAX(CASE WHEN a.siglas = 'PESO_SECO' THEN r.resultado END) AS peso_seco,

        MAX(CASE WHEN a.siglas = 'R1' THEN r.resultado END) AS R1,
        MAX(CASE WHEN a.siglas = 'R2' THEN r.resultado END) AS R2,
        MAX(CASE WHEN a.siglas = 'R3' THEN r.resultado END) AS R3,
        MAX(CASE WHEN a.siglas = 'R4' THEN r.resultado END) AS R4,

        MAX(CASE WHEN a.siglas = 'TEMP1' THEN r.resultado END) AS Temp1,
        MAX(CASE WHEN a.siglas = 'TEMP2' THEN r.resultado END) AS Temp2,
        MAX(CASE WHEN a.siglas = 'TEMP3' THEN r.resultado END) AS Temp3,
        MAX(CASE WHEN a.siglas = 'TEMP4' THEN r.resultado END) AS Temp4,

        MAX(CASE WHEN a.siglas = 'TIEMPO1' THEN r.resultado END) AS Tiempo1,
        MAX(CASE WHEN a.siglas = 'TIEMPO2' THEN r.resultado END) AS Tiempo2,
        MAX(CASE WHEN a.siglas = 'TIEMPO3' THEN r.resultado END) AS Tiempo3,
        MAX(CASE WHEN a.siglas = 'TIEMPO4' THEN r.resultado END) AS Tiempo4

    FROM trn_textura_muestras m
    LEFT JOIN trn_textura_resultados r
        ON r.id_textura_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'TEXTURA'

    WHERE m.id_textura = p_id_textura
   

    GROUP BY
        m.id, m.idlab, m.rep

    ORDER BY
        m.idlab, m.rep;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_textura (
    IN p_id INT
)
BEGIN
    SELECT
        id,
        id_textura,
        idlab,
        rep,
        material,
        tipo,
        posicion,
        estado,
        ri
    FROM trn_textura_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_textura_por_muestra (
    IN p_id_muestra INT
)
BEGIN
    SELECT
        r.id                AS id_resultado,
        r.id_analisis,
        a.analisis,
        a.siglas,
        r.resultado,
        r.estado
    FROM trn_textura_resultados r
    INNER JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'TEXTURA'
    WHERE r.id_textura_muestras = p_id_muestra
    ORDER BY a.id;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_textura (
    IN p_id INT,
    IN p_rep INT,
    IN p_material VARCHAR(255),
    IN p_tipo VARCHAR(100),
    IN p_posicion VARCHAR(50),
    IN p_estado TINYINT
)
BEGIN
    UPDATE trn_textura_muestras
    SET
        rep       = p_rep,
        material  = p_material,
        tipo      = p_tipo,
        posicion  = p_posicion,
        estado    = p_estado
    WHERE id = p_id;
END$$

DELIMITER ;
DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_textura (
    IN p_id_resultado INT,
    IN p_resultado VARCHAR(50)
)
BEGIN
    UPDATE trn_textura_resultados
    SET resultado = p_resultado
    WHERE id = p_id_resultado;
END$$

DELIMITER ;



DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_textura (
    IN p_id INT
)
BEGIN
    UPDATE trn_textura_muestras
    SET estado = 0
    WHERE id = p_id;
END$$

DELIMITER ;
DELIMITER $$
CREATE PROCEDURE sp_eliminar_muestra_textura (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_textura_muestras
    WHERE id = p_id;
END;
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_textura (
    IN p_id INT
)
BEGIN
    UPDATE trn_textura_muestras
    SET estado = IF(estado = 1, 0, 1)
    WHERE id = p_id;
END$$

DELIMITER ;

-- Procedimientos para Densidad Aparente

-- Listar archivos de densidad aparente por período
DELIMITER $$

CREATE PROCEDURE sp_listar_densidad_aparente_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        d.id                     AS id_archivo,
        d.periodo,
        d.fecha,
        d.archivo,
        d.analista               AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_densidad_aparente d
    INNER JOIN tbl_persona p
        ON p.id_persona = d.analista
    WHERE d.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY d.fecha DESC, d.id DESC;
END$$

DELIMITER ;

-- Listar muestras de densidad
DELIMITER $$

CREATE PROCEDURE sp_listar_muestras_densidad_aparente_detalle (
    IN p_id_densidad INT
)
BEGIN
    SELECT
        m.id                         AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,

        MAX(CASE WHEN a.siglas = 'altura' THEN r.resultado END) AS altura,
        MAX(CASE WHEN a.siglas = 'diametro' THEN r.resultado END) AS diametro,
        MAX(CASE WHEN a.siglas = 'peso_cilindro_suelo' THEN r.resultado END) AS peso_cilindro_suelo,
        MAX(CASE WHEN a.siglas = 'peso_cilindro' THEN r.resultado END) AS peso_cilindro,
        MAX(CASE WHEN a.siglas = 'temperatura' THEN r.resultado END) AS temperatura,
        MAX(CASE WHEN a.siglas = 'secado' THEN r.resultado END) AS secado


    FROM trn_densidad_aparente_muestras m
    LEFT JOIN trn_densidad_aparente_resultados r
        ON r.id_densidad_aparente_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'DENSIDAD_APARENTE'

    WHERE m.id_densidad_aparente = p_id_densidad

    GROUP BY
        m.id, m.idlab, m.rep, m.estado

    ORDER BY
        m.idlab, m.rep;
END$$

DELIMITER ;

-- Obtener una muestra específica
DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_densidad_aparente (
    IN p_id INT
)
BEGIN
    SELECT
        id,
        id_densidad_aparente,
        idlab,
        rep,
        material,
        tipo,
        posicion,
        estado,
        ri
    FROM trn_densidad_aparente_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Listar resultados por muestra

DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_densidad_aparente_por_muestra (
    IN p_id_muestra INT
)
BEGIN
    SELECT
        r.id                AS id_resultado,
        r.id_analisis,
        a.analisis,
        a.siglas,
        r.resultado,
        r.estado
    FROM trn_densidad_aparente_resultados r
    INNER JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'DENSIDAD_APARENTE'
    WHERE r.id_densidad_aparente_muestras = p_id_muestra
    ORDER BY a.id;
END$$

DELIMITER ;

-- Actualizar datos generales de la muestra

DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_densidad_aparente (
    IN p_id INT,
    IN p_rep INT,
    IN p_material VARCHAR(255),
    IN p_tipo VARCHAR(100),
    IN p_posicion VARCHAR(50),
    IN p_estado TINYINT
)
BEGIN
    UPDATE trn_densidad_aparente_muestras
    SET
        rep       = p_rep,
        material  = p_material,
        tipo      = p_tipo,
        posicion  = p_posicion,
        estado    = p_estado
    WHERE id = p_id;
END$$

DELIMITER ;

-- Actualizar un resultado puntual

DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_densidad_aparente (
    IN p_id_resultado INT,
    IN p_resultado VARCHAR(50)
)
BEGIN
    UPDATE trn_densidad_aparente_resultados
    SET resultado = p_resultado
    WHERE id = p_id_resultado;
END$$

DELIMITER ;

-- Anular muestra

DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_densidad_aparente (
    IN p_id INT
)
BEGIN
    UPDATE trn_densidad_aparente_muestras
    SET estado = 0
    WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar muestra

DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_densidad_aparente (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_densidad_aparente_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Toggle estado
DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_densidad_aparente (
    IN p_id INT
)
BEGIN
    UPDATE trn_densidad_aparente_muestras
    SET estado = IF(estado = 1, 0, 1)
    WHERE id = p_id;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_eliminar_densidad_aparente (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_densidad_aparente
    WHERE id = p_id;
END$$

DELIMITER ;





-- Procedimientos para Densidad particulas

-- Listar archivos de densidad particulas por período
DELIMITER $$

CREATE PROCEDURE sp_listar_densidad_particulas_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        d.id                     AS id_archivo,
        d.periodo,
        d.fecha,
        d.archivo,
        d.analista               AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_densidad_particulas d
    INNER JOIN tbl_persona p
        ON p.id_persona = d.analista
    WHERE d.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY d.fecha DESC, d.id DESC;
END$$

DELIMITER ;

-- Listar muestras de densidad
DELIMITER $$

CREATE PROCEDURE sp_listar_muestras_densidad_particulas_detalle (
    IN p_id_densidad INT
)
BEGIN
    SELECT
        m.id                         AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,

        MAX(CASE WHEN a.siglas = 'numero_balon_vol' THEN r.resultado END) AS numero_balon_vol,
        MAX(CASE WHEN a.siglas = 'peso_balon_vol_vacio_p1' THEN r.resultado END) AS peso_balon_vol_vacio_p1,
        MAX(CASE WHEN a.siglas = 'peso_balon_vol_suelo_seco_p2' THEN r.resultado END) AS peso_balon_vol_suelo_seco_p2,
        MAX(CASE WHEN a.siglas = 'peso_balon_vol_suelo_agua_p3' THEN r.resultado END) AS peso_balon_vol_suelo_agua_p3,
        MAX(CASE WHEN a.siglas = 'temperatura_agua' THEN r.resultado END) AS temperatura_agua


    FROM trn_densidad_particulas_muestras m
    LEFT JOIN trn_densidad_particulas_resultados r
        ON r.id_densidad_particulas_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'DENSIDAD_PARTICULAS'

    WHERE m.id_densidad_particulas = p_id_densidad

    GROUP BY
        m.id, m.idlab, m.rep, m.estado

    ORDER BY
        m.idlab, m.rep;
END$$

DELIMITER ;

-- Obtener una muestra específica
DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_densidad_particulas (
    IN p_id INT
)
BEGIN
    SELECT
        id,
        id_densidad_particulas,
        idlab,
        rep,
        material,
        tipo,
        posicion,
        estado,
        ri
    FROM trn_densidad_particulas_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Listar resultados por muestra

DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_densidad_particulas_por_muestra (
    IN p_id_muestra INT
)
BEGIN
    SELECT
        r.id                AS id_resultado,
        r.id_analisis,
        a.analisis,
        a.siglas,
        r.resultado,
        r.estado
    FROM trn_densidad_particulas_resultados r
    INNER JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'DENSIDAD_PARTICULAS'
    WHERE r.id_densidad_particulas_muestras = p_id_muestra
    ORDER BY a.id;
END$$

DELIMITER ;

-- Actualizar datos generales de la muestra

DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_densidad_particulas (
    IN p_id INT,
    IN p_rep INT,
    IN p_material VARCHAR(255),
    IN p_tipo VARCHAR(100),
    IN p_posicion VARCHAR(50),
    IN p_estado TINYINT
)
BEGIN
    UPDATE trn_densidad_particulas_muestras
    SET
        rep       = p_rep,
        material  = p_material,
        tipo      = p_tipo,
        posicion  = p_posicion,
        estado    = p_estado
    WHERE id = p_id;
END$$

DELIMITER ;

-- Actualizar un resultado puntual

DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_densidad_particulas (
    IN p_id_resultado INT,
    IN p_resultado VARCHAR(50)
)
BEGIN
    UPDATE trn_densidad_particulas_resultados
    SET resultado = p_resultado
    WHERE id = p_id_resultado;
END$$

DELIMITER ;

-- Anular muestra

DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_densidad_particulas (
    IN p_id INT
)
BEGIN
    UPDATE trn_densidad_particulas_muestras
    SET estado = 0
    WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar muestra

DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_densidad_particulas (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_densidad_particulas_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Toggle estado
DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_densidad_particulas (
    IN p_id INT
)
BEGIN
    UPDATE trn_densidad_particulas_muestras
    SET estado = IF(estado = 1, 0, 1)
    WHERE id = p_id;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_eliminar_densidad_particulas (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_densidad_particulas
    WHERE id = p_id;
END$$

DELIMITER ;

-- Procedimientos Humedad Gravimetrica

DELIMITER $$

-- Listar archivos por período
CREATE PROCEDURE sp_listar_humedad_gravimetrica_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        h.id                     AS id_archivo,
        h.periodo,
        h.fecha,
        h.archivo,
        h.analista               AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_humedad_gravimetrica h
    INNER JOIN tbl_persona p
        ON p.id_persona = h.analista
    WHERE h.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY h.fecha DESC, h.id DESC;
END$$

DELIMITER ;

-- Listar muestras del archivo (detalle)
DELIMITER $$

CREATE PROCEDURE sp_listar_muestras_humedad_gravimetrica_detalle (
    IN p_id_humedad_gravimetrica INT
)
BEGIN
    SELECT
        m.id      AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,

        MAX(CASE WHEN a.siglas = 'peso_capsula_vacia'        THEN r.resultado END) AS peso_capsula_vacia,
        MAX(CASE WHEN a.siglas = 'peso_capsula_suelohumedo' THEN r.resultado END) AS peso_capsula_suelohumedo,
        MAX(CASE WHEN a.siglas = 'peso_capsula_sueloseco'   THEN r.resultado END) AS peso_capsula_sueloseco,
        MAX(CASE WHEN a.siglas = 'temperatura_secado'       THEN r.resultado END) AS temperatura_secado,
        MAX(CASE WHEN a.siglas = 'tiempo_secado'            THEN r.resultado END) AS tiempo_secado

    FROM trn_humedad_gravimetrica_muestras m
    LEFT JOIN trn_humedad_gravimetrica_resultados r
        ON r.id_humedad_gravimetrica_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'HUMEDAD_GRAVIMETRICA'
    WHERE m.id_humedad_gravimetrica = p_id_humedad_gravimetrica
    GROUP BY m.id, m.idlab, m.rep, m.estado
    ORDER BY m.idlab, m.rep;
END$$


DELIMITER ;

-- Obtener una muestra específica
DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_humedad_gravimetrica (
    IN p_id INT
)
BEGIN
    SELECT
        id,
        id_humedad_gravimetrica,
        idlab,
        rep,
        material,
        tipo,
        posicion,
        estado,
        ri
    FROM trn_humedad_gravimetrica_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Listar resultados por muestra

DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_humedad_gravimetrica_por_muestra (
    IN p_id_muestra INT
)
BEGIN
    SELECT
        r.id                AS id_resultado,
        r.id_analisis,
        a.analisis,
        a.siglas,
        r.resultado,
        r.estado
    FROM trn_humedad_gravimetrica_resultados r
    INNER JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'HUMEDAD_GRAVIMETRICA'
    WHERE r.id_humedad_gravimetrica_muestras = p_id_muestra
    ORDER BY a.id;
END$$

DELIMITER ;

-- Actualizar datos generales de la muestra

DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_humedad_gravimetrica (
    IN p_id INT,
    IN p_rep INT,
    IN p_material VARCHAR(255),
    IN p_tipo VARCHAR(100),
    IN p_posicion VARCHAR(50),
    IN p_estado TINYINT
)
BEGIN
    UPDATE trn_humedad_gravimetrica_muestras
    SET
        rep       = p_rep,
        material  = p_material,
        tipo      = p_tipo,
        posicion  = p_posicion,
        estado    = p_estado
    WHERE id = p_id;
END$$

DELIMITER ;

-- Actualizar un resultado puntual

DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_humedad_gravimetrica (
    IN p_id_resultado INT,
    IN p_resultado VARCHAR(50)
)
BEGIN
    UPDATE trn_humedad_gravimetrica_resultados
    SET resultado = p_resultado
    WHERE id = p_id_resultado;
END$$

DELIMITER ;

-- Anular muestra

DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_humedad_gravimetrica (
    IN p_id INT
)
BEGIN
    UPDATE trn_humedad_gravimetrica_muestras
    SET estado = 0
    WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar muestra

DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_humedad_gravimetrica (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_humedad_gravimetrica_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Toggle estado muestra

DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_humedad_gravimetrica (
    IN p_id INT
)
BEGIN
    UPDATE trn_humedad_gravimetrica_muestras
    SET estado = IF(estado = 1, 0, 1)
    WHERE id = p_id;
END$$

DELIMITER ;
DELIMITER $$
CREATE PROCEDURE sp_eliminar_humedad_gravimetrica (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_humedad_gravimetrica
    WHERE id = p_id;
END$$


-- Procedimientos para Conductividad Hidraulica

-- Listar archivos por período

DELIMITER $$

CREATE PROCEDURE sp_listar_conductividad_hidraulica_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        c.id                     AS id_archivo,
        c.periodo,
        c.fecha,
        c.archivo,
        c.analista               AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_conductividad_hidraulica c
    INNER JOIN tbl_persona p
        ON p.id_persona = c.analista
    WHERE c.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY c.fecha DESC, c.id DESC;
END$$

DELIMITER ;

-- Listar muestras del archivo (detalle)

DELIMITER $$

CREATE PROCEDURE sp_listar_muestras_conductividad_hidraulica_detalle (
    IN p_id_conductividad INT
)
BEGIN
    SELECT
        m.id AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,

        MAX(CASE WHEN a.siglas = 'longitud_muestra'
                 THEN r.resultado END) AS longitud_muestra,

        MAX(CASE WHEN a.siglas = 'diametro_interno'
                 THEN r.resultado END) AS diametro_interno,

        MAX(CASE WHEN a.siglas = 'area_transversal'
                 THEN r.resultado END) AS area_transversal,

        MAX(CASE WHEN a.siglas = 'temperatura_agua'
                 THEN r.resultado END) AS temperatura_agua,

        MAX(CASE WHEN a.siglas = 'condicion_compactacion_saturacion'
                 THEN r.resultado END) AS condicion_compactacion_saturacion

    FROM trn_conductividad_hidraulica_muestras m
    LEFT JOIN trn_conductividad_hidraulica_resultados r
        ON r.id_conductividad_hidraulica_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'CONDUCTIVIDAD_HIDRAULICA'

    WHERE m.id_conductividad_hidraulica = p_id_conductividad

    GROUP BY
        m.id, m.idlab, m.rep, m.estado

    ORDER BY
        m.idlab, m.rep;
END$$

DELIMITER ;


-- Obtener una muestra

DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_conductividad_hidraulica (
    IN p_id INT
)
BEGIN
    SELECT
        id,
        id_conductividad_hidraulica,
        idlab,
        rep,
        material,
        tipo,
        posicion,
        estado,
        ri
    FROM trn_conductividad_hidraulica_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Listar resultados por muestra

DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_conductividad_hidraulica_por_muestra (
    IN p_id_muestra INT
)
BEGIN
    SELECT
        r.id          AS id_resultado,
        r.id_analisis,
        a.analisis,
        a.siglas,
        r.resultado,
        r.estado
    FROM trn_conductividad_hidraulica_resultados r
    INNER JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'CONDUCTIVIDAD_HIDRAULICA'
    WHERE r.id_conductividad_hidraulica_muestras = p_id_muestra
    ORDER BY a.id;
END$$

DELIMITER ;

-- Actualizar datos generales de la muestra

DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_conductividad_hidraulica (
    IN p_id INT,
    IN p_rep INT,
    IN p_material VARCHAR(255),
    IN p_tipo VARCHAR(100),
    IN p_posicion VARCHAR(50),
    IN p_estado TINYINT
)
BEGIN
    UPDATE trn_conductividad_hidraulica_muestras
    SET
        rep       = p_rep,
        material  = p_material,
        tipo      = p_tipo,
        posicion  = p_posicion,
        estado    = p_estado
    WHERE id = p_id;
END$$

DELIMITER ;

-- Actualizar un resultado puntual

DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_conductividad_hidraulica (
    IN p_id_resultado INT,
    IN p_resultado VARCHAR(50)
)
BEGIN
    UPDATE trn_conductividad_hidraulica_resultados
    SET resultado = p_resultado
    WHERE id = p_id_resultado;
END$$

DELIMITER ;

-- Anular muestra
DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_conductividad_hidraulica (
    IN p_id INT
)
BEGIN
    UPDATE trn_conductividad_hidraulica_muestras
    SET estado = 0
    WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar muestra

DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_conductividad_hidraulica (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_conductividad_hidraulica_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Toggle estado muestra
DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_conductividad_hidraulica (
    IN p_id INT
)
BEGIN
    UPDATE trn_conductividad_hidraulica_muestras
    SET estado = IF(estado = 1, 0, 1)
    WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar archivo completo
DELIMITER $$

CREATE PROCEDURE sp_eliminar_conductividad_hidraulica (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_conductividad_hidraulica
    WHERE id = p_id;
END$$

DELIMITER ;

-- Procedimientos Retencion Humedad

-- Listar archivos por período

DELIMITER $$

CREATE PROCEDURE sp_listar_retencion_humedad_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        r.id                     AS id_archivo,
        r.periodo,
        r.fecha,
        r.archivo,
        r.analista               AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_retencion_humedad r
    INNER JOIN tbl_persona p
        ON p.id_persona = r.analista
    WHERE r.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY r.fecha DESC, r.id DESC;
END$$

DELIMITER ;

-- Listar muestras del archivo (detalle)

DELIMITER $$

CREATE PROCEDURE sp_listar_muestras_retencion_humedad_detalle (
    IN p_id_retencion INT
)
BEGIN
    SELECT
        m.id        AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,

        MAX(CASE WHEN a.siglas = 'presion_aplicada' THEN r.resultado END) AS presion_aplicada,
        MAX(CASE WHEN a.siglas = 'ph1_L1'           THEN r.resultado END) AS ph1_L1,
        MAX(CASE WHEN a.siglas = 'ps1_L1'           THEN r.resultado END) AS ps1_L1,
        MAX(CASE WHEN a.siglas = 'ph_L2'            THEN r.resultado END) AS ph_L2,
        MAX(CASE WHEN a.siglas = 'ps2_L2'           THEN r.resultado END) AS ps2_L2,
        MAX(CASE WHEN a.siglas = 'L1'               THEN r.resultado END) AS L1,
        MAX(CASE WHEN a.siglas = 'L2'               THEN r.resultado END) AS L2

    FROM trn_retencion_humedad_muestras m
    LEFT JOIN trn_retencion_humedad_resultados r
        ON r.id_retencion_humedad_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'RETENCION_HUMEDAD'

    WHERE m.id_retencion_humedad = p_id_retencion

    GROUP BY
        m.id, m.idlab, m.rep, m.estado

    ORDER BY
        m.idlab, m.rep;
END$$

DELIMITER ;

-- Obtener una muestra específica

DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_retencion_humedad (
    IN p_id INT
)
BEGIN
    SELECT
        id,
        id_retencion_humedad,
        idlab,
        rep,
        material,
        tipo,
        posicion,
        estado,
        ri
    FROM trn_retencion_humedad_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Listar resultados por muestra

DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_retencion_humedad_por_muestra (
    IN p_id_muestra INT
)
BEGIN
    SELECT
        r.id          AS id_resultado,
        r.id_analisis,
        a.analisis,
        a.siglas,
        r.resultado,
        r.estado
    FROM trn_retencion_humedad_resultados r
    INNER JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'RETENCION_HUMEDAD'
    WHERE r.id_retencion_humedad_muestras = p_id_muestra
    ORDER BY a.id;
END$$

DELIMITER ;

-- Actualizar datos generales de la muestra

DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_retencion_humedad (
    IN p_id INT,
    IN p_rep INT,
    IN p_material VARCHAR(255),
    IN p_tipo VARCHAR(100),
    IN p_posicion VARCHAR(50),
    IN p_estado TINYINT
)
BEGIN
    UPDATE trn_retencion_humedad_muestras
    SET
        rep       = p_rep,
        material  = p_material,
        tipo      = p_tipo,
        posicion  = p_posicion,
        estado    = p_estado
    WHERE id = p_id;
END$$

DELIMITER ;

-- Actualizar un resultado puntual
DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_retencion_humedad (
    IN p_id_resultado INT,
    IN p_resultado VARCHAR(50)
)
BEGIN
    UPDATE trn_retencion_humedad_resultados
    SET resultado = p_resultado
    WHERE id = p_id_resultado;
END$$

DELIMITER ;

-- Anular muestra
DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_retencion_humedad (
    IN p_id INT
)
BEGIN
    UPDATE trn_retencion_humedad_muestras
    SET estado = 0
    WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar muestra
DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_retencion_humedad (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_retencion_humedad_muestras
    WHERE id = p_id;
END$$

DELIMITER ;

-- Toggle estado muestra

DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_retencion_humedad (
    IN p_id INT
)
BEGIN
    UPDATE trn_retencion_humedad_muestras
    SET estado = IF(estado = 1, 0, 1)
    WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar archivo completo
DELIMITER $$

CREATE PROCEDURE sp_eliminar_retencion_humedad (
    IN p_id INT
)
BEGIN
    DELETE FROM trn_retencion_humedad
    WHERE id = p_id;
END$$

DELIMITER ;

--  Procedimientos Granulometria
-- Listar por periodo
DELIMITER $$

CREATE PROCEDURE sp_listar_granulometria_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        g.id AS id_archivo,
        g.periodo,
        g.fecha,
        g.archivo,
        g.analista AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_granulometria g
    INNER JOIN tbl_persona p
        ON p.id_persona = g.analista
    WHERE g.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY g.fecha DESC, g.id DESC;
END$$

DELIMITER ;

DELIMITER $$

-- Listar muestras (detalle)

CREATE PROCEDURE sp_listar_muestras_granulometria_detalle (
    IN p_id_granulometria INT
)
BEGIN
    SELECT

        m.id AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,

        MAX(CASE WHEN a.siglas = 'peso_seco' THEN r.resultado END) AS peso_seco,
        MAX(CASE WHEN a.siglas = 'peso_lata' THEN r.resultado END) AS peso_lata,
        MAX(CASE WHEN a.siglas = 'temperatura_secado' THEN r.resultado END) AS temperatura_secado,
        MAX(CASE WHEN a.siglas = 'tiempo_secado' THEN r.resultado END) AS tiempo_secado,
        MAX(CASE WHEN a.siglas = 'fecha_secado' THEN r.resultado END) AS fecha_secado

    FROM trn_granulometria_muestras m

    LEFT JOIN trn_granulometria_resultado r
        ON r.id_granulometria_muestras = m.id
       AND r.estado = 1

    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'GRANULOMETRIA'

     WHERE m.id_granulometria = p_id_granulometria

    GROUP BY
        m.id, m.idlab, m.rep, m.estado

    ORDER BY
        m.idlab, m.rep;
END$$

DELIMITER ;

-- Obtener muestra
DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_granulometria (
    IN p_id INT
)
BEGIN

	SELECT
		id,
		id_granulometria,
		idlab,
		rep,
		material,
		tipo,
		posicion,
		estado,
		ri
	FROM trn_granulometria_muestras
	WHERE id = p_id;
END$$

DELIMITER ;

-- Listar Resultado por muestra
DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_granulometria_por_muestra (
    IN p_id_muestra INT
)
BEGIN
	SELECT
		r.id AS id_resultado,
		r.id_analisis,
		a.analisis,
		a.siglas,
		r.resultado,
		r.estado

	FROM trn_granulometria_resultado r
		INNER JOIN trn_analisis a
		ON a.id = r.id_analisis
		AND a.origen = 'GRANULOMETRIA'

	WHERE r.id_granulometria_muestras = p_id_muestra
	ORDER BY a.id;
END$$

DELIMITER ;

-- Actualizar muestra
DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_granulometria (
IN p_id INT,
IN p_rep INT,
IN p_material INT,
IN p_tipo INT,
IN p_posicion INT,
IN p_estado TINYINT
)
BEGIN
	UPDATE trn_granulometria_muestras
SET
rep = p_rep,
material = p_material,
tipo = p_tipo,
posicion = p_posicion,
estado = p_estado
WHERE id = p_id;

END$$

DELIMITER ;

-- Actualizar estado
DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_granulometria (
IN p_id_resultado INT,
IN p_resultado VARCHAR(50)
)
BEGIN
	UPDATE trn_granulometria_resultado
SET resultado = p_resultado
WHERE id = p_id_resultado;
END$$

DELIMITER ;

-- Anular muestra
DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_granulometria (
IN p_id INT
)
BEGIN
	UPDATE trn_granulometria_muestras
SET estado = 0
WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar muestra
DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_granulometria (
IN p_id INT
)
BEGIN
DELETE FROM trn_granulometria_muestras
WHERE id = p_id;
END$$

DELIMITER ;

-- Toggle estado
DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_granulometria (
IN p_id INT
)
BEGIN
UPDATE trn_granulometria_muestras
SET estado = IF(estado = 1, 0, 1)
WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar archivo
DELIMITER $$

CREATE PROCEDURE sp_eliminar_granulometria (
IN p_id INT
)
BEGIN
DELETE FROM trn_granulometria
WHERE id = p_id;
END$$

DELIMITER ;

-- Procedimientos de  Estabilidad de Agregados
-- Listar por periodo
DELIMITER $$

CREATE PROCEDURE sp_listar_estabilidad_agregados_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        e.id AS id_archivo,
        e.periodo,
        e.fecha,
        e.archivo,
        e.analista AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_estabilidad_agregados e
    INNER JOIN tbl_persona p
        ON p.id_persona = e.analista
    WHERE e.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY e.fecha DESC, e.id DESC;
END$$

DELIMITER ;

-- Listar detalle
DELIMITER $$

CREATE PROCEDURE sp_listar_muestras_estabilidad_agregados_detalle (
    IN p_id_estabilidad_agregados INT
)
BEGIN
    SELECT
        m.id AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,

        MAX(CASE WHEN a.siglas = 'peso_suelo_seco' THEN r.resultado END) AS peso_suelo_seco,
        MAX(CASE WHEN a.siglas = 'peso_tamices' THEN r.resultado END) AS peso_tamices,
        MAX(CASE WHEN a.siglas = 'temperatura' THEN r.resultado END) AS temperatura,
        MAX(CASE WHEN a.siglas = 'humedad_ambiental' THEN r.resultado END) AS humedad_ambiental,
        MAX(CASE WHEN a.siglas = 'fecha_inicio' THEN r.resultado END) AS fecha_inicio

    FROM trn_estabilidad_agregados_muestras m
    LEFT JOIN trn_estabilidad_agregados_resultado r
        ON r.id_estabilidad_agregados_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'ESTABILIDAD_AGREGADOS'

    WHERE m.id_estabilidad_agregados = p_id_estabilidad_agregados
    GROUP BY
        m.id, m.idlab, m.rep, m.estado
    ORDER BY
        m.idlab, m.rep;
END$$
DELIMITER ;

-- Obtener  muestra
DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_estabilidad_agregados (
    IN p_id INT
)
BEGIN
	SELECT
		id,
		id_estabilidad_agregados,
		idlab,
		rep,
		material,
		tipo,
		posicion,
		estado,
		ri
	FROM trn_estabilidad_agregados_muestras
	WHERE id = p_id;

END$$
DELIMITER ;

-- Listar resultados por muestra
DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_estabilidad_agregados_por_muestra (
    IN p_id_muestra INT
)
BEGIN
	SELECT
		r.id AS id_resultado,
		r.id_analisis,
		a.analisis,
		a.siglas,
		r.resultado,
		r.estado
	FROM trn_estabilidad_agregados_resultado r
	INNER JOIN trn_analisis a
		ON a.id = r.id_analisis
	   AND a.origen = 'ESTABILIDAD_AGREGADOS'
	WHERE r.id_estabilidad_agregados_muestras = p_id_muestra
	ORDER BY a.id;

END$$
DELIMITER ;

-- Actualizar muestra
DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_estabilidad_agregados (
	IN p_id INT,
	IN p_rep INT,
	IN p_material INT,
	IN p_tipo INT,
	IN p_posicion INT,
	IN p_estado TINYINT
)
BEGIN
UPDATE trn_estabilidad_agregados_muestras
	SET
		rep = p_rep,
		material = p_material,
		tipo = p_tipo,
		posicion = p_posicion,
		estado = p_estado
WHERE id = p_id;
END$$

DELIMITER ;

-- Actualizar resultado
DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_estabilidad_agregados (
IN p_id_resultado INT,
IN p_resultado VARCHAR(50)
)
BEGIN
	UPDATE trn_estabilidad_agregados_resultado
	SET resultado = p_resultado
WHERE id = p_id_resultado;
END$$

DELIMITER ;

-- Anular muestra
DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_estabilidad_agregados (
IN p_id INT

)
BEGIN
	UPDATE trn_estabilidad_agregados_muestras
	SET estado = 0
WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar muestra
DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_estabilidad_agregados (
IN p_id INT
)
	BEGIN
	DELETE FROM trn_estabilidad_agregados_muestras
WHERE id = p_id;

END$$
DELIMITER ;

-- Toggle estado
DELIMITER $$
CREATE PROCEDURE sp_toggle_estado_muestra_estabilidad_agregados (
IN p_id INT
)
	BEGIN
	UPDATE trn_estabilidad_agregados_muestras
	SET estado = IF(estado = 1, 0, 1)
WHERE id = p_id;
END$$

DELIMITER ;

-- Eliminar archivo
DELIMITER $$

CREATE PROCEDURE sp_eliminar_estabilidad_agregados (
IN p_id INT
)
	BEGIN
	DELETE FROM trn_estabilidad_agregados
WHERE id = p_id;
END$$

DELIMITER ;

-- Coeficiente de Extensibilidad
DELIMITER $$

CREATE PROCEDURE sp_listar_coeficiente_extensibilidad_por_periodo (
    IN p_periodo YEAR
)
BEGIN
    SELECT
        c.id AS id_archivo,
        c.periodo,
        c.fecha,
        c.archivo,
        c.analista AS id_analista,
        CONCAT(p.nombre, ' ', p.apellido1, ' ', p.apellido2) AS analista
    FROM trn_coeficiente_extensibilidad c
    INNER JOIN tbl_persona p
        ON p.id_persona = c.analista
    WHERE c.periodo = IFNULL(p_periodo, YEAR(CURDATE()))
    ORDER BY c.fecha DESC, c.id DESC;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_listar_muestras_coeficiente_extensibilidad_detalle (
    IN p_id_coeficiente_extensibilidad INT
)
BEGIN
    SELECT
        m.id AS id_muestra,
        m.idlab,
        m.rep,
        m.estado,
        MAX(CASE WHEN a.siglas = 'longitud_inicial' THEN r.resultado END) AS longitud_inicial,
        MAX(CASE WHEN a.siglas = 'diametro_muestra' THEN r.resultado END) AS diametro_muestra,
        MAX(CASE WHEN a.siglas = 'fecha_medicion' THEN r.resultado END) AS fecha_medicion,
        MAX(CASE WHEN a.siglas = 'hora_medicion' THEN r.resultado END) AS hora_medicion
        
    FROM trn_coeficiente_extensibilidad_muestras m
    LEFT JOIN trn_coeficiente_extensibilidad_resultado r
        ON r.id_coeficiente_extensibilidad_muestras = m.id
       AND r.estado = 1
    LEFT JOIN trn_analisis a
        ON a.id = r.id_analisis
       AND a.origen = 'COEFICIENTE_EXTENSIBILIDAD'
    WHERE m.id_coeficiente_extensibilidad = p_id_coeficiente_extensibilidad
    GROUP BY
        m.id, m.idlab, m.rep, m.estado
    ORDER BY
        m.idlab, m.rep;
        
END$$
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_obtener_muestra_coeficiente_extensibilidad (
    IN p_id INT
)
BEGIN
SELECT
    id,
    id_coeficiente_extensibilidad,
    idlab,
    rep,
    material,
    tipo,
    posicion,
    estado,
    ri
FROM trn_coeficiente_extensibilidad_muestras
WHERE id = p_id;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_listar_resultados_coeficiente_extensibilidad_por_muestra (
    IN p_id_muestra INT
)
BEGIN
SELECT

    r.id AS id_resultado,
    r.id_analisis,
    a.analisis,
    a.siglas,
    r.resultado,
    r.estado

FROM trn_coeficiente_extensibilidad_resultado r
INNER JOIN trn_analisis a
    ON a.id = r.id_analisis
   AND a.origen = 'COEFICIENTE_EXTENSIBILIDAD'
WHERE r.id_coeficiente_extensibilidad_muestras = p_id_muestra
ORDER BY a.id;

END$$
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_actualizar_muestra_coeficiente_extensibilidad (

IN p_id INT,
IN p_rep INT,
IN p_material INT,
IN p_tipo INT,
IN p_posicion INT,
IN p_estado TINYINT

)
BEGIN
UPDATE trn_coeficiente_extensibilidad_muestras
SET
rep = p_rep,
material = p_material,
tipo = p_tipo,
posicion = p_posicion,
estado = p_estado
WHERE id = p_id;
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE sp_actualizar_resultado_coeficiente_extensibilidad (
IN p_id_resultado INT,
IN p_resultado VARCHAR(50)

)
BEGIN
UPDATE trn_coeficiente_extensibilidad_resultado
SET resultado = p_resultado
WHERE id = p_id_resultado;

END$$
DELIMITER ;


DELIMITER $$

CREATE PROCEDURE sp_anular_muestra_coeficiente_extensibilidad (
IN p_id INT

)
BEGIN
UPDATE trn_coeficiente_extensibilidad_muestras
SET estado = 0
WHERE id = p_id;

END$$
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_eliminar_muestra_coeficiente_extensibilidad (
IN p_id INT
)
BEGIN
DELETE FROM trn_coeficiente_extensibilidad_muestras
WHERE id = p_id;

END$$
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_toggle_estado_muestra_coeficiente_extensibilidad (
IN p_id INT
)
BEGIN
UPDATE trn_coeficiente_extensibilidad_muestras
SET estado = IF(estado = 1, 0, 1)
WHERE id = p_id;

END$$
DELIMITER ;


DELIMITER $$

CREATE PROCEDURE sp_eliminar_coeficiente_extensibilidad (
IN p_id INT
)
BEGIN
DELETE FROM trn_coeficiente_extensibilidad
WHERE id = p_id;

END$$
DELIMITER ;

DELIMITER $$
-- drop PROCEDURE sp_listar_controles_por_anio
CREATE PROCEDURE sp_listar_controles_por_anio (
    IN p_anio INT,
    IN p_tipo INT
)
BEGIN
    SELECT
        c.id,
        c.consecutivo,
        c.tipo,
        c.fecha,
        CONCAT(p.nombre, ' ', p.apellido1) AS responsable
    FROM trn_controles c
    INNER JOIN tbl_persona p 
        ON p.id_persona = c.id_persona
    WHERE YEAR(c.fecha) = p_anio and c.tipo = p_tipo
    ORDER BY c.consecutivo ASC;
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

-- Densidad Aparente
DROP TRIGGER IF EXISTS trg_densidad_aparente_ai$$
CREATE TRIGGER trg_densidad_aparente_ai
AFTER INSERT ON trn_densidad_aparente
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_densidad_aparente',
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

DROP TRIGGER IF EXISTS trg_densidad_aparente_ad$$
CREATE TRIGGER trg_densidad_aparente_ad
AFTER DELETE ON trn_densidad_aparente
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_densidad_aparente',
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

DROP TRIGGER IF EXISTS trg_densidad_aparente_au$$
CREATE TRIGGER trg_densidad_aparente_au
AFTER UPDATE ON trn_densidad_aparente
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_densidad_aparente',
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

-- Triggers Humedad Gravimetrica

-- Insert 
DELIMITER $$

DROP TRIGGER IF EXISTS trg_humedad_gravimetrica_ai$$
CREATE TRIGGER trg_humedad_gravimetrica_ai
AFTER INSERT ON trn_humedad_gravimetrica
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_humedad_gravimetrica',
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


-- Delete
DELIMITER $$

DROP TRIGGER IF EXISTS trg_humedad_gravimetrica_ad$$
CREATE TRIGGER trg_humedad_gravimetrica_ad
AFTER DELETE ON trn_humedad_gravimetrica
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_humedad_gravimetrica',
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

-- Update

DELIMITER $$

DROP TRIGGER IF EXISTS trg_humedad_gravimetrica_au$$
CREATE TRIGGER trg_humedad_gravimetrica_au
AFTER UPDATE ON trn_humedad_gravimetrica
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_humedad_gravimetrica',
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

-- Conductividad Hidráulica

-- Insert
DELIMITER $$

DROP TRIGGER IF EXISTS trg_conductividad_hidraulica_ai$$
CREATE TRIGGER trg_conductividad_hidraulica_ai
AFTER INSERT ON trn_conductividad_hidraulica
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_conductividad_hidraulica',
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

-- Delete

DELIMITER $$

DROP TRIGGER IF EXISTS trg_conductividad_hidraulica_ad$$
CREATE TRIGGER trg_conductividad_hidraulica_ad
AFTER DELETE ON trn_conductividad_hidraulica
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_conductividad_hidraulica',
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

-- Update

DELIMITER $$

DROP TRIGGER IF EXISTS trg_conductividad_hidraulica_au$$
CREATE TRIGGER trg_conductividad_hidraulica_au
AFTER UPDATE ON trn_conductividad_hidraulica
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_conductividad_hidraulica',
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


DELIMITER $$
CREATE PROCEDURE sp_listar_bitacora()
BEGIN
    SELECT
        b.id,
        b.tabla,
        SUBSTRING_INDEX(
            COALESCE(c.correo, b.usuario),
            '@',
            1
        ) AS usuario,
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

DELIMITER $$

-- Retencion Humedad

-- Insert
DELIMITER $$

DROP TRIGGER IF EXISTS trg_retencion_humedad_ai$$
CREATE TRIGGER trg_retencion_humedad_ai
AFTER INSERT ON trn_retencion_humedad
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_retencion_humedad',
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

-- Delete
DELIMITER $$

DROP TRIGGER IF EXISTS trg_retencion_humedad_ad$$
CREATE TRIGGER trg_retencion_humedad_ad
AFTER DELETE ON trn_retencion_humedad
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_retencion_humedad',
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


-- Update
DELIMITER $$

DROP TRIGGER IF EXISTS trg_retencion_humedad_au$$
CREATE TRIGGER trg_retencion_humedad_au
AFTER UPDATE ON trn_retencion_humedad
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_retencion_humedad',
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

-- Triggers Granulometria
DELIMITER $$

DROP TRIGGER IF EXISTS trg_granulometria_ai$$
CREATE TRIGGER trg_granulometria_ai
AFTER INSERT ON trn_granulometria
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_granulometria',
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

DROP TRIGGER IF EXISTS trg_granulometria_ad$$
CREATE TRIGGER trg_granulometria_ad
AFTER DELETE ON trn_granulometria
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_granulometria',
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

DROP TRIGGER IF EXISTS trg_granulometria_au$$
CREATE TRIGGER trg_granulometria_au
AFTER UPDATE ON trn_granulometria
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_granulometria',
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

-- Triggers Estabilidad de Agregados
DELIMITER $$

DROP TRIGGER IF EXISTS trg_estabilidad_agregados_ai$$
CREATE TRIGGER trg_estabilidad_agregados_ai
AFTER INSERT ON trn_estabilidad_agregados
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_estabilidad_agregados',
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

DROP TRIGGER IF EXISTS trg_estabilidad_agregados_au$$
CREATE TRIGGER trg_estabilidad_agregados_au
AFTER UPDATE ON trn_estabilidad_agregados
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_estabilidad_agregados',
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


DELIMITER $$
DROP TRIGGER IF EXISTS trg_estabilidad_agregados_ad$$
CREATE TRIGGER trg_estabilidad_agregados_ad
AFTER DELETE ON trn_estabilidad_agregados
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_estabilidad_agregados',
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

-- Coeficiente de  Extensibilidad
DELIMITER $$

DROP TRIGGER IF EXISTS trg_coeficiente_extensibilidad_ai$$
CREATE TRIGGER trg_coeficiente_extensibilidad_ai
AFTER INSERT ON trn_coeficiente_extensibilidad
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_coeficiente_extensibilidad',
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

DROP TRIGGER IF EXISTS trg_coeficiente_extensibilidad_au$$

CREATE TRIGGER trg_coeficiente_extensibilidad_au
AFTER UPDATE ON trn_coeficiente_extensibilidad
FOR EACH ROW
BEGIN
    IF NOT (
        OLD.fecha    <=> NEW.fecha AND
        OLD.archivo  <=> NEW.archivo AND
        OLD.analista <=> NEW.analista
    ) THEN
        CALL sp_bitacora_usuario(
            'trn_coeficiente_extensibilidad',
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

DELIMITER $$
DROP TRIGGER IF EXISTS trg_coeficiente_extensibilidad_ad$$
CREATE TRIGGER trg_coeficiente_extensibilidad_ad
AFTER DELETE ON trn_coeficiente_extensibilidad
FOR EACH ROW
BEGIN
    CALL sp_bitacora_usuario(
        'trn_coeficiente_extensibilidad',
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
DELIMITER $$

CREATE PROCEDURE sp_obtener_bitacora_completa (
    IN p_id BIGINT
)
BEGIN
    SELECT
        b.id,
        b.tabla,
        SUBSTRING_INDEX(
            COALESCE(c.correo, b.usuario),
            '@',
            1
        ) AS usuario,
        b.usuario AS usuario_real,
        b.ip,
        b.accion,
        b.fecha,
        b.datos_antes,
        b.datos_despues
    FROM tbl_bitacora b
    LEFT JOIN trn_persona_correo c
        ON c.id_persona = b.usuario
        AND c.descripcion = 'PRINCIPAL'
    WHERE b.id = p_id;

END$$

DELIMITER ;



/* PROCS DE CONTROLES DE TEXTURA */

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_traer_consecutivo $$

CREATE PROCEDURE sp_traer_consecutivo(
    IN p_tipo INT,
    IN p_periodo YEAR
)
BEGIN
    SELECT IFNULL(MAX(consecutivo), 0) + 1 AS siguiente_consecutivo
    FROM trn_controles
    WHERE tipo = p_tipo
      AND YEAR(fecha) = p_periodo;
END $$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS sp_traer_archivos_textura $$

CREATE PROCEDURE sp_traer_archivos_textura(
    IN p_periodo YEAR
)
BEGIN
    SELECT t.id
    FROM trn_textura t
    LEFT JOIN trn_controles_lista cl
        ON cl.id_archivo = t.id
    WHERE t.periodo = p_periodo
      AND cl.id IS NULL;
END $$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS sp_traer_archivos_textura $$

CREATE PROCEDURE sp_traer_archivos_textura(
    IN p_periodo YEAR
)
BEGIN
    SELECT t.id
    FROM trn_textura t
    LEFT JOIN trn_controles_lista cl
        ON cl.id_archivo = t.id
    WHERE t.periodo = p_periodo
      AND cl.id IS NULL;
END $$

DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_eliminar_control $$

CREATE PROCEDURE sp_eliminar_control(
    IN p_id INT
)
BEGIN
    START TRANSACTION;

    DELETE FROM trn_controles_lista
    WHERE id_control = p_id;

    DELETE FROM trn_controles
    WHERE id = p_id;

    COMMIT;
END $$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS sp_guardar_control $$

CREATE PROCEDURE sp_guardar_control(
    IN p_tipo INT,
    IN p_periodo YEAR,
    IN p_consecutivo INT,
    IN p_id_persona INT,
    IN p_lista_archivos TEXT
)
BEGIN
    DECLARE v_id_control INT;
    DECLARE v_id_archivo INT;
    DECLARE v_pos INT DEFAULT 1;
    DECLARE v_coma INT;
    DECLARE v_existe INT DEFAULT 0;

    START TRANSACTION;

    -- Validar que no exista el consecutivo en el mismo tipo y periodo
    SELECT COUNT(*)
    INTO v_existe
    FROM trn_controles
    WHERE tipo = p_tipo
      AND consecutivo = p_consecutivo
      AND YEAR(fecha) = p_periodo;

    IF v_existe > 0 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El consecutivo ya existe para este tipo y periodo';
    END IF;

    -- Insertar control con consecutivo manual
    INSERT INTO trn_controles(consecutivo, tipo, fecha, id_persona)
    VALUES (p_consecutivo, p_tipo, CURDATE(), p_id_persona);

    SET v_id_control = LAST_INSERT_ID();

    -- Procesar lista de archivos
    archivos_loop: LOOP

        SET v_coma = LOCATE(',', p_lista_archivos, v_pos);

        IF v_coma = 0 THEN
            SET v_id_archivo = TRIM(SUBSTRING(p_lista_archivos, v_pos));
        ELSE
            SET v_id_archivo = TRIM(SUBSTRING(p_lista_archivos, v_pos, v_coma - v_pos));
        END IF;

        INSERT INTO trn_controles_lista(id_control, id_archivo)
        VALUES (v_id_control, v_id_archivo);

        IF v_coma = 0 THEN
            LEAVE archivos_loop;
        END IF;

        SET v_pos = v_coma + 1;

    END LOOP;

    COMMIT;

    SELECT v_id_control AS id_generado;

END $$

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


INSERT INTO trn_textura (periodo, archivo, fecha, analista)
VALUES (2024, 'textura_lote_2024_018.csv', '2024-09-15', 1);

INSERT INTO trn_textura (periodo, archivo, fecha, analista)
VALUES (2024, 'textura_lote_2024_018.csv', '2024-09-15', 2);

INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Peso seco',      'PESO_SECO', 'TEXTURA'),

('Resultado R1',   'R1',        'TEXTURA'),
('Resultado R2',   'R2',        'TEXTURA'),
('Resultado R3',   'R3',        'TEXTURA'),
('Resultado R4',   'R4',        'TEXTURA'),

('Temperatura 1',  'TEMP1',     'TEXTURA'),
('Temperatura 2',  'TEMP2',     'TEXTURA'),
('Temperatura 3',  'TEMP3',     'TEXTURA'),
('Temperatura 4',  'TEMP4',     'TEXTURA'),

('Tiempo 1',       'TIEMPO1',   'TEXTURA'),
('Tiempo 2',       'TIEMPO2',   'TEXTURA'),
('Tiempo 3',       'TIEMPO3',   'TEXTURA'),
('Tiempo 4',       'TIEMPO4',   'TEXTURA');


INSERT INTO trn_textura_muestras
(id_textura, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '754', 1, 1, 1, '1', 1, 0),
(1, '754', 2, 1, 1, '2', 1, 0),
(1, '755', 1, 1, 1, '3', 1, 0);


INSERT INTO trn_textura_resultados
(id_textura_muestras, id_analisis, resultado, estado)
VALUES
(1, 1, '12.45', 1), -- PESO_SECO
(1, 2, '0.226', 1), -- R1
(1, 3, '0.215', 1), -- R2
(1, 4, '0.209', 1), -- R3
(1, 5, '0.231', 1), -- R4
(1, 6, '25.0',  1), -- TEMP1
(1, 7, '25.2',  1), -- TEMP2
(1, 8, '25.1',  1), -- TEMP3
(1, 9, '25.3',  1), -- TEMP4
(1,10, '30',    1), -- TIEMPO1
(1,11, '60',    1), -- TIEMPO2
(1,12,'120',    1), -- TIEMPO3
(1,13,'360',    1); -- TIEMPO4


INSERT INTO trn_textura_resultados
(id_textura_muestras, id_analisis, resultado, estado)
VALUES
(2, 1, '12.62', 1), -- PESO_SECO
(2, 2, '0.231', 1), -- R1
(2, 3, '0.219', 1), -- R2
(2, 4, '0.214', 1), -- R3
(2, 5, '0.238', 1), -- R4
(2, 6, '24.9',  1), -- TEMP1
(2, 7, '25.1',  1), -- TEMP2
(2, 8, '25.0',  1), -- TEMP3
(2, 9, '25.4',  1), -- TEMP4
(2,10, '32',    1), -- TIEMPO1
(2,11, '62',    1), -- TIEMPO2
(2,12,'118',    1), -- TIEMPO3
(2,13,'355',    1); -- TIEMPO4

INSERT INTO trn_textura_resultados
(id_textura_muestras, id_analisis, resultado, estado)
VALUES
(3, 1, '12.18', 1), -- PESO_SECO
(3, 2, '0.219', 1), -- R1
(3, 3, '0.207', 1), -- R2
(3, 4, '0.201', 1), -- R3
(3, 5, '0.224', 1), -- R4
(3, 6, '25.3',  1), -- TEMP1
(3, 7, '25.5',  1), -- TEMP2
(3, 8, '25.4',  1), -- TEMP3
(3, 9, '25.6',  1), -- TEMP4
(3,10, '28',    1), -- TIEMPO1
(3,11, '58',    1), -- TIEMPO2
(3,12,'122',    1), -- TIEMPO3
(3,13,'365',    1); -- TIEMPO4


DROP PROCEDURE IF EXISTS sp_traer_archivos_textura;
DELIMITER $$

CREATE PROCEDURE sp_traer_archivos_textura(

)
BEGIN
    SELECT 
        t.id,
        t.archivo
    FROM trn_textura t
    LEFT JOIN trn_controles_lista cl
        ON cl.id_archivo = t.id
;
END $$

DELIMITER ;

SELECT *
FROM trn_controles_lista;

INSERT INTO trn_textura (periodo, archivo, fecha, analista)
VALUES (2024, 'textura_nuevo_2024_999.csv', '2024-01-15', 1);

INSERT INTO trn_textura (periodo, archivo, fecha, analista)
VALUES (2024, 'textura_prueba_2024_004.csv', '2024-04-15', 1);

INSERT INTO trn_textura (periodo, archivo, fecha, analista)
VALUES (2024, 'textura_libre_2024_010.csv', '2024-10-15', 1);

-- CALL sp_traer_archivos_textura();

DELETE FROM trn_controles_lista;

-- DENSIDAD APARENTE
INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Altura Cilindro', 'altura', 'DENSIDAD_APARENTE'),
('Diametro Cilindro', 'diametro', 'DENSIDAD_APARENTE'),
('Peso seco', 'peso_cilindro_suelo', 'DENSIDAD_APARENTE'),
('Peso cilindro', 'peso_cilindro',   'DENSIDAD_APARENTE'),
('Temperatura secado', 'temperatura', 'DENSIDAD_APARENTE'),
('Tiempo secado', 'secado',  'DENSIDAD_APARENTE');

-- DENSIDAD APARENTE – ARCHIVO

INSERT INTO trn_densidad_aparente (periodo, archivo, fecha, analista)
VALUES
(2024, 'DA-2026-001', '2024-09-20', 1);

-- DENSIDAD APARENTE – MUESTRAS

INSERT INTO trn_densidad_aparente_muestras
(id_densidad_aparente, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '801', 1, 1, 1, '1', 1, 0),
(1, '801', 2, 1, 1, '2', 1, 0),
(1, '802', 1, 1, 1, '3', 1, 0);

-- DENSIDAD APARENTE – RESULTADOS


INSERT INTO trn_densidad_aparente_resultados
(id_densidad_aparente_muestras, id_analisis, resultado, estado)
VALUES
(1, (SELECT id FROM trn_analisis WHERE siglas='altura' AND origen='DENSIDAD_APARENTE'), '12.50', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='diametro'   AND origen='DENSIDAD_APARENTE'), '9.80',  1),
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_cilindro_suelo'  AND origen='DENSIDAD_APARENTE'), '2.276', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_cilindro'  AND origen='DENSIDAD_APARENTE'), '1.276', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='temperatura'  AND origen='DENSIDAD_APARENTE'), '105.2', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='secado'  AND origen='DENSIDAD_APARENTE'), '120', 1),

(2, (SELECT id FROM trn_analisis WHERE siglas='altura' AND origen='DENSIDAD_APARENTE'), '12.72', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='diametro'   AND origen='DENSIDAD_APARENTE'), '9.90',  1),
(2, (SELECT id FROM trn_analisis WHERE siglas='peso_cilindro_suelo'  AND origen='DENSIDAD_APARENTE'), '1.285', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='peso_cilindro'  AND origen='DENSIDAD_APARENTE'), '1.285', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='temperatura'  AND origen='DENSIDAD_APARENTE'), '105.29', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='secado'  AND origen='DENSIDAD_APARENTE'), '120', 1),


(3, (SELECT id FROM trn_analisis WHERE siglas='altura' AND origen='DENSIDAD_APARENTE'), '12.18', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='diametro'   AND origen='DENSIDAD_APARENTE'), '9.75',  1),
(3, (SELECT id FROM trn_analisis WHERE siglas='peso_cilindro_suelo'  AND origen='DENSIDAD_APARENTE'), '1.249', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='peso_cilindro'  AND origen='DENSIDAD_APARENTE'), '1.249', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='temperatura'  AND origen='DENSIDAD_APARENTE'), '105.10', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='secado'  AND origen='DENSIDAD_APARENTE'), '120', 1);




-- DENSIDAD DE PARTICULAS
INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Número balon', 'numero_balon_vol', 'DENSIDAD_PARTICULAS'),
('Peso balon vacío P1', 'peso_balon_vol_vacio_p1', 'DENSIDAD_PARTICULAS'),
('Peso balon suelo seco P2', 'peso_balon_vol_suelo_seco_p2', 'DENSIDAD_PARTICULAS'),
('Peso balon suelo agua P3', 'peso_balon_vol_suelo_agua_p3',   'DENSIDAD_PARTICULAS'),
('Temperatura agua', 'temperatura_agua', 'DENSIDAD_PARTICULAS');


INSERT INTO trn_densidad_particulas (periodo, archivo, fecha, analista)
VALUES
(2024, 'DP-2026-001', '2024-09-20', 1);

-- DENSIDAD particulas – MUESTRAS

INSERT INTO trn_densidad_particulas_muestras
(id_densidad_particulas, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '801', 1, 1, 1, '1', 1, 0),
(1, '801', 2, 1, 1, '2', 1, 0),
(1, '802', 1, 1, 1, '3', 1, 0);

-- DENSIDAD particulas – RESULTADOS


INSERT INTO trn_densidad_particulas_resultados
(id_densidad_particulas_muestras, id_analisis, resultado, estado)
VALUES
(1, (SELECT id FROM trn_analisis WHERE siglas='numero_balon_vol' AND origen='DENSIDAD_PARTICULAS'), '12.50', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_vacio_p1'   AND origen='DENSIDAD_PARTICULAS'), '9.80',  1),
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_suelo_seco_p2'  AND origen='DENSIDAD_PARTICULAS'), '2.276', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_suelo_agua_p3'  AND origen='DENSIDAD_PARTICULAS'), '1.276', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='temperatura_agua'  AND origen='DENSIDAD_PARTICULAS'), '105.2', 1),


(2, (SELECT id FROM trn_analisis WHERE siglas='numero_balon_vol' AND origen='DENSIDAD_PARTICULAS'), '12.72', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_vacio_p1'   AND origen='DENSIDAD_PARTICULAS'), '9.90',  1),
(2, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_suelo_seco_p2'  AND origen='DENSIDAD_PARTICULAS'), '1.285', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_suelo_agua_p3'  AND origen='DENSIDAD_PARTICULAS'), '1.285', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='temperatura_agua'  AND origen='DENSIDAD_PARTICULAS'), '105.29', 1),



(3, (SELECT id FROM trn_analisis WHERE siglas='numero_balon_vol' AND origen='DENSIDAD_PARTICULAS'), '12.18', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_vacio_p1'   AND origen='DENSIDAD_PARTICULAS'), '9.75',  1),
(3, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_suelo_seco_p2'  AND origen='DENSIDAD_PARTICULAS'), '1.249', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='peso_balon_vol_suelo_agua_p3'  AND origen='DENSIDAD_PARTICULAS'), '1.249', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='temperatura_agua'  AND origen='DENSIDAD_PARTICULAS'), '105.10', 1);


-- HUMEDAD GRAVIMÉTRICA – ANÁLISIS
INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Peso cápsula vacía',        'peso_capsula_vacia',        'HUMEDAD_GRAVIMETRICA'),
('Peso cápsula + suelo húmedo','peso_capsula_suelohumedo', 'HUMEDAD_GRAVIMETRICA'),
('Peso cápsula + suelo seco', 'peso_capsula_sueloseco',    'HUMEDAD_GRAVIMETRICA'),
('Temperatura secado',        'temperatura_secado',        'HUMEDAD_GRAVIMETRICA'),
('Tiempo secado',             'tiempo_secado',             'HUMEDAD_GRAVIMETRICA');


-- HUMEDAD GRAVIMÉTRICA – ARCHIVO
INSERT INTO trn_humedad_gravimetrica (periodo, archivo, fecha, analista)
VALUES
(2024, 'HG-2026-001', '2024-09-20', 1);

-- HUMEDAD GRAVIMÉTRICA – MUESTRAS
INSERT INTO trn_humedad_gravimetrica_muestras
(id_humedad_gravimetrica, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '901', 1, 1, 1, '1', 1, 0),
(1, '901', 2, 1, 1, '2', 1, 0),
(1, '902', 1, 1, 1, '3', 1, 0);

-- HUMEDAD GRAVIMÉTRICA – RESULTADOS

INSERT INTO trn_humedad_gravimetrica_resultados
(id_humedad_gravimetrica_muestras, id_analisis, resultado, estado)
VALUES
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_capsula_vacia' AND origen='HUMEDAD_GRAVIMETRICA'), '5.20', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_capsula_suelohumedo' AND origen='HUMEDAD_GRAVIMETRICA'), '18.50', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='peso_capsula_sueloseco' AND origen='HUMEDAD_GRAVIMETRICA'), '15.20', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='temperatura_secado' AND origen='HUMEDAD_GRAVIMETRICA'), '105', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='tiempo_secado' AND origen='HUMEDAD_GRAVIMETRICA'), '120', 1);

-- Conductividad Hidraulica

INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Longitud de la muestra', 'longitud_muestra', 'CONDUCTIVIDAD_HIDRAULICA'),
('Diámetro interno', 'diametro_interno', 'CONDUCTIVIDAD_HIDRAULICA'),
('Área transversal', 'area_transversal', 'CONDUCTIVIDAD_HIDRAULICA'),
('Temperatura del agua', 'temperatura_agua', 'CONDUCTIVIDAD_HIDRAULICA'),
('Condición de compactación / saturación', 'condicion_compactacion_saturacion', 'CONDUCTIVIDAD_HIDRAULICA');

INSERT INTO trn_conductividad_hidraulica (periodo, archivo, fecha, analista)
VALUES
(2024, 'CH-2026-001', '2024-09-20', 1);

INSERT INTO trn_conductividad_hidraulica_muestras
(id_conductividad_hidraulica, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '801', 1, 1, 1, 1, 1, 0),
(1, '801', 2, 1, 1, 2, 1, 0),
(1, '802', 1, 1, 1, 3, 1, 0);

INSERT INTO trn_conductividad_hidraulica_resultados
(id_conductividad_hidraulica_muestras, id_analisis, resultado, estado)
VALUES
-- MUESTRA 1
(1, (SELECT id FROM trn_analisis WHERE siglas='longitud_muestra' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '10.50', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='diametro_interno' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '5.20', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='area_transversal' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '21.24', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='temperatura_agua' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '23.5', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='condicion_compactacion_saturacion' AND origen='CONDUCTIVIDAD_HIDRAULICA'), 'Saturada', 1),

-- MUESTRA 2
(2, (SELECT id FROM trn_analisis WHERE siglas='longitud_muestra' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '10.60', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='diametro_interno' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '5.25', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='area_transversal' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '21.65', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='temperatura_agua' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '23.8', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='condicion_compactacion_saturacion' AND origen='CONDUCTIVIDAD_HIDRAULICA'), 'Saturada', 1),

-- MUESTRA 3
(3, (SELECT id FROM trn_analisis WHERE siglas='longitud_muestra' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '10.40', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='diametro_interno' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '5.10', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='area_transversal' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '20.44', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='temperatura_agua' AND origen='CONDUCTIVIDAD_HIDRAULICA'), '22.9', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='condicion_compactacion_saturacion' AND origen='CONDUCTIVIDAD_HIDRAULICA'), 'Compactada', 1);


-- RETENCIÓN DE HUMEDAD
INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Presión aplicada',          'presion_aplicada', 'RETENCION_HUMEDAD'),
('Peso húmedo L1',            'ph1_L1',            'RETENCION_HUMEDAD'),
('Peso seco L1',              'ps1_L1',            'RETENCION_HUMEDAD'),
('Peso húmedo L2',            'ph_L2',              'RETENCION_HUMEDAD'),
('Peso seco L2',              'ps2_L2',             'RETENCION_HUMEDAD'),
('Longitud L1',               'L1',                 'RETENCION_HUMEDAD'),
('Longitud L2',               'L2',                 'RETENCION_HUMEDAD');

INSERT INTO trn_retencion_humedad (periodo, archivo, fecha, analista)
VALUES
(2024, 'RH-2026-001', '2024-09-20', 1);

INSERT INTO trn_retencion_humedad_muestras
(id_retencion_humedad, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '801', 1, 1, 1, '1', 1, 0),
(1, '801', 2, 1, 1, '2', 1, 0),
(1, '802', 1, 1, 1, '3', 1, 0);

INSERT INTO trn_retencion_humedad_resultados
(id_retencion_humedad_muestras, id_analisis, resultado, estado)
VALUES
-- MUESTRA 1
(1, (SELECT id FROM trn_analisis WHERE siglas='presion_aplicada' AND origen='RETENCION_HUMEDAD'), '33', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='ph1_L1'            AND origen='RETENCION_HUMEDAD'), '25.30', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='ps1_L1'            AND origen='RETENCION_HUMEDAD'), '22.10', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='ph_L2'             AND origen='RETENCION_HUMEDAD'), '24.80', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='ps2_L2'            AND origen='RETENCION_HUMEDAD'), '21.90', 1),
(1, (SELECT id FROM trn_analisis WHERE siglas='L1'                AND origen='RETENCION_HUMEDAD'), '5.00',  1),
(1, (SELECT id FROM trn_analisis WHERE siglas='L2'                AND origen='RETENCION_HUMEDAD'), '4.80',  1),

-- MUESTRA 2
(2, (SELECT id FROM trn_analisis WHERE siglas='presion_aplicada' AND origen='RETENCION_HUMEDAD'), '33', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='ph1_L1'            AND origen='RETENCION_HUMEDAD'), '26.10', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='ps1_L1'            AND origen='RETENCION_HUMEDAD'), '22.90', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='ph_L2'             AND origen='RETENCION_HUMEDAD'), '25.40', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='ps2_L2'            AND origen='RETENCION_HUMEDAD'), '22.30', 1),
(2, (SELECT id FROM trn_analisis WHERE siglas='L1'                AND origen='RETENCION_HUMEDAD'), '5.10',  1),
(2, (SELECT id FROM trn_analisis WHERE siglas='L2'                AND origen='RETENCION_HUMEDAD'), '4.90',  1),

-- MUESTRA 3
(3, (SELECT id FROM trn_analisis WHERE siglas='presion_aplicada' AND origen='RETENCION_HUMEDAD'), '33', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='ph1_L1'            AND origen='RETENCION_HUMEDAD'), '24.70', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='ps1_L1'            AND origen='RETENCION_HUMEDAD'), '21.60', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='ph_L2'             AND origen='RETENCION_HUMEDAD'), '24.20', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='ps2_L2'            AND origen='RETENCION_HUMEDAD'), '21.40', 1),
(3, (SELECT id FROM trn_analisis WHERE siglas='L1'                AND origen='RETENCION_HUMEDAD'), '4.95',  1),
(3, (SELECT id FROM trn_analisis WHERE siglas='L2'                AND origen='RETENCION_HUMEDAD'), '4.70',  1);


-- Granulometria
INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Peso del material seco total', 'peso_seco', 'GRANULOMETRIA'),
('Peso de lata',                 'peso_lata',          'GRANULOMETRIA'),
('Temperatura de secado',       'temperatura_secado', 'GRANULOMETRIA'),
('Tiempo de secado',            'tiempo_secado',      'GRANULOMETRIA'),
('Fecha de secado',             'fecha_secado',       'GRANULOMETRIA');

INSERT INTO trn_granulometria (periodo, archivo, fecha, analista)
VALUES
(2024, 'GR-2026-001', '2024-02-14', 1);

INSERT INTO trn_granulometria_muestras
(id_granulometria, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '901', 1, 1, 1, 1, 1, 0),
(1, '901', 2, 1, 1, 2, 1, 0),
(1, '902', 1, 1, 1, 3, 1, 0);

INSERT INTO trn_granulometria_resultado
(id_granulometria_muestras, id_analisis, resultado, estado)
-- Muestra 1
VALUES
(1,(SELECT id FROM trn_analisis WHERE siglas='peso_seco' AND origen='GRANULOMETRIA'),'500',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='peso_lata' AND origen='GRANULOMETRIA'),'120',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='temperatura_secado' AND origen='GRANULOMETRIA'),'105',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='tiempo_secado' AND origen='GRANULOMETRIA'),'1440',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='fecha_secado' AND origen='GRANULOMETRIA'),'2024-02-14',1),

-- Muestra 2
(2,(SELECT id FROM trn_analisis WHERE siglas='peso_seco' AND origen='GRANULOMETRIA'),'480',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='peso_lata' AND origen='GRANULOMETRIA'),'118',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='temperatura_secado' AND origen='GRANULOMETRIA'),'105',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='tiempo_secado' AND origen='GRANULOMETRIA'),'1440',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='fecha_secado' AND origen='GRANULOMETRIA'),'2024-02-14',1),

-- Muestra 3
(3,(SELECT id FROM trn_analisis WHERE siglas='peso_seco' AND origen='GRANULOMETRIA'),'510',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='peso_lata' AND origen='GRANULOMETRIA'),'121',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='temperatura_secado' AND origen='GRANULOMETRIA'),'105',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='tiempo_secado' AND origen='GRANULOMETRIA'),'1440',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='fecha_secado' AND origen='GRANULOMETRIA'),'2024-02-14',1);

-- Estabilidad de Agregados

INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES
('Peso total de suelo seco usado',  'peso_suelo_seco',      'ESTABILIDAD_AGREGADOS'),
('Peso del conjunto de tamices',    'peso_tamices',        'ESTABILIDAD_AGREGADOS'),
('Temperatura','temperatura',       'ESTABILIDAD_AGREGADOS'),
('Humedad Ambiental',  'humedad_ambiental', 'ESTABILIDAD_AGREGADOS'),
('Fecha de inicio del análisis',         'fecha_inicio',     'ESTABILIDAD_AGREGADOS');

INSERT INTO trn_estabilidad_agregados
(periodo, archivo, fecha, analista)
VALUES
(2024, 'EA-2026-001', '2024-02-15', 1);

INSERT INTO trn_estabilidad_agregados_muestras
(id_estabilidad_agregados, idlab, rep, material, tipo, posicion, estado, ri)
VALUES
(1, '1001', 1, 1, 1, 1, 1, 0),
(1, '1001', 2, 1, 1, 2, 1, 0),
(1, '1002', 1, 1, 1, 3, 1, 0);

INSERT INTO trn_estabilidad_agregados_resultado
(id_estabilidad_agregados_muestras, id_analisis, resultado, estado)
VALUES

-- Muestra 1
(1,(SELECT id FROM trn_analisis WHERE siglas='peso_suelo_seco' AND origen='ESTABILIDAD_AGREGADOS'),'500',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='peso_tamices' AND origen='ESTABILIDAD_AGREGADOS'),'1200',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='temperatura' AND origen='ESTABILIDAD_AGREGADOS'),'25',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='humedad_ambiental' AND origen='ESTABILIDAD_AGREGADOS'),'60',1),
(1,(SELECT id FROM trn_analisis WHERE siglas='fecha_inicio' AND origen='ESTABILIDAD_AGREGADOS'),'2024-02-15',1),

-- Muestra 2
(2,(SELECT id FROM trn_analisis WHERE siglas='peso_suelo_seco' AND origen='ESTABILIDAD_AGREGADOS'),'510',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='peso_tamices' AND origen='ESTABILIDAD_AGREGADOS'),'1210',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='temperatura' AND origen='ESTABILIDAD_AGREGADOS'),'26',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='humedad_ambiental' AND origen='ESTABILIDAD_AGREGADOS'),'62',1),
(2,(SELECT id FROM trn_analisis WHERE siglas='fecha_inicio' AND origen='ESTABILIDAD_AGREGADOS'),'2024-02-15',1),

-- Muestra 3

(3,(SELECT id FROM trn_analisis WHERE siglas='peso_suelo_seco' AND origen='ESTABILIDAD_AGREGADOS'),'495',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='peso_tamices' AND origen='ESTABILIDAD_AGREGADOS'),'1195',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='temperatura' AND origen='ESTABILIDAD_AGREGADOS'),'24',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='humedad_ambiental' AND origen='ESTABILIDAD_AGREGADOS'),'59',1),
(3,(SELECT id FROM trn_analisis WHERE siglas='fecha_inicio' AND origen='ESTABILIDAD_AGREGADOS'),'2024-02-15',1);

-- Coeficiente de Extensibilidad

INSERT INTO trn_analisis (analisis, siglas, origen)
VALUES

('Longitud inicial',          'longitud_inicial',     'COEFICIENTE_EXTENSIBILIDAD'),
('Diametro de la muestra',    'diametro_muestra',     'COEFICIENTE_EXTENSIBILIDAD'),
('Fecha de Medición',         'fecha_medicion',       'COEFICIENTE_EXTENSIBILIDAD'),
('Hora de Medicion',          'hora_medicion',        'COEFICIENTE_EXTENSIBILIDAD');


INSERT INTO trn_coeficiente_extensibilidad
(periodo, archivo, fecha, analista)

VALUES
(2026, 'CE-2026-001', '2024-02-16', 1);

INSERT INTO trn_coeficiente_extensibilidad_muestras

(id_coeficiente_extensibilidad, idlab, rep, material, tipo, posicion, estado, ri)

VALUES
(1, '2001', 1, 1, 1, 1, 1, 0),
(1, '2002', 2, 1, 1, 2, 1, 0),
(1, '2003', 1, 1, 1, 3, 1, 0);

INSERT INTO trn_coeficiente_extensibilidad_resultado

(id_coeficiente_extensibilidad_muestras, id_analisis, resultado, estado)

VALUES
-- Muestra 1
INSERT INTO trn_coeficiente_extensibilidad_resultado

(id_coeficiente_extensibilidad_muestras, id_analisis, resultado, estado)

VALUES
-- Muestra 1
(1,(SELECT id FROM trn_analisis WHERE siglas='longitud_inicial' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'10.50', 1),
(1,(SELECT id FROM trn_analisis WHERE siglas='diametro_muestra' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'5.20', 1),
(1,(SELECT id FROM trn_analisis WHERE siglas='fecha_medicion' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'2024-02-22', 1),
(1,(SELECT id FROM trn_analisis WHERE siglas='hora_medicion' AND origen='COEFICIENTE_EXTENSIBILIDAD'), '10:30', 1);

-- Muestra 2
(2,(SELECT id FROM trn_analisis WHERE siglas='longitud_inicial' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'10.60', 1),
(2,(SELECT id FROM trn_analisis WHERE siglas='diametro_muestra' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'5.25', 1),
(2,(SELECT id FROM trn_analisis WHERE siglas='fecha_medicion' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'2024-02-22', 1),
(2,(SELECT id FROM trn_analisis WHERE siglas='hora_medicion' AND origen='COEFICIENTE_EXTENSIBILIDAD'), '10:45', 1);

-- Muestra 3
(3,(SELECT id FROM trn_analisis WHERE siglas='longitud_inicial' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'10.40', 1),
(3,(SELECT id FROM trn_analisis WHERE siglas='diametro_muestra' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'5.10', 1),
(3,(SELECT id FROM trn_analisis WHERE siglas='fecha_medicion' AND origen='COEFICIENTE_EXTENSIBILIDAD'),'2024-02-22', 1),
(3,(SELECT id FROM trn_analisis WHERE siglas='hora_medicion' AND origen='COEFICIENTE_EXTENSIBILIDAD'), '11:00', 1);

/* ============================================================
   CONTROLES
   ============================================================ */

INSERT INTO trn_controles (consecutivo, tipo, fecha, id_persona)
VALUES
(2001, 1, '2024-09-15', 1), -- Textura
(2002, 2, '2024-09-20', 1), -- Densidad Aparente
(2003, 3, '2024-09-20', 1), -- Densidad Partículas
(2004, 4, '2024-09-20', 1), -- Humedad Gravimétrica
(2005, 5, '2024-09-20', 1), -- Conductividad Hidráulica
(2006, 6, '2024-09-20', 1), -- Retención Humedad
(2007, 7, '2024-02-14', 1), -- Granulometría
(2008, 8, '2024-02-15', 1); -- Estabilidad Agregados

/* ============================================================
   RELACIÓN CONTROL → ARCHIVOS
   ============================================================ */

-- CONTROL 1 → TEXTURA (tenés 2 archivos)
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(1, 1),
(1, 2);

-- CONTROL 2 → DENSIDAD APARENTE
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(2, 1);

-- CONTROL 3 → DENSIDAD PARTÍCULAS
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(3, 1);

-- CONTROL 4 → HUMEDAD GRAVIMÉTRICA
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(4, 1);

-- CONTROL 5 → CONDUCTIVIDAD HIDRÁULICA
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(5, 1);

-- CONTROL 6 → RETENCIÓN HUMEDAD
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(6, 1);

-- CONTROL 7 → GRANULOMETRÍA
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(7, 1);

-- CONTROL 8 → ESTABILIDAD AGREGADOS
INSERT INTO trn_controles_lista (id_control, id_archivo)
VALUES
(8, 1);

SELECT * 
FROM tbl_bitacora 
ORDER BY fecha DESC;

