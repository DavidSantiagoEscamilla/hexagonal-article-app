-- ============================================================
-- Schema: DDD + Hexagonal Architecture - PHP Application
-- Ejercicio: Artículo (CRUDL)
-- ============================================================

CREATE DATABASE IF NOT EXISTS hexagonal_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hexagonal_app;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id          CHAR(36)        NOT NULL PRIMARY KEY,
    name        VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NOT NULL UNIQUE,
    password    VARCHAR(255)    NOT NULL,
    role        ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  DATETIME        NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de artículos
CREATE TABLE IF NOT EXISTS articles (
    id              CHAR(36)        NOT NULL PRIMARY KEY,
    marca           VARCHAR(100)    NOT NULL,
    modelo          VARCHAR(100)    NOT NULL,
    descripcion     TEXT            NOT NULL,
    categoria       VARCHAR(100)    NOT NULL,
    precio_venta    DECIMAL(10,2)   NOT NULL,
    precio_compra   DECIMAL(10,2)   NOT NULL,
    iva             DECIMAL(5,2)    NOT NULL DEFAULT 19.00,
    proveedor       VARCHAR(150)    NOT NULL,
    tienda          VARCHAR(150)    NOT NULL,
    cantidad        INT             NOT NULL DEFAULT 0,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      DATETIME        NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de tokens de recuperación de contraseña
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(150) NOT NULL,
    token       VARCHAR(255) NOT NULL UNIQUE,
    expires_at  DATETIME     NOT NULL,
    used        TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario admin por defecto (contraseña: Admin1234!)
INSERT INTO users (id, name, email, password, role) VALUES
('00000000-0000-0000-0000-000000000001', 'Administrador', 'admin@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Datos de ejemplo para artículos
INSERT INTO articles (id, marca, modelo, descripcion, categoria, precio_venta, precio_compra, iva, proveedor, tienda, cantidad) VALUES
('a1b2c3d4-0001-0001-0001-000000000001','Samsung','Galaxy S24','Smartphone de última generación con cámara de 200MP','Electrónica',3500000.00,2800000.00,19.00,'Samsung Colombia','TechStore Principal',15),
('a1b2c3d4-0002-0002-0002-000000000002','Nike','Air Max 270','Zapatillas deportivas con amortiguación Air','Calzado',350000.00,200000.00,19.00,'Nike Colombia','SportZone',30),
('a1b2c3d4-0003-0003-0003-000000000003','Apple','MacBook Pro M3','Laptop profesional con chip M3','Computadores',9800000.00,8500000.00,19.00,'Apple Authorized Dealer','iStore',5);
