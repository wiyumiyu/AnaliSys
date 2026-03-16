-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-02-2026 a las 20:41:43
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
-- Estructura de tabla para la tabla `tbm_solicitud_muestras`
--

CREATE TABLE IF NOT EXISTS `tbm_solicitud_muestras` (
  `id_muestras` int(11) NOT NULL AUTO_INCREMENT,
  `agno` int(11) NOT NULL,
  `idlab` int(20) NOT NULL,
  `etiqueta` varchar(350) COLLATE latin1_general_ci NOT NULL,
  `analisis` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_temporal` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_access` int(11) NOT NULL DEFAULT '0',
  `id_cultivo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_muestras`),
  KEY `idx_idlab` (`idlab`),
  KEY `idx_muestras_solicitud` (`id_solicitud`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=226283 ;


