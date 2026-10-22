-- =====================================================================
--  Sistema de Impresiones y Comedor
--  Base de datos MySQL / MariaDB (XAMPP)
--  Importar con: /opt/lampp/bin/mysql -u root < database/database.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS impresiones_comedor
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE impresiones_comedor;

-- ---------------------------------------------------------------------
-- Usuarios que pueden entrar al sistema
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  usuario    VARCHAR(50)  NOT NULL UNIQUE,
  nombre     VARCHAR(100) NOT NULL,
  clave_hash VARCHAR(255) NOT NULL,
  creado     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Catálogo: servicios de impresión y platillos de comida
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS catalogo (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  area       ENUM('Impresiones','Comida') NOT NULL,
  categoria  VARCHAR(50)   NOT NULL,
  nombre     VARCHAR(100)  NOT NULL,
  precio     DECIMAL(10,2) NOT NULL,
  unidad     VARCHAR(30)   NOT NULL DEFAULT 'unidad',
  activo     TINYINT(1)    NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Pedidos (encabezado)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pedidos (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  cliente     VARCHAR(100)  NOT NULL,
  telefono    VARCHAR(20)   NULL,
  tipo        VARCHAR(30)   NOT NULL DEFAULT 'Para llevar',
  estado      VARCHAR(20)   NOT NULL DEFAULT 'Pendiente',
  notas       TEXT          NULL,
  total       DECIMAL(10,2) NOT NULL DEFAULT 0,
  usuario_id  INT           NULL,
  fecha       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Detalle de cada pedido (qué se pidió y cuánto)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pedido_detalle (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id        INT           NOT NULL,
  catalogo_id      INT           NOT NULL,
  cantidad         INT           NOT NULL,
  precio_unitario  DECIMAL(10,2) NOT NULL,
  subtotal         DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (pedido_id)   REFERENCES pedidos(id)  ON DELETE CASCADE,
  FOREIGN KEY (catalogo_id) REFERENCES catalogo(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Datos iniciales
-- ---------------------------------------------------------------------

-- Usuario administrador:  usuario = admin   contraseña = admin123
-- (¡Cambiar la contraseña después de instalar!)
INSERT INTO usuarios (usuario, nombre, clave_hash) VALUES
('admin', 'Administrador', '$2y$10$lAJuc9xGAv5IY3QJ3zm1Y.YN8zIpaXhtUn8rkmUISUk6txnEyV1Ia');

-- Catálogo de ejemplo (cambiar por los precios reales del negocio)
INSERT INTO catalogo (area, categoria, nombre, precio, unidad) VALUES
('Impresiones', 'Impresión', 'Impresión blanco y negro', 0.10, 'página'),
('Impresiones', 'Impresión', 'Impresión a color',        0.25, 'página'),
('Impresiones', 'Impresión', 'Impresión de fotografía',  0.75, 'unidad'),
('Impresiones', 'Copias',    'Fotocopia',                0.05, 'página'),
('Impresiones', 'Copias',    'Fotocopia doble cara',     0.08, 'hoja'),
('Impresiones', 'Servicios', 'Escaneo',                  0.25, 'página'),
('Impresiones', 'Servicios', 'Anillado',                 1.50, 'unidad'),
('Impresiones', 'Servicios', 'Plastificado',             0.75, 'unidad'),
('Comida',      'Almuerzos', 'Almuerzo del día',         2.50, 'plato'),
('Comida',      'Almuerzos', 'Pollo frito con papas',    3.00, 'plato'),
('Comida',      'Desayunos', 'Desayuno típico',          2.00, 'plato'),
('Comida',      'Snacks',    'Pupusa',                   0.60, 'unidad'),
('Comida',      'Snacks',    'Pan con pollo',            1.75, 'unidad'),
('Comida',      'Bebidas',   'Refresco natural',         0.50, 'vaso'),
('Comida',      'Bebidas',   'Gaseosa',                  0.75, 'unidad'),
('Comida',      'Bebidas',   'Café',                     0.50, 'taza');
