-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2012 at 08:26 AM
-- Server version: 5.0.95
-- PHP Version: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cumbari_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage_statistics`
--

DROP TABLE IF EXISTS `coupon_usage_statistics`;
CREATE TABLE IF NOT EXISTS `coupon_usage_statistics` (
  `coupon_id` char(36) NOT NULL,
  `num_consumes` int(11) NOT NULL,
  `num_loads` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `store_id` char(36) NOT NULL,
  `sum_consume_dist_to_store` int(11) NOT NULL,
  `sum_load_dist_to_store` int(11) NOT NULL,
  `sum_view_dist_to_store` int(11) NOT NULL,
  `version` int(11) default NULL,
  PRIMARY KEY  (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
