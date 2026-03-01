-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-02-2026 a las 20:57:01
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `bd_cia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbm_solicitud_impresa`
--

CREATE TABLE IF NOT EXISTS `tbm_solicitud_impresa` (
  `tbm_solicitud_impresa` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `enviada` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_envio` datetime NOT NULL,
  `enviada_por` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tbm_solicitud_impresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8687 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
