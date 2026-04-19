-- Schema reconciliado con la aplicacion PHP

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `app_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `app_db`;

DROP TABLE IF EXISTS `cripto_wallets`;
DROP TABLE IF EXISTS `referencias_cripto`;
DROP TABLE IF EXISTS `cripto_activos`;
DROP TABLE IF EXISTS `cuentas_bancarias`;
DROP TABLE IF EXISTS `bancos`;
DROP TABLE IF EXISTS `pagos_online`;
DROP TABLE IF EXISTS `proveedores_pago_online`;
DROP TABLE IF EXISTS `paises`;
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

CREATE TABLE `paises` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `codigo_iso2` char(2) NOT NULL,
  `codigo_iso3` char(3) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_paises_nombre` (`nombre`),
  UNIQUE KEY `uk_paises_codigo_iso2` (`codigo_iso2`),
  UNIQUE KEY `uk_paises_codigo_iso3` (`codigo_iso3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bancos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pais_id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `icono` varchar(200) NOT NULL DEFAULT 'images.png',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_bancos_pais_id` (`pais_id`),
  UNIQUE KEY `uk_bancos_pais_nombre` (`pais_id`, `nombre`),
  CONSTRAINT `fk_bancos_pais`
    FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`)
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cuentas_bancarias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `banco_id` int NOT NULL,
  `tipo_cuenta` enum('Ahorro','Corriente') DEFAULT NULL,
  `numero_cuenta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cuentas_usuario_id` (`usuario_id`),
  KEY `idx_cuentas_banco_id` (`banco_id`),
  CONSTRAINT `fk_cuentas_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_cuentas_banco`
    FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cripto_activos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `red` varchar(50) NOT NULL,
  `icono` varchar(200) NOT NULL DEFAULT 'images.png',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cripto_activos_nombre_red` (`nombre`, `red`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `referencias_cripto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `tipo` varchar(40) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_referencias_cripto_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cripto_wallets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `cripto_activo_id` int NOT NULL,
  `referencia_cripto_id` int DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `memo_tag` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cripto_usuario_id` (`usuario_id`),
  KEY `idx_cripto_activo_id` (`cripto_activo_id`),
  KEY `idx_cripto_referencia_id` (`referencia_cripto_id`),
  CONSTRAINT `fk_cripto_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_cripto_activo`
    FOREIGN KEY (`cripto_activo_id`) REFERENCES `cripto_activos` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_cripto_referencia`
    FOREIGN KEY (`referencia_cripto_id`) REFERENCES `referencias_cripto` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `proveedores_pago_online` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `icono` varchar(200) NOT NULL DEFAULT 'images.png',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_proveedores_pago_online_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pagos_online` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `proveedor_pago_online_id` int NOT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pagos_usuario_id` (`usuario_id`),
  KEY `idx_pagos_proveedor_id` (`proveedor_pago_online_id`),
  CONSTRAINT `fk_pagos_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_pagos_proveedor`
    FOREIGN KEY (`proveedor_pago_online_id`) REFERENCES `proveedores_pago_online` (`id`)
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

INSERT INTO `paises` (`id`, `nombre`, `codigo_iso2`, `codigo_iso3`, `activo`) VALUES
(1, 'Republica Dominicana', 'DO', 'DOM', 1),
(2, 'Afganistan', 'AF', 'AFG', 1),
(3, 'Albania', 'AL', 'ALB', 1),
(4, 'Alemania', 'DE', 'DEU', 1),
(5, 'Andorra', 'AD', 'AND', 1),
(6, 'Angola', 'AO', 'AGO', 1),
(7, 'Antigua y Barbuda', 'AG', 'ATG', 1),
(8, 'Arabia Saudita', 'SA', 'SAU', 1),
(9, 'Argelia', 'DZ', 'DZA', 1),
(10, 'Argentina', 'AR', 'ARG', 1),
(11, 'Armenia', 'AM', 'ARM', 1),
(12, 'Australia', 'AU', 'AUS', 1),
(13, 'Austria', 'AT', 'AUT', 1),
(14, 'Azerbaiyan', 'AZ', 'AZE', 1),
(15, 'Bahamas', 'BS', 'BHS', 1),
(16, 'Barein', 'BH', 'BHR', 1),
(17, 'Bangladesh', 'BD', 'BGD', 1),
(18, 'Barbados', 'BB', 'BRB', 1),
(19, 'Belgica', 'BE', 'BEL', 1),
(20, 'Belice', 'BZ', 'BLZ', 1),
(21, 'Benin', 'BJ', 'BEN', 1),
(22, 'Bielorrusia', 'BY', 'BLR', 1),
(23, 'Birmania', 'MM', 'MMR', 1),
(24, 'Bolivia', 'BO', 'BOL', 1),
(25, 'Bosnia y Herzegovina', 'BA', 'BIH', 1),
(26, 'Botsuana', 'BW', 'BWA', 1),
(27, 'Brasil', 'BR', 'BRA', 1),
(28, 'Brunei', 'BN', 'BRN', 1),
(29, 'Bulgaria', 'BG', 'BGR', 1),
(30, 'Burkina Faso', 'BF', 'BFA', 1),
(31, 'Burundi', 'BI', 'BDI', 1),
(32, 'Butan', 'BT', 'BTN', 1),
(33, 'Cabo Verde', 'CV', 'CPV', 1),
(34, 'Camboya', 'KH', 'KHM', 1),
(35, 'Camerun', 'CM', 'CMR', 1),
(36, 'Canada', 'CA', 'CAN', 1),
(37, 'Catar', 'QA', 'QAT', 1),
(38, 'Chad', 'TD', 'TCD', 1),
(39, 'Chile', 'CL', 'CHL', 1),
(40, 'China', 'CN', 'CHN', 1),
(41, 'Chipre', 'CY', 'CYP', 1),
(42, 'Colombia', 'CO', 'COL', 1),
(43, 'Comoras', 'KM', 'COM', 1),
(44, 'Corea del Norte', 'KP', 'PRK', 1),
(45, 'Corea del Sur', 'KR', 'KOR', 1),
(46, 'Costa de Marfil', 'CI', 'CIV', 1),
(47, 'Costa Rica', 'CR', 'CRI', 1),
(48, 'Croacia', 'HR', 'HRV', 1),
(49, 'Cuba', 'CU', 'CUB', 1),
(50, 'Dinamarca', 'DK', 'DNK', 1),
(51, 'Dominica', 'DM', 'DMA', 1),
(52, 'Ecuador', 'EC', 'ECU', 1),
(53, 'Egipto', 'EG', 'EGY', 1),
(54, 'El Salvador', 'SV', 'SLV', 1),
(55, 'Emiratos Arabes Unidos', 'AE', 'ARE', 1),
(56, 'Eritrea', 'ER', 'ERI', 1),
(57, 'Eslovaquia', 'SK', 'SVK', 1),
(58, 'Eslovenia', 'SI', 'SVN', 1),
(59, 'Espana', 'ES', 'ESP', 1),
(60, 'Estados Unidos', 'US', 'USA', 1),
(61, 'Estado de Palestina', 'PS', 'PSE', 1),
(62, 'Estonia', 'EE', 'EST', 1),
(63, 'Esuatini', 'SZ', 'SWZ', 1),
(64, 'Etiopia', 'ET', 'ETH', 1),
(65, 'Filipinas', 'PH', 'PHL', 1),
(66, 'Finlandia', 'FI', 'FIN', 1),
(67, 'Fiyi', 'FJ', 'FJI', 1),
(68, 'Francia', 'FR', 'FRA', 1),
(69, 'Gabon', 'GA', 'GAB', 1),
(70, 'Gambia', 'GM', 'GMB', 1),
(71, 'Georgia', 'GE', 'GEO', 1),
(72, 'Ghana', 'GH', 'GHA', 1),
(73, 'Granada', 'GD', 'GRD', 1),
(74, 'Grecia', 'GR', 'GRC', 1),
(75, 'Guatemala', 'GT', 'GTM', 1),
(76, 'Guinea', 'GN', 'GIN', 1),
(77, 'Guinea Ecuatorial', 'GQ', 'GNQ', 1),
(78, 'Guinea-Bisau', 'GW', 'GNB', 1),
(79, 'Guyana', 'GY', 'GUY', 1),
(80, 'Haiti', 'HT', 'HTI', 1),
(81, 'Honduras', 'HN', 'HND', 1),
(82, 'Hungria', 'HU', 'HUN', 1),
(83, 'India', 'IN', 'IND', 1),
(84, 'Indonesia', 'ID', 'IDN', 1),
(85, 'Irak', 'IQ', 'IRQ', 1),
(86, 'Iran', 'IR', 'IRN', 1),
(87, 'Irlanda', 'IE', 'IRL', 1),
(88, 'Islandia', 'IS', 'ISL', 1),
(89, 'Islas Marshall', 'MH', 'MHL', 1),
(90, 'Islas Salomon', 'SB', 'SLB', 1),
(91, 'Israel', 'IL', 'ISR', 1),
(92, 'Italia', 'IT', 'ITA', 1),
(93, 'Jamaica', 'JM', 'JAM', 1),
(94, 'Japon', 'JP', 'JPN', 1),
(95, 'Jordania', 'JO', 'JOR', 1),
(96, 'Kazajistan', 'KZ', 'KAZ', 1),
(97, 'Kenia', 'KE', 'KEN', 1),
(98, 'Kirguistan', 'KG', 'KGZ', 1),
(99, 'Kiribati', 'KI', 'KIR', 1),
(100, 'Kuwait', 'KW', 'KWT', 1),
(101, 'Laos', 'LA', 'LAO', 1),
(102, 'Lesoto', 'LS', 'LSO', 1),
(103, 'Letonia', 'LV', 'LVA', 1),
(104, 'Libano', 'LB', 'LBN', 1),
(105, 'Liberia', 'LR', 'LBR', 1),
(106, 'Libia', 'LY', 'LBY', 1),
(107, 'Liechtenstein', 'LI', 'LIE', 1),
(108, 'Lituania', 'LT', 'LTU', 1),
(109, 'Luxemburgo', 'LU', 'LUX', 1),
(110, 'Macedonia del Norte', 'MK', 'MKD', 1),
(111, 'Madagascar', 'MG', 'MDG', 1),
(112, 'Malasia', 'MY', 'MYS', 1),
(113, 'Malaui', 'MW', 'MWI', 1),
(114, 'Maldivas', 'MV', 'MDV', 1),
(115, 'Mali', 'ML', 'MLI', 1),
(116, 'Malta', 'MT', 'MLT', 1),
(117, 'Marruecos', 'MA', 'MAR', 1),
(118, 'Mauricio', 'MU', 'MUS', 1),
(119, 'Mauritania', 'MR', 'MRT', 1),
(120, 'Mexico', 'MX', 'MEX', 1),
(121, 'Micronesia', 'FM', 'FSM', 1),
(122, 'Moldavia', 'MD', 'MDA', 1),
(123, 'Monaco', 'MC', 'MCO', 1),
(124, 'Mongolia', 'MN', 'MNG', 1),
(125, 'Montenegro', 'ME', 'MNE', 1),
(126, 'Mozambique', 'MZ', 'MOZ', 1),
(127, 'Namibia', 'NA', 'NAM', 1),
(128, 'Nauru', 'NR', 'NRU', 1),
(129, 'Nepal', 'NP', 'NPL', 1),
(130, 'Nicaragua', 'NI', 'NIC', 1),
(131, 'Niger', 'NE', 'NER', 1),
(132, 'Nigeria', 'NG', 'NGA', 1),
(133, 'Noruega', 'NO', 'NOR', 1),
(134, 'Nueva Zelanda', 'NZ', 'NZL', 1),
(135, 'Oman', 'OM', 'OMN', 1),
(136, 'Paises Bajos', 'NL', 'NLD', 1),
(137, 'Pakistan', 'PK', 'PAK', 1),
(138, 'Palaos', 'PW', 'PLW', 1),
(139, 'Panama', 'PA', 'PAN', 1),
(140, 'Papua Nueva Guinea', 'PG', 'PNG', 1),
(141, 'Paraguay', 'PY', 'PRY', 1),
(142, 'Peru', 'PE', 'PER', 1),
(143, 'Polonia', 'PL', 'POL', 1),
(144, 'Portugal', 'PT', 'PRT', 1),
(145, 'Reino Unido', 'GB', 'GBR', 1),
(146, 'Republica Centroafricana', 'CF', 'CAF', 1),
(147, 'Republica Checa', 'CZ', 'CZE', 1),
(148, 'Republica del Congo', 'CG', 'COG', 1),
(149, 'Republica Democratica del Congo', 'CD', 'COD', 1),
(150, 'Rumania', 'RO', 'ROU', 1),
(151, 'Rusia', 'RU', 'RUS', 1),
(152, 'Ruanda', 'RW', 'RWA', 1),
(153, 'Samoa', 'WS', 'WSM', 1),
(154, 'San Cristobal y Nieves', 'KN', 'KNA', 1),
(155, 'San Marino', 'SM', 'SMR', 1),
(156, 'San Vicente y las Granadinas', 'VC', 'VCT', 1),
(157, 'Santa Lucia', 'LC', 'LCA', 1),
(158, 'Santa Sede', 'VA', 'VAT', 1),
(159, 'Santo Tome y Principe', 'ST', 'STP', 1),
(160, 'Senegal', 'SN', 'SEN', 1),
(161, 'Serbia', 'RS', 'SRB', 1),
(162, 'Seychelles', 'SC', 'SYC', 1),
(163, 'Sierra Leona', 'SL', 'SLE', 1),
(164, 'Singapur', 'SG', 'SGP', 1),
(165, 'Siria', 'SY', 'SYR', 1),
(166, 'Somalia', 'SO', 'SOM', 1),
(167, 'Sri Lanka', 'LK', 'LKA', 1),
(168, 'Sudafrica', 'ZA', 'ZAF', 1),
(169, 'Sudan', 'SD', 'SDN', 1),
(170, 'Sudan del Sur', 'SS', 'SSD', 1),
(171, 'Suecia', 'SE', 'SWE', 1),
(172, 'Suiza', 'CH', 'CHE', 1),
(173, 'Surinam', 'SR', 'SUR', 1),
(174, 'Tailandia', 'TH', 'THA', 1),
(175, 'Tanzania', 'TZ', 'TZA', 1),
(176, 'Tayikistan', 'TJ', 'TJK', 1),
(177, 'Timor Oriental', 'TL', 'TLS', 1),
(178, 'Togo', 'TG', 'TGO', 1),
(179, 'Tonga', 'TO', 'TON', 1),
(180, 'Trinidad y Tobago', 'TT', 'TTO', 1),
(181, 'Tunez', 'TN', 'TUN', 1),
(182, 'Turkmenistan', 'TM', 'TKM', 1),
(183, 'Turquia', 'TR', 'TUR', 1),
(184, 'Tuvalu', 'TV', 'TUV', 1),
(185, 'Ucrania', 'UA', 'UKR', 1),
(186, 'Uganda', 'UG', 'UGA', 1),
(187, 'Uruguay', 'UY', 'URY', 1),
(188, 'Uzbekistan', 'UZ', 'UZB', 1),
(189, 'Vanuatu', 'VU', 'VUT', 1),
(190, 'Venezuela', 'VE', 'VEN', 1),
(191, 'Vietnam', 'VN', 'VNM', 1),
(192, 'Yemen', 'YE', 'YEM', 1),
(193, 'Yibuti', 'DJ', 'DJI', 1),
(194, 'Zambia', 'ZM', 'ZMB', 1),
(195, 'Zimbabue', 'ZW', 'ZWE', 1);

INSERT INTO `bancos` (`id`, `pais_id`, `nombre`, `icono`, `activo`) VALUES
(1, 1, 'Banco BHD', 'do-banco-bhd-official.png', 1),
(2, 1, 'Banco Ademi', 'do-banco-ademi-official.png', 1),
(3, 1, 'Banreservas', 'do-banreservas-official.png', 1),
(4, 1, 'Banco Santa Cruz', 'do-banco-santa-cruz-official.svg', 1),
(5, 1, 'Bancamerica', 'do-bancamerica.svg', 1),
(6, 1, 'Banco Activo Dominicana', 'do-banco-activo-official.png', 1),
(7, 1, 'Banco BDI', 'do-banco-bdi-official.svg', 1),
(8, 1, 'Banco Caribe', 'https://www.bancocaribe.com.do/logo.png', 1),
(9, 1, 'Banco del Progreso', 'do-banco-del-progreso.svg', 1),
(10, 1, 'Banco Lafise', 'do-banco-lafise-official.svg', 1),
(11, 1, 'Banco Lopez de Haro', 'do-banco-lopez-de-haro-official.png', 1),
(12, 1, 'Banco Popular Dominicano', 'https://popularenlinea.com/_catalogs/masterpage/popularenlinea/shared/images/BPD-logo.png', 1),
(13, 1, 'Banco Promerica', 'do-banco-promerica-official.webp', 1),
(14, 1, 'Banco Vimenca', 'do-banco-vimenca-official.svg', 1),
(15, 1, 'Banesco', 'do-banesco-official.svg', 1),
(16, 1, 'BellBank', 'do-bellbank.svg', 1),
(17, 1, 'Citibank', 'do-citibank.svg', 1),
(18, 1, 'Scotiabank', 'do-scotiabank-official.svg', 1),
(19, 1, 'Asociacion Bonao de Ahorros y Prestamos', 'do-asociacion-bonao-official.png', 1),
(20, 1, 'Asociacion Cibao de Ahorros y Prestamos', 'do-asociacion-cibao-official.png', 1),
(21, 1, 'Asociacion Duarte de Ahorros y Prestamos', 'https://adap.com.do/wp-content/themes/asocdu/images/logoasociacionduarteblanco.png', 1),
(22, 1, 'Asociacion La Nacional de Ahorros y Prestamos', 'do-asociacion-la-nacional-official.svg', 1),
(23, 1, 'Asociacion La Vega Real de Ahorros y Prestamos', 'do-asociacion-la-vega-real-official.jpg', 1),
(24, 1, 'Asociacion Maguana de Ahorros y Prestamos', 'do-asociacion-maguana.svg', 1),
(25, 1, 'Asociacion Mocana de Ahorros y Prestamos', 'do-asociacion-mocana.svg', 1),
(26, 1, 'Asociacion Peravia de Ahorros y Prestamos', 'https://asociacionperavia.com.do/wp-content/themes/peravia/images/logo.png', 1),
(27, 1, 'Asociacion Popular de Ahorros y Prestamos', 'do-asociacion-popular-official.png', 1),
(28, 1, 'Asociacion Romana de Ahorros y Prestamos', 'do-asociacion-romana-official.png', 1),
(29, 60, 'JPMorgan Chase Bank', 'images.png', 1),
(30, 60, 'Bank of America', 'images.png', 1),
(31, 60, 'Wells Fargo Bank', 'images.png', 1),
(32, 60, 'Citibank', 'images.png', 1),
(33, 60, 'Goldman Sachs Bank', 'images.png', 1),
(34, 60, 'Morgan Stanley Bank', 'images.png', 1),
(35, 145, 'HSBC', 'images.png', 1),
(36, 145, 'Barclays', 'images.png', 1),
(37, 145, 'Lloyds Bank', 'images.png', 1),
(38, 145, 'NatWest', 'images.png', 1),
(39, 145, 'Standard Chartered', 'images.png', 1),
(40, 36, 'Royal Bank of Canada', 'images.png', 1),
(41, 36, 'Toronto-Dominion Bank', 'images.png', 1),
(42, 36, 'Bank of Nova Scotia', 'images.png', 1),
(43, 36, 'Bank of Montreal', 'images.png', 1),
(44, 36, 'Canadian Imperial Bank of Commerce', 'images.png', 1),
(45, 120, 'BBVA Mexico', 'images.png', 1),
(46, 120, 'Banorte', 'images.png', 1),
(47, 120, 'Santander Mexico', 'images.png', 1),
(48, 27, 'Banco do Brasil', 'images.png', 1),
(49, 27, 'Caixa Economica Federal', 'images.png', 1),
(50, 27, 'Itau Unibanco', 'images.png', 1),
(51, 27, 'Banco Bradesco', 'images.png', 1),
(52, 27, 'Santander Brasil', 'images.png', 1),
(53, 59, 'Banco Santander', 'images.png', 1),
(54, 59, 'BBVA', 'images.png', 1),
(55, 59, 'CaixaBank', 'images.png', 1),
(56, 59, 'Banco Sabadell', 'images.png', 1),
(57, 68, 'BNP Paribas', 'images.png', 1),
(58, 68, 'Credit Agricole', 'images.png', 1),
(59, 68, 'Societe Generale', 'images.png', 1),
(60, 68, 'Groupe BPCE', 'images.png', 1),
(61, 4, 'Deutsche Bank', 'images.png', 1),
(62, 4, 'Commerzbank', 'images.png', 1),
(63, 4, 'KfW', 'images.png', 1),
(64, 172, 'UBS', 'images.png', 1),
(65, 172, 'Julius Baer', 'images.png', 1),
(66, 172, 'Raiffeisen Schweiz', 'images.png', 1),
(67, 172, 'PostFinance', 'images.png', 1),
(68, 94, 'Mitsubishi UFJ Bank', 'images.png', 1),
(69, 94, 'Sumitomo Mitsui Banking Corporation', 'images.png', 1),
(70, 94, 'Mizuho Bank', 'images.png', 1),
(71, 40, 'Industrial and Commercial Bank of China', 'images.png', 1),
(72, 40, 'Agricultural Bank of China', 'images.png', 1),
(73, 40, 'China Construction Bank', 'images.png', 1),
(74, 40, 'Bank of China', 'images.png', 1),
(75, 40, 'Bank of Communications', 'images.png', 1),
(76, 83, 'State Bank of India', 'images.png', 1),
(77, 83, 'HDFC Bank', 'images.png', 1),
(78, 83, 'ICICI Bank', 'images.png', 1),
(79, 83, 'Punjab National Bank', 'images.png', 1),
(80, 83, 'Axis Bank', 'images.png', 1),
(81, 12, 'Commonwealth Bank', 'images.png', 1),
(82, 12, 'Westpac', 'images.png', 1),
(83, 12, 'National Australia Bank', 'images.png', 1),
(84, 12, 'ANZ', 'images.png', 1),
(85, 164, 'DBS Bank', 'images.png', 1),
(86, 164, 'OCBC Bank', 'images.png', 1),
(87, 164, 'United Overseas Bank', 'images.png', 1),
(88, 55, 'First Abu Dhabi Bank', 'images.png', 1),
(89, 55, 'Emirates NBD', 'images.png', 1),
(90, 55, 'Abu Dhabi Commercial Bank', 'images.png', 1),
(91, 8, 'Al Rajhi Bank', 'images.png', 1),
(92, 8, 'Saudi National Bank', 'images.png', 1),
(93, 8, 'Riyad Bank', 'images.png', 1),
(94, 92, 'Intesa Sanpaolo', 'images.png', 1),
(95, 92, 'UniCredit', 'images.png', 1),
(96, 92, 'Banco BPM', 'images.png', 1),
(97, 144, 'Caixa Geral de Depositos', 'images.png', 1),
(98, 144, 'Millennium bcp', 'images.png', 1),
(99, 144, 'Novo Banco', 'images.png', 1),
(100, 10, 'Banco de la Nacion Argentina', 'images.png', 1),
(101, 10, 'Banco Galicia', 'images.png', 1),
(102, 10, 'BBVA Argentina', 'images.png', 1),
(103, 42, 'Bancolombia', 'images.png', 1),
(104, 42, 'Banco de Bogota', 'images.png', 1),
(105, 42, 'Davivienda', 'images.png', 1),
(106, 39, 'Banco de Chile', 'images.png', 1),
(107, 39, 'BancoEstado', 'images.png', 1),
(108, 39, 'Santander Chile', 'images.png', 1),
(109, 139, 'Banco General', 'images.png', 1),
(110, 139, 'Banistmo', 'images.png', 1),
(111, 168, 'Standard Bank', 'images.png', 1),
(112, 168, 'FirstRand Bank', 'images.png', 1),
(113, 168, 'Absa Bank', 'images.png', 1),
(114, 168, 'Nedbank', 'images.png', 1),
(115, 45, 'KB Kookmin Bank', 'images.png', 1),
(116, 45, 'Shinhan Bank', 'images.png', 1),
(117, 45, 'Hana Bank', 'images.png', 1),
(118, 45, 'Woori Bank', 'images.png', 1);

INSERT INTO `cuentas_bancarias` (`id`, `usuario_id`, `banco_id`, `tipo_cuenta`, `numero_cuenta`) VALUES
(5, 1, 1, 'Ahorro', '11111111'),
(7, 1, 2, 'Corriente', '8497071192'),
(9, 1, 1, 'Ahorro', '22222222'),
(11, 1, 4, 'Ahorro', '1111111');

INSERT INTO `cripto_activos` (`id`, `nombre`, `red`, `icono`, `activo`) VALUES
(1, 'BTC', 'BTC', 'btc.png', 1),
(2, 'ETHER', 'ERC20', 'ether.png', 1),
(3, 'USDT', 'TRC20', 'images.png', 1),
(4, 'BNB', 'BEP20', 'images.png', 1),
(5, 'USDC', 'ERC20', 'images.png', 1),
(6, 'SOL', 'SOL', 'images.png', 1),
(7, 'XRP', 'XRP Ledger', 'images.png', 1),
(8, 'ADA', 'Cardano', 'images.png', 1),
(9, 'TRX', 'TRC20', 'images.png', 1),
(10, 'DOGE', 'Dogecoin', 'images.png', 1),
(11, 'LTC', 'Litecoin', 'images.png', 1),
(12, 'MATIC', 'Polygon', 'images.png', 1);

INSERT INTO `referencias_cripto` (`id`, `nombre`, `tipo`, `activo`) VALUES
(1, 'MetaMask', 'wallet', 1),
(2, 'Trust Wallet', 'wallet', 1),
(3, 'Binance', 'exchange', 1),
(4, 'Coinbase', 'exchange', 1),
(5, 'Ledger', 'hardware_wallet', 1),
(6, 'Phantom', 'wallet', 1),
(7, 'Exodus', 'wallet', 1),
(8, 'Bybit', 'exchange', 1),
(9, 'Kraken', 'exchange', 1),
(10, 'OKX', 'exchange', 1),
(11, 'KuCoin', 'exchange', 1),
(12, 'Trezor', 'hardware_wallet', 1);

INSERT INTO `cripto_wallets` (`id`, `usuario_id`, `cripto_activo_id`, `referencia_cripto_id`, `direccion`, `memo_tag`) VALUES
(4, 1, 2, 1, '0xA1B2C3D4E5F6', NULL),
(5, 1, 1, 5, 'bc1qexamplewallet123', NULL);

INSERT INTO `proveedores_pago_online` (`id`, `nombre`, `icono`, `activo`) VALUES
(1, 'Zelle', 'images.png', 1),
(2, 'PayPal', 'images.png', 1),
(3, 'Payoneer', 'images.png', 1),
(4, 'Wise', 'images.png', 1),
(5, 'Stripe Payment Link', 'images.png', 1),
(6, 'Venmo', 'images.png', 1),
(7, 'Cash App', 'images.png', 1);

INSERT INTO `pagos_online` (`id`, `usuario_id`, `proveedor_pago_online_id`, `enlace`) VALUES
(3, 1, 2, 'http://localhost/banco/index.php');

SET FOREIGN_KEY_CHECKS = 1;
