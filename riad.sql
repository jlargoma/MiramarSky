-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-06-2019 a las 19:04:20
-- Versión del servidor: 5.6.34-log
-- Versión de PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `riad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agents_rooms`
--

CREATE TABLE `agents_rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agency_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `answers`
--

CREATE TABLE `answers` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `rate` text COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `room_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bail`
--

CREATE TABLE `bail` (
  `id_book` int(10) UNSIGNED NOT NULL,
  `import_in` decimal(8,2) NOT NULL,
  `date_in` date NOT NULL,
  `comment_in` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `import_out` decimal(8,2) NOT NULL,
  `date_out` date NOT NULL,
  `comment_out` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank`
--

CREATE TABLE `bank` (
  `id` int(10) UNSIGNED NOT NULL,
  `concept` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `import` decimal(15,2) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `typePayment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 = ingresa; 1 = paga',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `book`
--

CREATE TABLE `book` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `start` date NOT NULL,
  `finish` date NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ff_status` tinyint(1) NOT NULL DEFAULT '0',
  `ff_request_id` int(11) DEFAULT NULL,
  `book_comments` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_book` int(11) NOT NULL,
  `pax` int(11) NOT NULL,
  `nigths` int(11) NOT NULL,
  `agency` int(11) NOT NULL,
  `PVPAgencia` decimal(8,2) NOT NULL,
  `sup_limp` decimal(8,2) NOT NULL,
  `cost_limp` int(11) NOT NULL,
  `sup_park` decimal(8,2) NOT NULL,
  `type_park` int(11) NOT NULL,
  `cost_park` decimal(8,2) NOT NULL,
  `type_luxury` int(11) NOT NULL,
  `sup_lujo` decimal(8,2) NOT NULL,
  `cost_lujo` decimal(8,2) NOT NULL,
  `cost_apto` decimal(8,2) NOT NULL,
  `cost_total` decimal(8,2) NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `total_ben` decimal(8,2) NOT NULL,
  `extraPrice` decimal(8,2) NOT NULL,
  `extraCost` decimal(8,2) NOT NULL,
  `extra` decimal(8,2) NOT NULL,
  `inc_percent` decimal(4,2) NOT NULL,
  `ben_jorge` decimal(8,2) NOT NULL,
  `ben_jaime` decimal(8,2) NOT NULL,
  `send` int(11) NOT NULL,
  `statusCobro` int(11) NOT NULL,
  `real_price` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `schedule` int(11) NOT NULL,
  `scheduleOut` int(11) NOT NULL DEFAULT '12',
  `real_pax` int(11) NOT NULL,
  `book_owned_comments` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `promociones` decimal(8,2) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `book`
--

INSERT INTO `book` (`id`, `user_id`, `customer_id`, `room_id`, `start`, `finish`, `comment`, `ff_status`, `ff_request_id`, `book_comments`, `type_book`, `pax`, `nigths`, `agency`, `PVPAgencia`, `sup_limp`, `cost_limp`, `sup_park`, `type_park`, `cost_park`, `type_luxury`, `sup_lujo`, `cost_lujo`, `cost_apto`, `cost_total`, `total_price`, `total_ben`, `extraPrice`, `extraCost`, `extra`, `inc_percent`, `ben_jorge`, `ben_jaime`, `send`, `statusCobro`, `real_price`, `created_at`, `updated_at`, `schedule`, `scheduleOut`, `real_pax`, `book_owned_comments`, `promociones`, `enable`) VALUES
(1, 1, 1, 6, '2019-06-02', '2019-06-19', '', 0, NULL, '', 2, 6, 30, 0, 0.00, 40.00, 30, 0.00, 2, 0.00, 2, 0.00, 0.00, 750.00, 785.00, 4840.00, 4055.00, 5.00, 5.00, 0.00, 83.78, 2635.75, 1419.25, 0, 0, 4840.00, '2019-05-27 17:37:57', '2019-06-02 16:05:03', 0, 0, 6, '', 0.00, 1),
(2, 1, 2, 1, '2019-06-19', '2019-07-21', '', 0, NULL, '', 2, 6, 6, 0, 0.00, 40.00, 30, 180.00, 1, 60.00, 2, 0.00, 0.00, 150.00, 245.00, 1120.00, 875.00, 0.00, 0.00, 0.00, 78.13, 568.75, 306.25, 0, 0, 1120.00, '2019-06-01 15:23:38', '2019-06-02 16:01:31', 0, 0, 6, '', 0.00, 1),
(6, 1, 12, 6, '2019-06-24', '2019-06-28', '', 0, NULL, '', 0, 4, 4, 0, 0.00, 40.00, 30, 120.00, 1, 40.00, 1, 50.00, 40.00, 130.00, 210.00, 730.00, 520.00, 0.00, 0.00, 0.00, 71.23, 338.00, 182.00, 0, 0, 730.00, '2019-06-03 15:55:35', '2019-06-03 16:03:14', 0, 12, 4, '', 0.00, 1),
(7, 1, 13, 6, '2019-06-24', '2019-06-28', '', 0, NULL, '', 0, 4, 4, 0, 0.00, 40.00, 30, 120.00, 1, 40.00, 1, 50.00, 40.00, 130.00, 210.00, 730.00, 520.00, 0.00, 0.00, 0.00, 71.23, 338.00, 182.00, 0, 0, 730.00, '2019-06-03 15:56:54', '2019-06-03 16:03:17', 0, 12, 4, '', 0.00, 1),
(8, 1, 14, 6, '2019-06-24', '2019-06-28', '', 0, NULL, '', 0, 4, 4, 0, 0.00, 40.00, 30, 120.00, 1, 40.00, 1, 50.00, 40.00, 130.00, 210.00, 730.00, 520.00, 0.00, 0.00, 0.00, 71.23, 338.00, 182.00, 0, 0, 730.00, '2019-06-03 15:57:16', '2019-06-03 16:03:20', 0, 12, 4, '', 0.00, 1),
(9, 1, 15, 6, '2019-06-24', '2019-06-28', '', 0, NULL, '', 3, 4, 4, 0, 0.00, 40.00, 30, 120.00, 1, 40.00, 1, 50.00, 40.00, 130.00, 210.00, 730.00, 520.00, 0.00, 0.00, 0.00, 71.23, 338.00, 182.00, 0, 0, 730.00, '2019-06-03 15:58:31', '2019-06-03 15:58:31', 0, 12, 4, '', 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bookingnotification`
--

CREATE TABLE `bookingnotification` (
  `id` int(10) UNSIGNED NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cashbox`
--

CREATE TABLE `cashbox` (
  `id` int(10) UNSIGNED NOT NULL,
  `concept` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `import` decimal(15,2) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `typePayment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 = ingresa; 1 = paga',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `cashbox`
--

INSERT INTO `cashbox` (`id`, `concept`, `date`, `import`, `comment`, `typePayment`, `type`, `created_at`, `updated_at`) VALUES
(1, 'demo 3', '2019-05-29', 456.00, '', '0', 1, '2019-05-29 18:46:14', '2019-05-29 18:46:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `code_country` varchar(2) NOT NULL,
  `city` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classes_prices`
--

CREATE TABLE `classes_prices` (
  `id` int(11) NOT NULL,
  `type` tinytext NOT NULL,
  `subtype` tinytext,
  `days` int(2) NOT NULL,
  `pax` int(2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commissions`
--

CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
  `type` tinytext NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

CREATE TABLE `countries` (
  `code` varchar(2) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `countries`
--

INSERT INTO `countries` (`code`, `country`) VALUES
('FR', 'France'),
('DE', 'Germany'),
('SE', 'Sweden'),
('IT', 'Italy'),
('ES', 'Spain'),
('GB', 'United Kingdom'),
('PT', 'Portugal'),
('NO', 'Norway');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `DNI` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `name`, `email`, `phone`, `comments`, `created_at`, `updated_at`, `DNI`, `address`, `country`, `city`) VALUES
(1, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-05-27 17:37:57', '2019-05-27 17:37:57', 'X8026232Z', 'Arroyo de las pilillas 22 bajo c', 'ES', ''),
(2, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-01 15:23:38', '2019-06-01 15:23:38', 'X8026232Z', 'Arroyo de las pilillas 22 bajo c', 'ES', ''),
(3, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:40:02', '2019-06-03 15:40:02', 'X8026232Z', '', '', ''),
(4, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:49:28', '2019-06-03 15:49:28', 'X8026232Z', '', '', ''),
(5, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:50:15', '2019-06-03 15:50:15', 'X8026232Z', '', '', ''),
(6, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:50:21', '2019-06-03 15:50:21', 'X8026232Z', '', '', ''),
(7, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:51:02', '2019-06-03 15:51:02', 'X8026232Z', '', '', ''),
(8, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:51:09', '2019-06-03 15:51:09', 'X8026232Z', '', '', ''),
(9, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:51:49', '2019-06-03 15:51:49', 'X8026232Z', '', '', ''),
(10, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:52:24', '2019-06-03 15:52:24', 'X8026232Z', '', '', ''),
(11, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:53:09', '2019-06-03 15:53:09', 'X8026232Z', '', '', ''),
(12, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:55:35', '2019-06-03 15:55:35', 'X8026232Z', '', '', ''),
(13, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:56:54', '2019-06-03 15:56:54', 'X8026232Z', '', '', ''),
(14, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:57:16', '2019-06-03 15:57:16', 'X8026232Z', '', '', ''),
(15, 1, 'ian avila', 'iankurosaki17@gmail.com', '622411066', NULL, '2019-06-03 15:58:31', '2019-06-03 15:58:31', 'X8026232Z', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dayssecondpay`
--

CREATE TABLE `dayssecondpay` (
  `id` int(10) UNSIGNED NOT NULL,
  `days` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `dayssecondpay`
--

INSERT INTO `dayssecondpay` (`id`, `days`, `created_at`, `updated_at`) VALUES
(1, 16, NULL, '2018-09-23 10:17:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `concept` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `import` decimal(10,2) NOT NULL,
  `typePayment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `PayFor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extras`
--

CREATE TABLE `extras` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `cost` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `apartment_size` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `extras`
--

INSERT INTO `extras` (`id`, `name`, `price`, `cost`, `created_at`, `updated_at`, `apartment_size`) VALUES
(1, 'Limpieza Dos Dorm', 40.00, 30.00, '2017-09-18 06:58:37', '2019-05-26 16:38:34', 2),
(2, 'Limpieza Estudio', 40.00, 30.00, '2017-09-18 06:58:56', '2019-05-26 16:38:39', 1),
(3, 'Limpieza Gran ocupación', 40.00, 30.00, '2017-09-18 06:59:17', '2019-05-26 16:38:41', 3),
(4, 'Obsequio', 0.00, 0.00, '2017-11-20 09:43:20', '2019-06-01 13:59:48', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extrasbooks`
--

CREATE TABLE `extrasbooks` (
  `id` int(10) UNSIGNED NOT NULL,
  `extra_id` int(10) UNSIGNED NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fianza`
--

CREATE TABLE `fianza` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `customer_id` varchar(100) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `amount` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forfaits_calendar`
--

CREATE TABLE `forfaits_calendar` (
  `id` int(11) NOT NULL,
  `date` int(8) NOT NULL,
  `type` tinytext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forfaits_prices`
--

CREATE TABLE `forfaits_prices` (
  `id` int(11) NOT NULL,
  `type` tinytext NOT NULL,
  `days` int(11) DEFAULT NULL,
  `price_spring` decimal(10,2) DEFAULT NULL,
  `price_promo` decimal(10,2) DEFAULT NULL,
  `price_season_low` decimal(10,2) DEFAULT NULL,
  `price_season_high` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ical_import`
--

CREATE TABLE `ical_import` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incomes`
--

CREATE TABLE `incomes` (
  `id` int(10) UNSIGNED NOT NULL,
  `concept` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `import` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `incomes`
--

INSERT INTO `incomes` (`id`, `concept`, `date`, `import`, `created_at`, `updated_at`) VALUES
(1, 'INGRESOS EXTRAORDINARIOS', '2019-05-29', 500.00, '2019-05-29 19:01:10', '2019-05-29 19:01:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postalcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nif` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `total_price` decimal(8,2) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `finish` date DEFAULT NULL,
  `name_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nif_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_business` int(11) DEFAULT NULL,
  `zip_code_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_images`
--

CREATE TABLE `log_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `room_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2017_02_22_102845_create_prices_table', 1),
('2017_02_23_075549_create_rooms_table', 1),
('2017_02_23_080546_create_customers_table', 1),
('2017_02_23_081948_create_seasons_table', 1),
('2017_02_23_082852_create_type_payments_table', 1),
('2017_02_23_083029_create_payments_pro_table', 1),
('2017_05_08_063536_create_payments_table', 1),
('2017_05_08_063737_create_book_table', 1),
('2017_05_10_102438_create_typeseasons_table', 1),
('2017_05_10_120235_create_sizerooms_table', 1),
('2017_05_10_205403_update_tabless_table', 1),
('2017_06_06_095940_create_extras_table', 1),
('2017_06_06_095959_create_extrasbooks_table', 1),
('2017_07_03_141307_create_typeApto_table', 1),
('2017_07_24_090052_update_rooms_order', 2),
('2017_07_31_152246_create_Bail_table', 2),
('2017_10_19_095805_create_bookingNotification_table', 3),
('2017_10_24_071516_addBookSchedules', 4),
('2017_10_24_072327_addBookSchedules2', 4),
('2017_10_25_091100_update_roomsPark_table', 4),
('2017_10_26_065020_update_bookPax_table', 5),
('2017_10_31_073407_addSeasonsDays', 6),
('2017_11_14_081101_update_typeApto_table', 7),
('2017_11_15_073938_update_customers_table', 8),
('2017_11_15_101326_update_customer_country_city', 9),
('2017_11_22_075348_solicitudes', 10),
('2017_11_29_132333_store', 11),
('2017_12_12_082742_create_encuestas', 12),
('2017_12_13_091918_update_encuestas', 13),
('2018_01_02_122822_update_usuarios', 13),
('2018_01_03_084326_update_book_commentOwned', 13),
('2018_01_04_190701_add_indices_multiple_tables', 14),
('2018_01_15_134516_update_book_promociones', 15),
('2018_01_29_235132_create_icalImport', 16),
('2018_02_13_204909_update_rooms_table', 17),
('2018_02_14_144022_create_expenses', 18),
('2018_02_17_191121_create_percent_benef', 19),
('2018_02_19_125422_create_incomes', 20),
('2018_02_20_093854_create_cashbox', 21),
('2018_02_21_151312_create_bank', 22),
('2018_02_22_141345_create_log_sendimagesroom', 23),
('2018_02_22_155224_create_rules_stripe', 24),
('2018_02_24_150115_create_invoices', 25),
('2018_03_18_113905_create_days_to_second_pay', 26),
('2018_03_19_192358_update_invoices', 27),
('2018_03_21_202415_update_user_business_data', 28),
('2018_03_29_153627_add_apartment_size_column_to_extras_table', 29),
('2018_09_15_120003_update_seasons', 30),
('2018_09_22_112913_create_special_segments', 31),
('2018_09_22_142948_create_years_table', 31),
('2018_09_24_145402_update_rooms_description', 32),
('2018_10_01_200739_update_user_iban', 33),
('2018_10_08_153023_create_book_send_pictures_email', 34),
('2018_10_14_224839_update_user_accept_stripe', 35),
('2018_10_29_201954_create_agents_users', 36),
('2018_12_17_220242_update_agents_rooms', 37),
('2019_05_27_170859_update_years', 38),
('2019_06_01_155543_create_setting_table', 39),
('2019_06_03_000953_update_rooms_with_garage', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `email_customer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_customer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_customer` int(15) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `datePayment` date NOT NULL,
  `import` decimal(8,2) NOT NULL,
  `type` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paymentspro`
--

CREATE TABLE `paymentspro` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `datePayment` date NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `import` decimal(8,2) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `percent`
--

CREATE TABLE `percent` (
  `id` int(10) UNSIGNED NOT NULL,
  `percent` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `percent`
--

INSERT INTO `percent` (`id`, `percent`, `created_at`, `updated_at`) VALUES
(1, 15, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prices`
--

CREATE TABLE `prices` (
  `id` int(10) UNSIGNED NOT NULL,
  `season` int(10) UNSIGNED NOT NULL,
  `occupation` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `cost` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `prices`
--

INSERT INTO `prices` (`id`, `season`, `occupation`, `price`, `cost`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 80.00, 25.00, NULL, NULL),
(2, 3, 3, 90.00, 25.00, NULL, NULL),
(3, 3, 4, 100.00, 25.00, NULL, NULL),
(4, 3, 5, 115.00, 25.00, NULL, NULL),
(5, 3, 6, 130.00, 25.00, NULL, NULL),
(6, 3, 7, 140.00, 25.00, NULL, NULL),
(7, 3, 8, 150.00, 25.00, NULL, NULL),
(8, 3, 9, 160.00, 25.00, NULL, NULL),
(9, 3, 10, 170.00, 25.00, NULL, NULL),
(10, 2, 2, 90.00, 25.00, NULL, NULL),
(11, 2, 3, 100.00, 25.00, NULL, NULL),
(12, 2, 4, 120.00, 25.00, NULL, NULL),
(13, 2, 5, 135.00, 25.00, NULL, NULL),
(14, 2, 6, 150.00, 25.00, NULL, NULL),
(15, 2, 7, 160.00, 25.00, NULL, NULL),
(16, 2, 8, 170.00, 25.00, NULL, NULL),
(17, 2, 9, 180.00, 25.00, NULL, NULL),
(18, 2, 10, 190.00, 25.00, NULL, NULL),
(19, 1, 2, 100.00, 25.00, NULL, NULL),
(20, 1, 3, 110.00, 25.00, NULL, NULL),
(21, 1, 4, 130.00, 25.00, NULL, NULL),
(22, 1, 5, 145.00, 25.00, NULL, NULL),
(23, 1, 6, 160.00, 25.00, NULL, NULL),
(24, 1, 7, 170.00, 25.00, NULL, NULL),
(25, 1, 8, 180.00, 25.00, NULL, NULL),
(26, 1, 9, 190.00, 25.00, NULL, NULL),
(27, 1, 10, 200.00, 25.00, NULL, NULL),
(28, 4, 2, 160.00, 25.00, NULL, NULL),
(29, 4, 3, 180.00, 25.00, NULL, NULL),
(30, 4, 4, 200.00, 25.00, NULL, NULL),
(31, 4, 5, 230.00, 25.00, NULL, NULL),
(32, 4, 6, 260.00, 25.00, NULL, NULL),
(33, 4, 7, 280.00, 25.00, NULL, NULL),
(34, 4, 8, 300.00, 25.00, NULL, NULL),
(35, 4, 9, 320.00, 25.00, NULL, NULL),
(36, 4, 10, 240.00, 25.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `unity` double NOT NULL,
  `price` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_orders`
--

CREATE TABLE `products_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

CREATE TABLE `questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rooms`
--

CREATE TABLE `rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nameRoom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `owned` int(10) UNSIGNED NOT NULL,
  `sizeApto` int(10) UNSIGNED NOT NULL,
  `typeApto` int(10) UNSIGNED NOT NULL,
  `minOcu` int(11) NOT NULL,
  `maxOcu` int(11) NOT NULL,
  `luxury` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `parking` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locker` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profit_percent` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `num_garage` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `nameRoom`, `owned`, `sizeApto`, `typeApto`, `minOcu`, `maxOcu`, `luxury`, `created_at`, `updated_at`, `order`, `state`, `parking`, `locker`, `profit_percent`, `description`, `num_garage`) VALUES
(1, 'N001', 'N001', 1, 1, 3, 4, 4, 0, '2019-05-26 16:35:09', '2019-06-02 22:19:37', 1, 1, '', '', 0, 'Lorem ipsum dolor sit amet consectetur adipiscing elit, nullam lacus nisl aenean massa aptent a in, at neque malesuada cras sem convallis. Mollis nisi libero enim dapibus auctor tristique, habitasse sagittis cras posuere dignissim nostra ultricies, phasellus inceptos placerat vel fringilla. Proin etiam facilisi hac varius cum euismod pulvinar, fermentum nibh condimentum tortor turpis praesent porttitor felis, eu fames est quam torquent habitant purus, urna facilisis semper in maecenas arcu.', 3),
(2, 'N002', 'N002', 1, 1, 3, 2, 2, 0, '2019-05-26 16:35:37', '2019-05-26 16:39:31', 2, 1, '', '', 0, 'Lorem ipsum dolor sit amet consectetur adipiscing elit, nullam lacus nisl aenean massa aptent a in, at neque malesuada cras sem convallis. Mollis nisi libero enim dapibus auctor tristique, habitasse sagittis cras posuere dignissim nostra ultricies, phasellus inceptos placerat vel fringilla. Proin etiam facilisi hac varius cum euismod pulvinar, fermentum nibh condimentum tortor turpis praesent porttitor felis, eu fames est quam torquent habitant purus, urna facilisis semper in maecenas arcu.', 1),
(3, 'N003', 'N003', 1, 2, 3, 6, 8, 0, '2019-05-26 16:36:00', '2019-05-26 16:39:35', 3, 1, '', '', 0, 'Lorem ipsum dolor sit amet consectetur adipiscing elit, nullam lacus nisl aenean massa aptent a in, at neque malesuada cras sem convallis. Mollis nisi libero enim dapibus auctor tristique, habitasse sagittis cras posuere dignissim nostra ultricies, phasellus inceptos placerat vel fringilla. Proin etiam facilisi hac varius cum euismod pulvinar, fermentum nibh condimentum tortor turpis praesent porttitor felis, eu fames est quam torquent habitant purus, urna facilisis semper in maecenas arcu.', 1),
(4, 'N004', 'N004', 1, 2, 3, 6, 8, 0, '2019-05-26 16:36:18', '2019-05-26 16:39:38', 4, 1, '', '', 0, 'Lorem ipsum dolor sit amet consectetur adipiscing elit, nullam lacus nisl aenean massa aptent a in, at neque malesuada cras sem convallis. Mollis nisi libero enim dapibus auctor tristique, habitasse sagittis cras posuere dignissim nostra ultricies, phasellus inceptos placerat vel fringilla. Proin etiam facilisi hac varius cum euismod pulvinar, fermentum nibh condimentum tortor turpis praesent porttitor felis, eu fames est quam torquent habitant purus, urna facilisis semper in maecenas arcu.', 1),
(5, 'N005', 'N005', 1, 2, 3, 6, 8, 0, '2019-05-26 16:36:38', '2019-05-26 16:39:42', 5, 1, '', '', 0, 'Lorem ipsum dolor sit amet consectetur adipiscing elit, nullam lacus nisl aenean massa aptent a in, at neque malesuada cras sem convallis. Mollis nisi libero enim dapibus auctor tristique, habitasse sagittis cras posuere dignissim nostra ultricies, phasellus inceptos placerat vel fringilla. Proin etiam facilisi hac varius cum euismod pulvinar, fermentum nibh condimentum tortor turpis praesent porttitor felis, eu fames est quam torquent habitant purus, urna facilisis semper in maecenas arcu.', 1),
(6, 'N006', 'N006', 1, 2, 3, 4, 4, 0, '2019-05-26 16:37:00', '2019-05-26 16:39:46', 6, 1, '', '', 0, 'Lorem ipsum dolor sit amet consectetur adipiscing elit, nullam lacus nisl aenean massa aptent a in, at neque malesuada cras sem convallis. Mollis nisi libero enim dapibus auctor tristique, habitasse sagittis cras posuere dignissim nostra ultricies, phasellus inceptos placerat vel fringilla. Proin etiam facilisi hac varius cum euismod pulvinar, fermentum nibh condimentum tortor turpis praesent porttitor felis, eu fames est quam torquent habitant purus, urna facilisis semper in maecenas arcu.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rules_stripe`
--

CREATE TABLE `rules_stripe` (
  `id` int(10) UNSIGNED NOT NULL,
  `percent` int(11) NOT NULL,
  `numDays` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rules_stripe`
--

INSERT INTO `rules_stripe` (`id`, `percent`, `numDays`, `created_at`, `updated_at`) VALUES
(1, 50, 1, NULL, '2018-03-14 18:19:36'),
(2, 0, 0, NULL, '2018-03-14 18:19:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seasondays`
--

CREATE TABLE `seasondays` (
  `id` int(10) UNSIGNED NOT NULL,
  `numDays` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `seasondays`
--

INSERT INTO `seasondays` (`id`, `numDays`, `created_at`, `updated_at`) VALUES
(1, 120, NULL, '2018-03-22 16:21:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seasons`
--

CREATE TABLE `seasons` (
  `id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `finish_date` date NOT NULL,
  `type` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `seasons`
--

INSERT INTO `seasons` (`id`, `start_date`, `finish_date`, `type`, `created_at`, `updated_at`) VALUES
(1, '2019-01-01', '2019-01-06', 1, '2019-05-27 14:52:05', '2019-05-27 14:52:05'),
(2, '2019-01-07', '2019-02-28', 3, '2019-05-27 14:52:43', '2019-05-27 14:52:43'),
(3, '2019-03-01', '2019-03-05', 1, '2019-05-27 14:53:20', '2019-05-27 14:53:20'),
(4, '2019-03-06', '2019-03-31', 3, '2019-05-27 14:53:36', '2019-05-27 14:53:36'),
(5, '2019-04-01', '2019-04-17', 2, '2019-05-27 14:53:50', '2019-05-27 14:53:50'),
(6, '2019-04-18', '2019-04-21', 4, '2019-05-27 14:54:03', '2019-05-27 14:54:03'),
(7, '2019-04-22', '2019-04-30', 2, '2019-05-27 14:54:28', '2019-05-27 14:54:28'),
(8, '2019-05-01', '2019-06-30', 1, '2019-05-27 14:54:50', '2019-05-27 14:54:50'),
(9, '2019-07-01', '2019-08-31', 2, '2019-05-27 14:55:16', '2019-05-27 14:55:16'),
(10, '2019-09-01', '2019-10-31', 1, '2019-05-27 14:55:36', '2019-05-27 14:55:36'),
(11, '2019-11-01', '2019-11-30', 2, '2019-05-27 14:55:50', '2019-05-27 14:55:50'),
(12, '2019-12-01', '2019-12-05', 3, '2019-05-27 14:56:56', '2019-05-27 14:56:56'),
(13, '2019-12-06', '2019-12-09', 1, '2019-05-27 14:57:15', '2019-05-27 14:57:15'),
(14, '2019-12-10', '2019-12-25', 3, '2019-05-27 14:57:34', '2019-05-27 14:57:34'),
(15, '2019-12-26', '2019-12-31', 1, '2019-05-27 14:57:47', '2019-05-27 14:57:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `name`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'Cost Sup Park', 'parking_book_cost', '10', '2019-06-01 14:53:26', '2019-06-01 15:18:34'),
(2, 'PVP Sup Park', 'parking_book_price', '30', '2019-06-01 14:54:10', '2019-06-01 15:18:38'),
(3, 'Cost Sup Lujo', 'luxury_book_cost', '40', '2019-06-01 14:54:18', '2019-06-01 14:54:18'),
(4, 'PVP Sup Lujo', 'luxury_book_price', '50', '2019-06-01 14:55:44', '2019-06-01 14:55:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sizerooms`
--

CREATE TABLE `sizerooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sizerooms`
--

INSERT INTO `sizerooms` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Estudio', '2017-06-01 02:43:36', '2017-06-01 02:43:36'),
(2, 'Dos Dorm', '2017-06-01 02:43:41', '2017-06-01 02:43:41'),
(3, 'Grande(10 px)', '2017-07-03 12:42:00', '2017-07-03 12:42:00'),
(4, 'Grande (12 pax)', '2017-12-05 15:01:30', '2017-12-05 15:01:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(11) NOT NULL,
  `start` date NOT NULL,
  `finish` date NOT NULL,
  `cc_pan` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_name` tinytext COLLATE utf8_unicode_ci,
  `cc_expiry` tinytext COLLATE utf8_unicode_ci,
  `cc_cvc` tinyint(3) DEFAULT NULL,
  `request_forfaits` text COLLATE utf8_unicode_ci,
  `request_material` text COLLATE utf8_unicode_ci,
  `request_classes` text COLLATE utf8_unicode_ci,
  `request_prices` tinytext COLLATE utf8_unicode_ci,
  `comments` text COLLATE utf8_unicode_ci,
  `commissions` tinytext COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `enable` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_productos`
--

CREATE TABLE `solicitudes_productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_solicitud` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `special_segments`
--

CREATE TABLE `special_segments` (
  `id` int(10) UNSIGNED NOT NULL,
  `start` date NOT NULL,
  `finish` date NOT NULL,
  `minDays` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `typeapto`
--

CREATE TABLE `typeapto` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `PercentJorge` int(11) NOT NULL,
  `PercentJaime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `typeapto`
--

INSERT INTO `typeapto` (`id`, `name`, `created_at`, `updated_at`, `PercentJorge`, `PercentJaime`) VALUES
(1, 'Riesgo', NULL, '2017-11-16 15:51:04', 80, 20),
(2, 'Propietario', '2017-07-03 12:42:18', '2017-11-18 18:08:35', 80, 20),
(3, 'Propio', '2017-07-04 04:59:28', '2017-11-14 09:38:49', 65, 35),
(4, 'Inv.Jorge', '2017-09-18 07:38:59', '2017-11-18 18:08:58', 80, 20),
(5, 'Subcomun', '2017-09-18 07:39:09', '2017-11-16 15:51:42', 80, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `typepayments`
--

CREATE TABLE `typepayments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `typeseasons`
--

CREATE TABLE `typeseasons` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `typeseasons`
--

INSERT INTO `typeseasons` (`id`, `name`, `created_at`, `updated_at`, `order`) VALUES
(1, 'Alta', '2017-06-01 00:02:06', '2017-06-01 00:02:06', 3),
(2, 'Media', '2017-06-01 00:02:45', '2017-06-01 00:02:45', 2),
(3, 'Baja', '2017-06-01 00:06:05', '2017-06-01 00:06:05', 1),
(4, 'Premium', '2017-06-01 00:06:05', '2017-06-01 00:06:05', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` int(11) NOT NULL,
  `zip_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nif` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `defaultTable` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `nif_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `address_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `zip_code_business` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `iban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_stripe_connect` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accept_stripe` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `zip_code`, `address`, `nif`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `defaultTable`, `name_business`, `nif_business`, `address_business`, `zip_code_business`, `iban`, `account_stripe_connect`, `accept_stripe`) VALUES
(1, 'Ian Admin', 627375651, NULL, NULL, 'X8026232Z', 'iankurosaki17@gmail.com', '$2a$04$BGNUJBM/ZdVnx8NOxR28CuWfaH.1.mkxekU9PKfsBjjrmXNyAUKZW', 'admin', 'MD0OqV0B4SMIsaBbkvLFvUJFbXqf1QlpG9nuc1oSuJaNGM3A85sUbKZYgMpf', '2017-05-31 23:36:54', '2019-05-31 17:07:52', '', '', '', '', '', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `years`
--

CREATE TABLE `years` (
  `id` int(10) UNSIGNED NOT NULL,
  `year` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `years`
--

INSERT INTO `years` (`id`, `year`, `active`, `created_at`, `updated_at`, `start_date`, `end_date`) VALUES
(1, 2015, 0, NULL, '2019-05-29 19:23:43', '2015-01-01', '2015-05-31'),
(2, 2016, 0, NULL, NULL, '2016-01-01', '2016-12-31'),
(3, 2017, 0, NULL, '2019-05-29 21:19:29', '2017-01-01', '2017-12-31'),
(4, 2018, 0, NULL, '2019-05-31 16:04:50', '2018-01-01', '2018-12-31'),
(5, 2019, 1, NULL, '2019-06-01 14:04:18', '2019-01-01', '2019-12-31'),
(6, 2020, 0, NULL, '2019-05-29 17:02:06', '2020-01-01', '2020-12-31'),
(7, 2021, 0, NULL, '2019-05-29 20:54:55', '2021-01-01', '2021-12-31'),
(8, 2022, 0, NULL, NULL, '2022-01-01', '2022-12-31'),
(9, 2023, 0, NULL, NULL, '2023-01-01', '2023-12-31'),
(10, 2024, 0, NULL, NULL, '2024-01-01', '2024-12-31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agents_rooms`
--
ALTER TABLE `agents_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agents_rooms_user_id_foreign` (`user_id`),
  ADD KEY `agents_rooms_room_id_foreign` (`room_id`);

--
-- Indices de la tabla `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_question_id_foreign` (`question_id`),
  ADD KEY `answers_room_id_foreign` (`room_id`);

--
-- Indices de la tabla `bail`
--
ALTER TABLE `bail`
  ADD PRIMARY KEY (`id_book`);

--
-- Indices de la tabla `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_user_id_foreign` (`user_id`),
  ADD KEY `book_customer_id_foreign` (`customer_id`),
  ADD KEY `book_room_id_foreign` (`room_id`),
  ADD KEY `book_start_index` (`start`),
  ADD KEY `book_finish_index` (`finish`),
  ADD KEY `book_type_book_index` (`type_book`);

--
-- Indices de la tabla `bookingnotification`
--
ALTER TABLE `bookingnotification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookingnotification_book_id_foreign` (`book_id`);

--
-- Indices de la tabla `cashbox`
--
ALTER TABLE `cashbox`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_country` (`code_country`),
  ADD KEY `city` (`city`);

--
-- Indices de la tabla `classes_prices`
--
ALTER TABLE `classes_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`code`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `dayssecondpay`
--
ALTER TABLE `dayssecondpay`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `extras`
--
ALTER TABLE `extras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `extrasbooks`
--
ALTER TABLE `extrasbooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `extrasbooks_extra_id_foreign` (`extra_id`),
  ADD KEY `extrasbooks_book_id_foreign` (`book_id`);

--
-- Indices de la tabla `fianza`
--
ALTER TABLE `fianza`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `forfaits_calendar`
--
ALTER TABLE `forfaits_calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `forfaits_prices`
--
ALTER TABLE `forfaits_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ical_import`
--
ALTER TABLE `ical_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ical_import_room_id_foreign` (`room_id`);

--
-- Indices de la tabla `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `log_images`
--
ALTER TABLE `log_images`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_book_id_foreign` (`email_customer`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_book_id_foreign` (`book_id`),
  ADD KEY `payments_comment_index` (`comment`),
  ADD KEY `payments_created_at_index` (`created_at`);

--
-- Indices de la tabla `paymentspro`
--
ALTER TABLE `paymentspro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paymentspro_room_id_foreign` (`room_id`);

--
-- Indices de la tabla `percent`
--
ALTER TABLE `percent`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prices_season_foreign` (`season`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_tax_id_foreign` (`tax_id`);

--
-- Indices de la tabla `products_orders`
--
ALTER TABLE `products_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_orders_order_id_foreign` (`order_id`),
  ADD KEY `products_orders_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_owned_foreign` (`owned`),
  ADD KEY `rooms_sizeapto_foreign` (`sizeApto`),
  ADD KEY `rooms_typeapto_foreign` (`typeApto`),
  ADD KEY `rooms_state_index` (`state`);

--
-- Indices de la tabla `rules_stripe`
--
ALTER TABLE `rules_stripe`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seasondays`
--
ALTER TABLE `seasondays`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seasons_type_foreign` (`type`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sizerooms`
--
ALTER TABLE `sizerooms`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes_productos`
--
ALTER TABLE `solicitudes_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitudes_productos_id_solicitud_foreign` (`id_solicitud`);

--
-- Indices de la tabla `special_segments`
--
ALTER TABLE `special_segments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `typeapto`
--
ALTER TABLE `typeapto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `typepayments`
--
ALTER TABLE `typepayments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `typeseasons`
--
ALTER TABLE `typeseasons`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agents_rooms`
--
ALTER TABLE `agents_rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `bail`
--
ALTER TABLE `bail`
  MODIFY `id_book` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `book`
--
ALTER TABLE `book`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `bookingnotification`
--
ALTER TABLE `bookingnotification`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cashbox`
--
ALTER TABLE `cashbox`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `classes_prices`
--
ALTER TABLE `classes_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `dayssecondpay`
--
ALTER TABLE `dayssecondpay`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `extras`
--
ALTER TABLE `extras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `extrasbooks`
--
ALTER TABLE `extrasbooks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `fianza`
--
ALTER TABLE `fianza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `forfaits_calendar`
--
ALTER TABLE `forfaits_calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `forfaits_prices`
--
ALTER TABLE `forfaits_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ical_import`
--
ALTER TABLE `ical_import`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `log_images`
--
ALTER TABLE `log_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `paymentspro`
--
ALTER TABLE `paymentspro`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `percent`
--
ALTER TABLE `percent`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `prices`
--
ALTER TABLE `prices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `products_orders`
--
ALTER TABLE `products_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `rules_stripe`
--
ALTER TABLE `rules_stripe`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `seasondays`
--
ALTER TABLE `seasondays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `sizerooms`
--
ALTER TABLE `sizerooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `solicitudes_productos`
--
ALTER TABLE `solicitudes_productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `special_segments`
--
ALTER TABLE `special_segments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `typeapto`
--
ALTER TABLE `typeapto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `typepayments`
--
ALTER TABLE `typepayments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `typeseasons`
--
ALTER TABLE `typeseasons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `years`
--
ALTER TABLE `years`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agents_rooms`
--
ALTER TABLE `agents_rooms`
  ADD CONSTRAINT `agents_rooms_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `agents_rooms_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `answers_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Filtros para la tabla `bail`
--
ALTER TABLE `bail`
  ADD CONSTRAINT `bail_id_book_foreign` FOREIGN KEY (`id_book`) REFERENCES `book` (`id`);

--
-- Filtros para la tabla `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `book_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `book_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `bookingnotification`
--
ALTER TABLE `bookingnotification`
  ADD CONSTRAINT `bookingnotification_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);

--
-- Filtros para la tabla `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `extrasbooks`
--
ALTER TABLE `extrasbooks`
  ADD CONSTRAINT `extrasbooks_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `extrasbooks_extra_id_foreign` FOREIGN KEY (`extra_id`) REFERENCES `extras` (`id`);

--
-- Filtros para la tabla `ical_import`
--
ALTER TABLE `ical_import`
  ADD CONSTRAINT `ical_import_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);

--
-- Filtros para la tabla `paymentspro`
--
ALTER TABLE `paymentspro`
  ADD CONSTRAINT `paymentspro_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Filtros para la tabla `prices`
--
ALTER TABLE `prices`
  ADD CONSTRAINT `prices_season_foreign` FOREIGN KEY (`season`) REFERENCES `typeseasons` (`id`);

--
-- Filtros para la tabla `products_orders`
--
ALTER TABLE `products_orders`
  ADD CONSTRAINT `products_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `products_orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Filtros para la tabla `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_owned_foreign` FOREIGN KEY (`owned`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rooms_sizeapto_foreign` FOREIGN KEY (`sizeApto`) REFERENCES `sizerooms` (`id`),
  ADD CONSTRAINT `rooms_typeapto_foreign` FOREIGN KEY (`typeApto`) REFERENCES `typeapto` (`id`);

--
-- Filtros para la tabla `seasons`
--
ALTER TABLE `seasons`
  ADD CONSTRAINT `seasons_type_foreign` FOREIGN KEY (`type`) REFERENCES `typeseasons` (`id`);

--
-- Filtros para la tabla `solicitudes_productos`
--
ALTER TABLE `solicitudes_productos`
  ADD CONSTRAINT `solicitudes_productos_id_solicitud_foreign` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
