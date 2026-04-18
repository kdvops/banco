-- Schema reconciliado con la aplicacion PHP

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `app_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `app_db`;

DROP TABLE IF EXISTS `cripto_wallets`;
DROP TABLE IF EXISTS `cuentas_bancarias`;
DROP TABLE IF EXISTS `pagos_online`;
DROP TABLE IF EXISTS `servicios`;
DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(200) NOT NULL DEFAULT 'perfil.png',
  `cedula` varchar(11) NOT NULL DEFAULT '00000000000',
  `resena_personal` varchar(200) NOT NULL DEFAULT 'Sin descripcion personal.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_email` (`email`),
  UNIQUE KEY `uk_usuarios_numero` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `servicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `nombre_servicio` varchar(100) DEFAULT NULL,
  `resena` text,
  `enlace` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_servicios_usuario_id` (`usuario_id`),
  CONSTRAINT `fk_servicios_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cuentas_bancarias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `banco` enum('BHD','Ademi','Ban Reservas','Santa Cruz') DEFAULT NULL,
  `tipo_cuenta` enum('Ahorro','Corriente') DEFAULT NULL,
  `numero_cuenta` varchar(50) DEFAULT NULL,
  `imagen` varchar(200) NOT NULL DEFAULT 'images.png',
  PRIMARY KEY (`id`),
  KEY `idx_cuentas_usuario_id` (`usuario_id`),
  CONSTRAINT `fk_cuentas_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cripto_wallets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `moneda` varchar(50) DEFAULT NULL,
  `red` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `imagen` varchar(200) NOT NULL DEFAULT 'images.png',
  PRIMARY KEY (`id`),
  KEY `idx_cripto_usuario_id` (`usuario_id`),
  CONSTRAINT `fk_cripto_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pagos_online` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `plataforma` varchar(100) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pagos_usuario_id` (`usuario_id`),
  CONSTRAINT `fk_pagos_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `email`, `numero`, `password`, `fecha_registro`, `imagen`, `cedula`, `resena_personal`) VALUES
(1, 'Sheyla', 'Medina Ogando', 'sheyla@gmail.com', '8497071192', '$2y$10$RmFU70UY3ucwVMq/YzKiT.NNzXHA5FRz/kuLHFOwUhgs8h4RL/0nW', '2026-03-18 23:20:09', '1774664573_1.jpg', '22300904566', 'jjjj'),
(2, 'Juan Miguel', 'Alcala Grassal', 'klindo002@gmail.com', '8294908199', '$2y$10$HyiUssiqIALqc5q7F0l9huaxw6eFd1bRB/B5AodsQfLfZU1h6KdoG', '2026-03-27 02:19:59', 'perfil.png', '00000000000', 'Buena gente'),
(3, 'Juan Miguel', 'Alcala Grassal', 'jmalcala@gmail.com', '8294908100', '$2y$10$bcy9wTaZBe0dr7akXwerqu0ZPCZcYaLnwT2nx8yItpomao5mrmQPa', '2026-03-27 03:02:54', '1774582111_3.png', '22300904566', 'Buena gente');

INSERT INTO `servicios` (`id`, `usuario_id`, `imagen`, `nombre_servicio`, `resena`, `enlace`) VALUES
(4, 2, 'uploads/1774579291_RobloxScreenShot20260213_230006931.png', 'Servicio demo', 'Descripcion de ejemplo', 'http://localhost/banco/index.php'),
(5, 3, 'uploads/1774581751_RobloxScreenShot20260213_230006931.png', 'Portafolio', 'Contenido de muestra', 'http://localhost/banco/index.php'),
(6, 3, 'uploads/1774581842_RobloxScreenShot20260213_230031511.png', 'Soporte', 'Disponible para trabajos', 'http://localhost/banco/index.php'),
(14, 1, 'uploads/1775010298_acuario.jpg', 'Acuario', 'sss', 'http://localhost/banco/index.php');

INSERT INTO `cuentas_bancarias` (`id`, `usuario_id`, `banco`, `tipo_cuenta`, `numero_cuenta`, `imagen`) VALUES
(5, 1, 'BHD', 'Ahorro', '11111111', 'bhd.jpg'),
(7, 1, 'Ademi', 'Corriente', '8497071192', 'images.png'),
(9, 1, 'BHD', 'Ahorro', '22222222', 'bhd.jpg'),
(11, 1, 'Santa Cruz', 'Ahorro', '1111111', 'images.png');

INSERT INTO `cripto_wallets` (`id`, `usuario_id`, `moneda`, `red`, `direccion`, `imagen`) VALUES
(4, 1, 'ETHER', 'ERC20', '0xA1B2C3D4E5F6', 'images.png'),
(5, 1, 'BTC', 'BTC', 'bc1qexamplewallet123', 'images.png');

INSERT INTO `pagos_online` (`id`, `usuario_id`, `plataforma`, `enlace`) VALUES
(3, 1, 'PayPal', 'http://localhost/banco/index.php');

SET FOREIGN_KEY_CHECKS = 1;
