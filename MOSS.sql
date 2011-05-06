CREATE DATABASE MOSS


-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Mai 06, 2011 as 10:09 PM
-- Versão do Servidor: 5.1.49
-- Versão do PHP: 5.3.3-1ubuntu9.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `MOSS`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `ExtremeTemperatureKilled`
--

CREATE TABLE IF NOT EXISTS `ExtremeTemperatureKilled` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `Years` int(5) NOT NULL,
  `India` int(11) NOT NULL,
  `Portugal` int(11) NOT NULL,
  `Romania` int(11) NOT NULL,
  `United States` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `ExtremeTemperatureKilled`
--

INSERT INTO `ExtremeTemperatureKilled` (`id`, `Years`, `India`, `Portugal`, `Romania`, `United States`) VALUES
(1, 2006, 47, 41, 26, 188),
(2, 2007, 185, 0, 68, 0),
(3, 2008, 70, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `TradeBalance`
--

CREATE TABLE IF NOT EXISTS `TradeBalance` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `Years` int(5) NOT NULL,
  `India` text NOT NULL,
  `Portugal` text NOT NULL,
  `Romania` text NOT NULL,
  `United States` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `TradeBalance`
--

INSERT INTO `TradeBalance` (`id`, `Years`, `India`, `Portugal`, `Romania`, `United States`) VALUES
(1, 2000, '-4245869487', '-12267366869', '-1929999616', '-379500000000'),
(2, 2001, '-4255020245', '-11595973154', '-3084000000', '-367000000000'),
(3, 2002, '-5046977702', '-10592414832', '-2602000128', '-424400000000'),
(4, 2003, '-7746193821', '-10252821670', '-4467000064', '-499400000000'),
(5, 2004, '-12662104268', '-13981983324', '-6875285248', '-615400000000'),
(6, 2005, '-22898864984', '-16424041188', '-10247298560', '-713600000000'),
(7, 2006, '-28936530064', '-16015610789', '-11134352384', '-757300000000'),
(8, 2007, '-50036896691', '-16599601992', '-20530492556', '-707800000000'),
(9, 2008, '-66042038772', '-23263925001', '-25585473818', '-710500000000'),
(10, 2009, '-61116904962', '-', '-11150720042', '-386400000000');

-- --------------------------------------------------------
