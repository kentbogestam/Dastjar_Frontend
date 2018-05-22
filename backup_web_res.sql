-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 16, 2018 at 11:11 AM
-- Server version: 5.7.20-0ubuntu0.16.04.1
-- PHP Version: 7.0.27-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backup_web_res`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertise`
--

CREATE TABLE `advertise` (
  `advertise_id` char(36) NOT NULL,
  `company_id` char(36) DEFAULT NULL,
  `u_id` char(36) DEFAULT NULL,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `required_card` varchar(255) DEFAULT NULL,
  `supported_cards` varchar(255) DEFAULT NULL,
  `discount` smallint(6) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `spons` tinyint(1) NOT NULL COMMENT '0=No, 1= Yes',
  `start_of_publishing` datetime NOT NULL,
  `end_of_publishing` datetime NOT NULL,
  `startValidity` datetime NOT NULL,
  `advertise_name` varchar(255) NOT NULL,
  `view_opt` char(3) NOT NULL,
  `infopage` varchar(255) DEFAULT NULL,
  `s_activ` tinyint(4) NOT NULL COMMENT '0=Active, 2=Deleted',
  `reseller_status` char(1) NOT NULL,
  `value` int(11) NOT NULL,
  `partner_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `advertise_keyword`
--

CREATE TABLE `advertise_keyword` (
  `advertise_id` char(36) NOT NULL,
  `offer_keyword` char(36) NOT NULL,
  `system_key` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `advertise_offer_slogan_lang_list`
--

CREATE TABLE `advertise_offer_slogan_lang_list` (
  `advertise_id` char(36) NOT NULL,
  `offer_slogan_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `advertise_offer_sub_slogan_lang_list`
--

CREATE TABLE `advertise_offer_sub_slogan_lang_list` (
  `advertise_id` char(36) NOT NULL,
  `offer_sub_slogan_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` char(36) NOT NULL,
  `company_id` char(36) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL COMMENT '2=deleted, 1=active, 0=inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `campaign_id` char(36) NOT NULL,
  `company_id` char(36) DEFAULT NULL,
  `u_id` char(36) DEFAULT NULL,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `required_card` varchar(255) DEFAULT NULL,
  `supported_cards` varchar(255) DEFAULT NULL,
  `discount` smallint(6) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `spons` tinyint(1) NOT NULL COMMENT '0=No, 1= Yes',
  `start_of_publishing` datetime NOT NULL,
  `end_of_publishing` datetime NOT NULL,
  `startValidity` datetime DEFAULT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `view_opt` char(3) NOT NULL,
  `infopage` varchar(255) DEFAULT NULL,
  `s_activ` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Active, 2=Deleted',
  `reseller_status` char(1) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `code_type` varchar(200) DEFAULT NULL,
  `value` int(11) NOT NULL,
  `accept_by` char(36) DEFAULT NULL,
  `MaxNrOfCoupons` int(11) DEFAULT NULL,
  `GeneratedCoupons` int(11) DEFAULT NULL,
  `RedeemedCoupons` int(11) DEFAULT NULL,
  `partner_id` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_keyword`
--

CREATE TABLE `campaign_keyword` (
  `campaign_id` char(36) NOT NULL,
  `offer_keyword` char(36) DEFAULT NULL,
  `system_key` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_limit_period_list`
--

CREATE TABLE `campaign_limit_period_list` (
  `campaign_id` char(36) NOT NULL,
  `limit_period_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_offer_slogan_lang_list`
--

CREATE TABLE `campaign_offer_slogan_lang_list` (
  `campaign_id` char(36) NOT NULL,
  `offer_slogan_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_offer_sub_slogan_lang_list`
--

CREATE TABLE `campaign_offer_sub_slogan_lang_list` (
  `campaign_id` char(36) NOT NULL,
  `offer_sub_slogan_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` varchar(36) NOT NULL,
  `api_version` int(11) NOT NULL,
  `jpa_version` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories_list_of_categories`
--

CREATE TABLE `categories_list_of_categories` (
  `categories` varchar(255) NOT NULL,
  `list_of_categories` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` char(36) NOT NULL,
  `small_image` varchar(255) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `small_image`, `version`) VALUES
('2c12195a-96e8-102e-bdd2-12313b062daf', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/Entertainment.png', NULL),
('3a986706-9790-102e-bdd2-12313b062daf', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/HealthBeauty.png', NULL),
('449d563e-96e7-102e-bdd2-12313b062daf', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/Cafe.png', NULL),
('7099ead0-8d47-102e-9bd4-12313b062dat', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/FoodDining.png', NULL),
('7099ead0-8d47-102e-9bd4-12313b062dax', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/Shopping.png', NULL),
('7099ead0-8d47-102e-9bd4-12313b062day', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/FoodSnacks.png', NULL),
('7f355eb2-96e8-102e-bdd2-12313b062daf', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/DrinksBeverage.png', NULL),
('9be2061e-f2dd-7eb8-ace2-a68295eb086a', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/cat_icon_da4c4d29bed3fccd41a46954f4edba11.png', NULL),
('b8d7d927-2473-0e78-5859-6437c9eb09aa', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/cat_icon_7bd5c0302d3a2769750fa13893f3b96a.png', NULL),
('bbc9fc83-3e43-8f8a-29ba-e6e11fb746e1', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/cat_icon_bdfa3b5957e860c7cd4498f6e4b986a5.png', NULL),
('c67e0255-f63f-16d8-5251-745ff6c88822', 'https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/category_lib/cat_icon_056b641740aa06abd6d7c5018e6972fe.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category_names_lang_list`
--

CREATE TABLE `category_names_lang_list` (
  `category` char(36) NOT NULL,
  `names_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_names_lang_list`
--

INSERT INTO `category_names_lang_list` (`category`, `names_lang_list`) VALUES
('c67e0255-f63f-16d8-5251-745ff6c88822', '039e3be2-2999-21fd-f689-6b06f30abd40'),
('bbc9fc83-3e43-8f8a-29ba-e6e11fb746e1', '24231770-2d92-1180-bb17-aacdd6ca588c'),
('7f355eb2-96e8-102e-bdd2-12313b062daf', '27eb2726-139f-1c5c-60d4-6cfe525e5897'),
('2c12195a-96e8-102e-bdd2-12313b062daf', '2c12195a-96e8-102e-bdd2-12313b062daf'),
('bbc9fc83-3e43-8f8a-29ba-e6e11fb746e1', '30f663d6-6040-d04f-7728-4fdbfe4d58f3'),
('c67e0255-f63f-16d8-5251-745ff6c88822', '318dd3ab-3a8b-bef3-5584-ddea1867c9af'),
('b8d7d927-2473-0e78-5859-6437c9eb09aa', '37be699e-6bfb-9f54-f91c-15e751e51455'),
('b8d7d927-2473-0e78-5859-6437c9eb09aa', '3b00937d-0833-e32f-8e0a-7c3afb7dc075'),
('3a986706-9790-102e-bdd2-12313b062daf', '3c4f9acf-5459-11e0-adfa-3c39675c97e2'),
('7099ead0-8d47-102e-9bd4-12313b062dat', '3d04980a-5456-11e0-adfa-3c39675c97e2'),
('9be2061e-f2dd-7eb8-ace2-a68295eb086a', '3deaf5ab-ef97-039b-82cc-d727c61ae7b2'),
('7099ead0-8d47-102e-9bd4-12313b062day', '557ce5e2-6eca-c222-3ede-6b1348a55135'),
('2c12195a-96e8-102e-bdd2-12313b062daf', '5c9bb91f-60eb-3ac9-0dfc-58adaab2ea92'),
('449d563e-96e7-102e-bdd2-12313b062daf', '622c7fa4-96e7-102e-bdd2-12313b062daf'),
('7099ead0-8d47-102e-9bd4-12313b062dax', '6405d850-9519-373d-7a78-2e9cb8f42a1d'),
('3a986706-9790-102e-bdd2-12313b062daf', '68c0fc56-9790-102e-bdd2-12313b062daf'),
('bbc9fc83-3e43-8f8a-29ba-e6e11fb746e1', '6c6e124d-9585-0f24-ef68-048249249e30'),
('7099ead0-8d47-102e-9bd4-12313b062day', '7a8e55ac-5457-11e0-adfa-3c39675c97e2'),
('2c12195a-96e8-102e-bdd2-12313b062daf', '7bf2c28b-5097-11e0-bb28-8a7ed7bb2586'),
('449d563e-96e7-102e-bdd2-12313b062daf', '8702915a-5459-11e0-adfa-3c39675c97e2'),
('7099ead0-8d47-102e-9bd4-12313b062dat', '9de47dc0-96e8-102e-bdd2-12313b062daf'),
('7099ead0-8d47-102e-9bd4-12313b062dax', 'a0844b62-978f-102e-bdd2-12313b062daf'),
('7099ead0-8d47-102e-9bd4-12313b062day', 'a981c34e-96e9-102e-bdd2-12313b062daf'),
('7099ead0-8d47-102e-9bd4-12313b062dat', 'ab72e4a5-9359-dc74-dea6-996d8c27cc8e'),
('449d563e-96e7-102e-bdd2-12313b062daf', 'b31c118a-851b-6730-9b55-00a3811ce486'),
('9be2061e-f2dd-7eb8-ace2-a68295eb086a', 'c1ca2030-d8a1-0320-76f4-679da25f5636'),
('9be2061e-f2dd-7eb8-ace2-a68295eb086a', 'c622983a-8b77-a385-d526-0732a1f59db3'),
('3a986706-9790-102e-bdd2-12313b062daf', 'c8f87184-4166-33b0-2ba4-04b5541a6098'),
('7099ead0-8d47-102e-9bd4-12313b062dax', 'd2c5eb50-5456-11e0-adfa-3c39675c97e2'),
('7f355eb2-96e8-102e-bdd2-12313b062daf', 'd876ea49-5459-11e0-adfa-3c39675c97e2'),
('b8d7d927-2473-0e78-5859-6437c9eb09aa', 'e139b9c4-ebdc-62b6-7b8c-3a19b92c4ad1'),
('7f355eb2-96e8-102e-bdd2-12313b062daf', 'ebdc60f2-96e7-102e-bdd2-12313b062daf'),
('c67e0255-f63f-16d8-5251-745ff6c88822', 'f0cbe2c5-e05c-da2e-80bf-06baf2178113');

-- --------------------------------------------------------

--
-- Table structure for table `ccode`
--

CREATE TABLE `ccode` (
  `ccode` char(36) NOT NULL DEFAULT '0',
  `start_of_validity` datetime NOT NULL,
  `end_of_validity` datetime NOT NULL,
  `activ` tinyint(1) DEFAULT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` char(36) NOT NULL,
  `u_id` char(36) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_type` int(10) DEFAULT NULL,
  `orgnr` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `tzcountries` varchar(255) DEFAULT NULL,
  `timezones` varchar(255) DEFAULT NULL,
  `currencies` varchar(255) DEFAULT NULL,
  `pre_loaded_value` int(10) UNSIGNED DEFAULT NULL,
  `budget` int(11) DEFAULT NULL,
  `c_activ` tinyint(4) DEFAULT NULL,
  `seller_id` char(36) DEFAULT NULL,
  `seller_date` datetime DEFAULT NULL,
  `ccode` char(36) DEFAULT NULL,
  `cc_value` int(11) DEFAULT NULL,
  `low_level` int(11) DEFAULT '100',
  `paid` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `ba` tinyint(2) UNSIGNED DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `stripe_publishable_key` varchar(255) DEFAULT NULL,
  `stripe_user_id` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cost`
--

CREATE TABLE `cost` (
  `country` varchar(255) NOT NULL,
  `usage_fee` int(11) NOT NULL,
  `spons_fee` int(11) DEFAULT NULL,
  `brand_fee` int(11) DEFAULT NULL,
  `clicks` int(11) NOT NULL,
  `views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `iso` char(2) CHARACTER SET latin1 NOT NULL,
  `name` varchar(80) CHARACTER SET latin1 NOT NULL,
  `printable_name` varchar(80) CHARACTER SET latin1 NOT NULL,
  `iso3` char(3) CHARACTER SET latin1 DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`iso`, `name`, `printable_name`, `iso3`, `numcode`) VALUES
('AD', 'ANDORRA', 'Andorra', 'AND', 20),
('AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784),
('AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4),
('AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28),
('AI', 'ANGUILLA', 'Anguilla', 'AIA', 660),
('AL', 'ALBANIA', 'Albania', 'ALB', 8),
('AM', 'ARMENIA', 'Armenia', 'ARM', 51),
('AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530),
('AO', 'ANGOLA', 'Angola', 'AGO', 24),
('AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL),
('AR', 'ARGENTINA', 'Argentina', 'ARG', 32),
('AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16),
('AT', 'AUSTRIA', 'Austria', 'AUT', 40),
('AU', 'AUSTRALIA', 'Australia', 'AUS', 36),
('AW', 'ARUBA', 'Aruba', 'ABW', 533),
('AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31),
('BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70),
('BB', 'BARBADOS', 'Barbados', 'BRB', 52),
('BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50),
('BE', 'BELGIUM', 'Belgium', 'BEL', 56),
('BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854),
('BG', 'BULGARIA', 'Bulgaria', 'BGR', 100),
('BH', 'BAHRAIN', 'Bahrain', 'BHR', 48),
('BI', 'BURUNDI', 'Burundi', 'BDI', 108),
('BJ', 'BENIN', 'Benin', 'BEN', 204),
('BM', 'BERMUDA', 'Bermuda', 'BMU', 60),
('BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96),
('BO', 'BOLIVIA', 'Bolivia', 'BOL', 68),
('BR', 'BRAZIL', 'Brazil', 'BRA', 76),
('BS', 'BAHAMAS', 'Bahamas', 'BHS', 44),
('BT', 'BHUTAN', 'Bhutan', 'BTN', 64),
('BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL),
('BW', 'BOTSWANA', 'Botswana', 'BWA', 72),
('BY', 'BELARUS', 'Belarus', 'BLR', 112),
('BZ', 'BELIZE', 'Belize', 'BLZ', 84),
('CA', 'CANADA', 'Canada', 'CAN', 124),
('CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL),
('CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180),
('CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140),
('CG', 'CONGO', 'Congo', 'COG', 178),
('CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756),
('CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384),
('CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184),
('CL', 'CHILE', 'Chile', 'CHL', 152),
('CM', 'CAMEROON', 'Cameroon', 'CMR', 120),
('CN', 'CHINA', 'China', 'CHN', 156),
('CO', 'COLOMBIA', 'Colombia', 'COL', 170),
('CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188),
('CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL),
('CU', 'CUBA', 'Cuba', 'CUB', 192),
('CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132),
('CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL),
('CY', 'CYPRUS', 'Cyprus', 'CYP', 196),
('CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203),
('DE', 'GERMANY', 'Germany', 'DEU', 276),
('DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262),
('DK', 'DENMARK', 'Denmark', 'DNK', 208),
('DM', 'DOMINICA', 'Dominica', 'DMA', 212),
('DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214),
('DZ', 'ALGERIA', 'Algeria', 'DZA', 12),
('EC', 'ECUADOR', 'Ecuador', 'ECU', 218),
('EE', 'ESTONIA', 'Estonia', 'EST', 233),
('EG', 'EGYPT', 'Egypt', 'EGY', 818),
('EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732),
('ER', 'ERITREA', 'Eritrea', 'ERI', 232),
('ES', 'SPAIN', 'Spain', 'ESP', 724),
('ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231),
('FI', 'FINLAND', 'Finland', 'FIN', 246),
('FJ', 'FIJI', 'Fiji', 'FJI', 242),
('FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238),
('FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583),
('FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234),
('FR', 'FRANCE', 'France', 'FRA', 250),
('GA', 'GABON', 'Gabon', 'GAB', 266),
('GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826),
('GD', 'GRENADA', 'Grenada', 'GRD', 308),
('GE', 'GEORGIA', 'Georgia', 'GEO', 268),
('GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254),
('GH', 'GHANA', 'Ghana', 'GHA', 288),
('GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292),
('GL', 'GREENLAND', 'Greenland', 'GRL', 304),
('GM', 'GAMBIA', 'Gambia', 'GMB', 270),
('GN', 'GUINEA', 'Guinea', 'GIN', 324),
('GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312),
('GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226),
('GR', 'GREECE', 'Greece', 'GRC', 300),
('GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL),
('GT', 'GUATEMALA', 'Guatemala', 'GTM', 320),
('GU', 'GUAM', 'Guam', 'GUM', 316),
('GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624),
('GY', 'GUYANA', 'Guyana', 'GUY', 328),
('HK', 'HONG KONG', 'Hong Kong', 'HKG', 344),
('HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL),
('HN', 'HONDURAS', 'Honduras', 'HND', 340),
('HR', 'CROATIA', 'Croatia', 'HRV', 191),
('HT', 'HAITI', 'Haiti', 'HTI', 332),
('HU', 'HUNGARY', 'Hungary', 'HUN', 348),
('ID', 'INDONESIA', 'Indonesia', 'IDN', 360),
('IE', 'IRELAND', 'Ireland', 'IRL', 372),
('IL', 'ISRAEL', 'Israel', 'ISR', 376),
('IN', 'INDIA', 'India', 'IND', 356),
('IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL),
('IQ', 'IRAQ', 'Iraq', 'IRQ', 368),
('IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364),
('IS', 'ICELAND', 'Iceland', 'ISL', 352),
('IT', 'ITALY', 'Italy', 'ITA', 380),
('JM', 'JAMAICA', 'Jamaica', 'JAM', 388),
('JO', 'JORDAN', 'Jordan', 'JOR', 400),
('JP', 'JAPAN', 'Japan', 'JPN', 392),
('KE', 'KENYA', 'Kenya', 'KEN', 404),
('KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417),
('KH', 'CAMBODIA', 'Cambodia', 'KHM', 116),
('KI', 'KIRIBATI', 'Kiribati', 'KIR', 296),
('KM', 'COMOROS', 'Comoros', 'COM', 174),
('KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659),
('KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408),
('KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410),
('KW', 'KUWAIT', 'Kuwait', 'KWT', 414),
('KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136),
('KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398),
('LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418),
('LB', 'LEBANON', 'Lebanon', 'LBN', 422),
('LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662),
('LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438),
('LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144),
('LR', 'LIBERIA', 'Liberia', 'LBR', 430),
('LS', 'LESOTHO', 'Lesotho', 'LSO', 426),
('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440),
('LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442),
('LV', 'LATVIA', 'Latvia', 'LVA', 428),
('LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434),
('MA', 'MOROCCO', 'Morocco', 'MAR', 504),
('MC', 'MONACO', 'Monaco', 'MCO', 492),
('MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498),
('MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450),
('MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584),
('MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807),
('ML', 'MALI', 'Mali', 'MLI', 466),
('MM', 'MYANMAR', 'Myanmar', 'MMR', 104),
('MN', 'MONGOLIA', 'Mongolia', 'MNG', 496),
('MO', 'MACAO', 'Macao', 'MAC', 446),
('MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580),
('MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474),
('MR', 'MAURITANIA', 'Mauritania', 'MRT', 478),
('MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500),
('MT', 'MALTA', 'Malta', 'MLT', 470),
('MU', 'MAURITIUS', 'Mauritius', 'MUS', 480),
('MV', 'MALDIVES', 'Maldives', 'MDV', 462),
('MW', 'MALAWI', 'Malawi', 'MWI', 454),
('MX', 'MEXICO', 'Mexico', 'MEX', 484),
('MY', 'MALAYSIA', 'Malaysia', 'MYS', 458),
('MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508),
('NA', 'NAMIBIA', 'Namibia', 'NAM', 516),
('NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540),
('NE', 'NIGER', 'Niger', 'NER', 562),
('NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574),
('NG', 'NIGERIA', 'Nigeria', 'NGA', 566),
('NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558),
('NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528),
('NO', 'NORWAY', 'Norway', 'NOR', 578),
('NP', 'NEPAL', 'Nepal', 'NPL', 524),
('NR', 'NAURU', 'Nauru', 'NRU', 520),
('NU', 'NIUE', 'Niue', 'NIU', 570),
('NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554),
('OM', 'OMAN', 'Oman', 'OMN', 512),
('PA', 'PANAMA', 'Panama', 'PAN', 591),
('PE', 'PERU', 'Peru', 'PER', 604),
('PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258),
('PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598),
('PH', 'PHILIPPINES', 'Philippines', 'PHL', 608),
('PK', 'PAKISTAN', 'Pakistan', 'PAK', 586),
('PL', 'POLAND', 'Poland', 'POL', 616),
('PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666),
('PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612),
('PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630),
('PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL),
('PT', 'PORTUGAL', 'Portugal', 'PRT', 620),
('PW', 'PALAU', 'Palau', 'PLW', 585),
('PY', 'PARAGUAY', 'Paraguay', 'PRY', 600),
('QA', 'QATAR', 'Qatar', 'QAT', 634),
('RE', 'REUNION', 'Reunion', 'REU', 638),
('RO', 'ROMANIA', 'Romania', 'ROM', 642),
('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643),
('RW', 'RWANDA', 'Rwanda', 'RWA', 646),
('SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682),
('SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90),
('SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690),
('SD', 'SUDAN', 'Sudan', 'SDN', 736),
('SE', 'SWEDEN', 'Sweden', 'SWE', 752),
('SG', 'SINGAPORE', 'Singapore', 'SGP', 702),
('SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654),
('SI', 'SLOVENIA', 'Slovenia', 'SVN', 705),
('SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744),
('SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703),
('SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694),
('SM', 'SAN MARINO', 'San Marino', 'SMR', 674),
('SN', 'SENEGAL', 'Senegal', 'SEN', 686),
('SO', 'SOMALIA', 'Somalia', 'SOM', 706),
('SR', 'SURINAME', 'Suriname', 'SUR', 740),
('ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678),
('SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222),
('SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760),
('SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748),
('TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796),
('TD', 'CHAD', 'Chad', 'TCD', 148),
('TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL),
('TG', 'TOGO', 'Togo', 'TGO', 768),
('TH', 'THAILAND', 'Thailand', 'THA', 764),
('TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762),
('TK', 'TOKELAU', 'Tokelau', 'TKL', 772),
('TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL),
('TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795),
('TN', 'TUNISIA', 'Tunisia', 'TUN', 788),
('TO', 'TONGA', 'Tonga', 'TON', 776),
('TR', 'TURKEY', 'Turkey', 'TUR', 792),
('TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780),
('TV', 'TUVALU', 'Tuvalu', 'TUV', 798),
('TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158),
('TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834),
('UA', 'UKRAINE', 'Ukraine', 'UKR', 804),
('UG', 'UGANDA', 'Uganda', 'UGA', 800),
('UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL),
('US', 'UNITED STATES', 'United States', 'USA', 840),
('UY', 'URUGUAY', 'Uruguay', 'URY', 858),
('UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860),
('VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336),
('VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670),
('VE', 'VENEZUELA', 'Venezuela', 'VEN', 862),
('VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92),
('VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850),
('VN', 'VIET NAM', 'Viet Nam', 'VNM', 704),
('VU', 'VANUATU', 'Vanuatu', 'VUT', 548),
('WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876),
('WS', 'SAMOA', 'Samoa', 'WSM', 882),
('YE', 'YEMEN', 'Yemen', 'YEM', 887),
('YT', 'MAYOTTE', 'Mayotte', NULL, NULL),
('ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710),
('ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894),
('ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716);

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `coupon_id` char(36) NOT NULL,
  `group_id` char(36) NOT NULL,
  `store` char(36) DEFAULT NULL,
  `brand_name` varchar(100) NOT NULL,
  `brand_icon` varchar(255) DEFAULT NULL,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `product_info_link` varchar(255) DEFAULT NULL,
  `category` char(36) NOT NULL,
  `is_sponsored` tinyint(1) NOT NULL COMMENT '0=No, 1= Yes',
  `valid_from` datetime DEFAULT NULL,
  `end_of_publishing` datetime DEFAULT '2031-03-10 00:00:00',
  `coupon_delivery_type` varchar(255) NOT NULL,
  `offer_type` varchar(255) NOT NULL,
  `view_opt` char(3) NOT NULL,
  `version` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `code_type` varchar(200) DEFAULT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_delivery_method`
--

CREATE TABLE `coupon_delivery_method` (
  `store` char(36) CHARACTER SET latin1 NOT NULL,
  `delivery_method` varchar(25) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_delivery_type`
--

CREATE TABLE `coupon_delivery_type` (
  `coupon_delivery_type` varchar(20) CHARACTER SET latin1 NOT NULL,
  `priority` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_keywords_lang_list`
--

CREATE TABLE `coupon_keywords_lang_list` (
  `coupon` char(36) NOT NULL,
  `keywords_lang_list` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_limit_period_list`
--

CREATE TABLE `coupon_limit_period_list` (
  `coupon` char(36) NOT NULL,
  `limit_period_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_offer_slogan_lang_list`
--

CREATE TABLE `coupon_offer_slogan_lang_list` (
  `coupon` char(36) NOT NULL,
  `offer_slogan_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_offer_title_lang_list`
--

CREATE TABLE `coupon_offer_title_lang_list` (
  `coupon` char(36) NOT NULL,
  `offer_title_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage_statistics`
--

CREATE TABLE `coupon_usage_statistics` (
  `coupon_id` char(36) NOT NULL,
  `num_consumes` int(11) NOT NULL,
  `num_loads` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `store_id` char(36) NOT NULL,
  `sum_consume_dist_to_store` int(11) NOT NULL,
  `sum_load_dist_to_store` int(11) NOT NULL,
  `sum_view_dist_to_store` int(11) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage_statistics_history`
--

CREATE TABLE `coupon_usage_statistics_history` (
  `coupon_id` char(36) NOT NULL,
  `num_consumes` int(11) NOT NULL,
  `num_loads` int(11) NOT NULL,
  `num_views` int(11) NOT NULL,
  `store_id` char(36) NOT NULL,
  `sum_consume_dist_to_store` int(11) NOT NULL,
  `sum_load_dist_to_store` int(11) NOT NULL,
  `sum_view_dist_to_store` int(11) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `fac_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `range` double DEFAULT NULL,
  `phone_number_prifix` int(10) DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified_phone` tinyint(1) NOT NULL DEFAULT '0',
  `otp` int(10) DEFAULT NULL,
  `web_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `c_s_rel`
--

CREATE TABLE `c_s_rel` (
  `campaign_id` char(36) DEFAULT NULL,
  `product_id` char(36) DEFAULT NULL,
  `advertise_id` char(36) DEFAULT NULL,
  `coupon_id` char(36) DEFAULT NULL,
  `store_id` char(36) NOT NULL,
  `start_of_publishing` datetime NOT NULL,
  `end_of_publishing` datetime NOT NULL DEFAULT '2031-03-10 00:00:00',
  `activ` tinyint(4) DEFAULT NULL COMMENT '1=Active, 2=Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dish_type`
--

CREATE TABLE `dish_type` (
  `dish_id` int(11) NOT NULL,
  `dish_lang` varchar(255) NOT NULL,
  `dish_name` varchar(255) NOT NULL,
  `company_id` varchar(255) NOT NULL,
  `u_id` varchar(255) NOT NULL,
  `dish_activate` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employer`
--

CREATE TABLE `employer` (
  `company_id` char(36) NOT NULL,
  `u_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `financial_exception`
--

CREATE TABLE `financial_exception` (
  `partner_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `in_stock`
--

CREATE TABLE `in_stock` (
  `store_id` varchar(36) CHARACTER SET latin1 NOT NULL,
  `product_id` varchar(36) CHARACTER SET latin1 NOT NULL,
  `in_stock` int(20) NOT NULL,
  `amount_in_stock` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lang_text`
--

CREATE TABLE `lang_text` (
  `id` char(36) NOT NULL,
  `lang` varchar(3) NOT NULL,
  `text` varchar(300) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lang_text`
--

INSERT INTO `lang_text` (`id`, `lang`, `text`, `version`) VALUES
('000cb732-abdb-7ed3-d5e3-e0664b1e38b1', 'SWE', 'sportfy', NULL),
('00522560-7df6-8287-4a89-3ee34fa038bc', 'ENG', 'keyword_pra', NULL),
('00c1e476-dbcd-c8fb-964d-6ddbd81c5113', 'ENG', 'Dressing', NULL),
('00cdda0b-a36d-ae6a-2705-d40d9703d5e5', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('00f9eb36-a4e9-baf7-81b0-24834226043e', 'SWE', 'a6c213b5-58b1-a8c2-5bc0-7cc3ecd10d78', NULL),
('015d0063-4672-37c9-86a7-ece2ee7ef506', 'ENG', 'd7732478-60a8-7f2f-c96e-9e6082fe2223', NULL),
('01632e0f-df3e-48d4-0de3-19304bdc6d07', 'ENG', 'test204', NULL),
('02197b38-5e3f-b074-e366-7015d5b9bee2', 'SWE', 'Erik Såser Dressing', NULL),
('02b7f3e8-207e-4c78-2592-744cafcaacdf', 'ENG', 'test204', NULL),
('039e3be2-2999-21fd-f689-6b06f30abd40', 'SWE', 'Övrigt', NULL),
('03f5f38e-6871-6f18-f9d0-f402bee6b23f', 'ENG', 'Lasange', NULL),
('04808abd-d035-c6f6-7881-6edac07b9090', 'GER', 'T-Shirts, Bikers, Tanks - nur 6,00 Euro', NULL),
('04859b73-419b-575f-26db-a74dee73a8bc', 'GER', 'T-Shirts, Bikers, Tanks - nur 6,00 Euro', NULL),
('04ba1833-1e73-838c-b7a9-ac0e15e2868e', 'ENG', 'Limited offers', NULL),
('05068f1c-9220-d6c6-1d59-2d107c3c0571', 'GER', 'Spiralblöcke Künstleredition - große Motivauswahl', NULL),
('05b35dfe-11d2-94d1-0f7a-2e5a8201962b', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('05bbcdb4-839a-af57-618f-c52049b5adfe', 'ENG', '2a13aa21-7163-e257-fa38-f355430b58c4', NULL),
('05d1b329-a47b-b7b1-ddf0-ebe471f4ba8e', 'GER', 'Neue Kollektion von Kissen im Schwarz-Weiß Design', NULL),
('0637fdfd-20ef-37f3-ce91-211d2b8cc0f9', 'ENG', '769aff7e-85ba-fb7d-afe5-9f78f3c48223', NULL),
('065b7284-6acf-5ed3-3486-bed397670c23', 'GER', 'Pris:11Kr', NULL),
('066ad7bc-e701-826b-e460-261e7c6a9377', 'ENG', 'pras_desc', NULL),
('078e3c3a-1b77-fc10-1219-c6ee7632b66d', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('07a3af53-82ac-deb0-40aa-aa06d77026cf', 'ENG', 'awa_key', NULL),
('07ef56f8-be51-eb09-d682-1b4c9d889e6b', 'ENG', 'Ofeer valid till 1 week only', NULL),
('082cb210-c53a-1104-d5b0-bd319fa8cd44', 'ENG', 'test', NULL),
('093bbbde-5663-fafd-560b-a8f4ba1fcd50', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('09b73f31-44d4-f746-fc97-fbd5c0cd5c8d', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('09f7951b-ce19-65b5-5111-a81b3159c2a2', 'SWE', 'mindre öäåÅÄÖ æüÜ', NULL),
('0acd1ac8-5a3c-e277-acbe-ecd419a624fc', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('0b3941c6-c693-b555-1772-ce40b658f1ec', 'ENG', 'Lasange7', NULL),
('0c96b89d-f9f5-226a-ef70-a8cdd706eb12', 'ENG', '50% Discount', NULL),
('0cb0873a-ec09-527c-b065-384be9867067', 'ENG', 'bd9648a7-513b-32cd-0338-f8c3bb1c004b', NULL),
('0ced783f-350d-37b4-dc11-fe4c1e363b97', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('0cf017bc-817b-2793-a5fc-886a89a0fba8', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('0e58fb80-2162-55b1-132e-de5d3849bcbd', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('0ec01a0a-dec5-3d40-2b27-21202d5d3cce', 'ENG', 'sdffds', NULL),
('0f6b58cf-f93f-45ef-f572-d6e795ded168', 'ENG', 'Dressing', NULL),
('0fbeb27e-879f-6a28-3c7f-1803a2062939', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('1011e2e5-c85f-423d-b732-563b60715a59', 'GER', 'Spiralblöcke', NULL),
('10299628-4790-9654-05ba-d2d1ea32bb3c', 'ENG', 'Dressing', NULL),
('103c7cf6-227a-fdba-772a-9def90b0bdf8', 'ENG', 'Price:8Rupee', NULL),
('109089ec-86ef-6c15-bb38-bce9341e443a', 'ENG', 'QWERT', NULL),
('10b7dbee-4d8d-6dd5-bcfe-1568599f0ec9', 'SWE', 'burgare', NULL),
('10e2ec76-1b19-cef3-ec50-3c7bc7823b64', 'SWE', 'cf2d59fa-c20a-e75c-7e51-a1897b95d258', NULL),
('110cb904-39c5-b4ba-7429-6771c4a8fe80', 'SWE', 'Test ÅÄÖ åäö Brand', NULL),
('11123986-e2ec-2c5a-48b4-a92ae32ccc57', 'ENG', '45859466-bf84-1edd-e127-99ec76e97aba', NULL),
('113b8cc5-0f1b-770b-4486-71edf9acdb36', 'ENG', 'cf017909-ad6d-e1c4-60c4-85f13127b52d', NULL),
('12b5f3a6-052a-ded8-0c17-e41fd686d2a4', 'ENG', 'Dressing', NULL),
('1325e6aa-bec2-6c95-e646-127a243a0b09', 'ENG', 'b7ec414f-a3a5-36ce-75ba-5eca8080a3df', NULL),
('133e0f81-0b0c-93da-62b3-29132c8a4d89', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('13821756-e5b8-4a25-3bdc-33adf7fb9eea', 'ENG', 'new_qwerty_key', NULL),
('139665dc-5a51-161e-4bbc-019ed0851a0d', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('140a356d-af00-7a24-216a-c5d8a1ab24e5', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('14e46e42-a1ca-d303-47f8-7c6ce3caf1e1', 'GER', 'Badeschuhe, Bademode, Gummischuh', NULL),
('173ea746-e033-9611-af16-5b91eaf85f58', 'ENG', 'asdasd', NULL),
('17a062dd-546a-6670-42be-d6b6b5c056d9', 'ENG', '25b26bf9-2301-b7b8-46a1-f4504f6392f0', NULL),
('17c5a82e-d0f6-c972-b937-2d1935a806bd', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('18cf34eb-88cf-fdbb-f50b-87bc5979063c', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('18f9f87d-9b37-e8ae-f205-4e224fe3d110', 'ENG', '5426c798-28fc-5ec5-b657-12d7b6ddcebc', NULL),
('1956d590-09bf-e6ac-a486-4fc7ea780d0c', 'ENG', 'Dressing', NULL),
('1a401c31-333a-7a2b-44b4-9226f3a6427a', 'ENG', 'awa_desc', NULL),
('1a77415e-4b8b-b9ae-5d51-62558888d781', 'GER', 'Spiralblöcke Künstleredition - große Motivauswahl', NULL),
('1bfb08a7-d082-1360-9566-a7dc4e553eab', 'ENG', 'Hamburger Dressing', NULL),
('1c3f1c66-fc65-228d-c5de-7c6cfd9d13ee', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('1c46f69c-958a-ae3a-2d30-419db49cf752', 'SWE', 'bäst kampanjen', NULL),
('1cee65e1-ffd2-6f06-5275-1c2ab0654922', 'SWE', '078cb353-681e-f5c0-dd2a-c02ed0b78547', NULL),
('1d1c5fd6-5d55-b68a-a378-8ee374b014ec', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('1e26a007-a267-42a8-47eb-841cdfc07798', 'ENG', 'sdfdf', NULL),
('1e6497df-851d-bde1-14bb-86009f6510c6', 'ENG', 'Hamburger Dressing', NULL),
('1e822db4-a360-344c-0344-34c6616b13d9', 'ENG', 'Camp', NULL),
('1f6bb829-8906-e26a-48ba-332284b7aec9', 'ENG', 'pasta', NULL),
('1f8b2296-846f-93ed-f96b-eb5ab6a85fdf', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('1fb6690d-79e3-6640-e972-a5a92535ea6c', 'ENG', '22', NULL),
('2022f8e1-c080-2807-1e1f-1cd177c15fd9', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('20305ea2-ff1d-124d-a371-2ac598c8962b', 'ENG', 'Dressing', NULL),
('2083c6f6-1889-a696-45b6-29a8466c23b9', 'ENG', '221', NULL),
('20b5e5be-98a4-cfaa-26d7-5d072dda8d5d', 'ENG', '261ed9e2-dd64-f514-4aa9-192de1b7490a', NULL),
('20be86a2-44f6-1743-7435-b54ef86d368e', 'ENG', 'Lasange8', NULL),
('2103bf74-f19a-dbf1-653c-5df564a5aeb2', 'ENG', 'Dressing', NULL),
('2116f1bb-372c-f036-7ac7-785fb5518072', 'ENG', 'Hamburger Dressing', NULL),
('21563bd0-1efb-3bd8-cb62-d1e2dd11fb9c', 'ENG', 'Hamburger Dressing', NULL),
('21c6f2d2-fa5c-f682-7fc3-df143d57d99a', 'ENG', 'test2010', NULL),
('21e708da-8de8-5f12-91b2-02e5ef7ad878', 'ENG', 'Hamburger Dressing', NULL),
('22ea0170-235d-c516-98c4-bf327b75c63a', 'ENG', '50% Discount', NULL),
('2308776f-b429-7c4d-110a-bd993de6c968', 'ENG', '0db1bedb-852e-b43c-301f-48a1622139c9', NULL),
('24231770-2d92-1180-bb17-aacdd6ca588c', 'SWE', 'Spel & Lotteri', NULL),
('24d416a8-bde0-6782-039c-a97447121484', 'ENG', 'pras_tittle', NULL),
('2554ad7e-9200-ca92-7a2d-d83ae76d53ac', 'ENG', 'Dressing', NULL),
('25638ec1-f966-6789-5a40-5cdaa8e5461d', 'SWE', 'pizza', NULL),
('264a5f33-d52e-d76a-7173-f3f14a8e216f', 'ENG', 'awa_key', NULL),
('266acc1f-58e8-4ed4-1430-69d5041cc24d', 'ENG', '5ba2fe68-7264-917c-ae13-edf777624a88', NULL),
('2671e4b4-1cbc-8dc9-2040-eae5fa03ebc5', 'SWE', 'plast', NULL),
('268bd627-90d9-26b8-6c4e-873e304a9b92', 'ENG', 'JulyEndOffer', NULL),
('26cf2cb9-395a-d722-34af-e6b4ef14a21b', 'ENG', 'testetse', NULL),
('26eba049-6246-1199-9613-d8ee0c746d69', 'ENG', 'Hamburger Dressing', NULL),
('275f4932-1212-ceb6-b188-141077af3d4e', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('279ca259-80a0-35b1-5c2c-dc4837b6a82d', 'ENG', 'FirstCampimp', NULL),
('27eb2726-139f-1c5c-60d4-6cfe525e5897', 'GER', 'Gesundheit & Schönheit', NULL),
('27f479c2-dc5a-1964-3ac6-a1c13c8cea17', 'ENG', 'FirstCampimp', NULL),
('28613566-a075-f031-8308-5dcb3ebe029b', 'ENG', 'tesenew', NULL),
('28da3fae-9dbb-f038-b819-0c2ce64387ba', 'GER', 'Pris:45Kr', NULL),
('28e05d7f-7a9e-1533-36aa-a8f7f9f55231', 'ENG', '4b892a28-36fe-91a6-c229-522d51071fbd', NULL),
('2998ec15-3226-c8d5-feb8-8b1b8edb0b89', 'ENG', 'Lasange4', NULL),
('29a2c78c-e569-2282-65e0-19984fa75be5', 'ENG', 'Lasange', NULL),
('2c12195a-96e8-102e-bdd2-12313b062daf', 'ENG', 'Entertainment', NULL),
('2c602b39-afba-cade-9710-685979dd32e8', 'SWE', 'a3d1c706-ed0a-b055-0695-215c0c9be122', NULL),
('2c889190-ae19-d625-ed5a-1ca7e3b009b3', 'GER', 'Flip Flop Mania', NULL),
('2d783aa3-c29b-081a-e023-526c8fa02906', 'ENG', 'Testing new server setup', NULL),
('2dbd5371-5d65-a22c-87d8-0a8af2bd8f9e', 'GER', 'Tierkreiskalender', NULL),
('2df8db20-4888-4983-40b5-da93f12fe808', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('2ea8ef1a-9c9d-b55e-bf06-53aef5828687', 'ENG', '6c6958ec-6f28-cc4f-ba15-4e5395da2411', NULL),
('305563e7-e097-adb7-572c-d3bc7e24a7c2', 'ENG', 'Dressing', NULL),
('309ac06e-4dfe-0d7a-d20c-8d4b134fa2dc', 'GER', '1000e von Flip Flops zum Schnäppchenpreis', NULL),
('30c82ef4-6dd8-8c19-def7-b7b4558f4c4a', 'ENG', 'Lasange', NULL),
('30e95037-b595-7f2f-ae17-69efebd12b06', 'GER', 'c0bbe16d-587a-1030-d0a8-faa2be635558', NULL),
('30f663d6-6040-d04f-7728-4fdbfe4d58f3', 'GER', 'Games & Glücksspiele', NULL),
('3121c084-d435-27ec-01df-998d3c323666', 'ENG', '9456a746-62f7-d0a0-08b5-a443d5b2d030', NULL),
('3122c065-4998-00ee-f644-5be7b0925f29', 'ENG', 'bea3600e-ed5e-321a-eeb2-72276f9bef7d', NULL),
('318dd3ab-3a8b-bef3-5584-ddea1867c9af', 'GER', 'Sonstiges', NULL),
('31bbef24-05cb-076d-edf1-ce4a22b68c42', 'ENG', 'This Precious discount on Health & Beauty product', NULL),
('31ceecd8-d600-13b7-7179-23e5834b7fdf', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('31d0977d-167b-387f-8c4d-832aebdf5083', 'ENG', 'Hamburger Dressing', NULL),
('31e4a898-f19e-76c4-ac43-f6f572b39a11', 'ENG', '06cccad3-9895-d8b4-af65-e01ef2c47507', NULL),
('3244af1b-cbac-539c-377d-488db778e1e7', 'ENG', 'c401ec64-1177-6b72-7b02-17c59ef8e13c', NULL),
('3248b485-112e-d900-3d11-6869a956bc4f', 'ENG', '', NULL),
('32571b1d-6f61-9b23-243a-bbc40226981a', 'ENG', 'test204', NULL),
('328cfe37-42df-b540-99bc-2409d26e87b2', 'ENG', 'Speisekartoffeln 2,6 kg Packung  - Jetzt €1.79', NULL),
('32c3fa1a-2dc1-8980-fa67-d8ca5cad6b23', 'ENG', 'test204', NULL),
('34ad474b-2159-132c-7d3c-c9a3da6c4189', 'ENG', 'test204', NULL),
('34f5e1b4-e3ae-fed5-a2d1-c7d6a189340c', 'ENG', 'pasta', NULL),
('35807016-09ae-7c95-a06b-6e0390c45060', 'ENG', 'Dressing', NULL),
('3587f301-8c29-f777-7aac-976c98c21c49', 'SWE', 'ÅÄÖ brand', NULL),
('35e94abd-f25f-ac31-bb6c-22503a826f7d', 'ENG', '12345', NULL),
('372895ab-f814-572d-7e66-d9c4e7ac81f3', 'ENG', '6f855548-c96e-abc7-2576-075f94f32950', NULL),
('375fef56-a6d1-3b7b-1913-293725c8d404', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('37be699e-6bfb-9f54-f91c-15e751e51455', 'GER', 'Kundendienst', NULL),
('3808887a-3738-abec-9093-71a235be1a32', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('382244d4-74f1-fa06-d7f5-b5f97b294049', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('3982545f-f667-f1cd-16b5-67b3ab9c1f59', 'ENG', 'test2010first', NULL),
('3a23697a-8dcb-0d6a-2842-ea05d658b7d9', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('3b00937d-0833-e32f-8e0a-7c3afb7dc075', 'ENG', 'Help & Service', NULL),
('3b9a5d29-fbe3-23ec-e782-367b08a4d7ff', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('3c361e5f-e248-2b95-ca03-f29556d662c1', 'ENG', '714e5685-579c-252b-33d9-c439dd571937', NULL),
('3c4f9acf-5459-11e0-adfa-3c39675c97e2', 'SWE', 'Hälsa & Skönhet', NULL),
('3c571290-df21-07f6-0e2f-8321f0f46c1a', 'SWE', 'ÅÄÖ', NULL),
('3ca44bbe-00d4-c33b-9a84-2acecb92a527', 'SWE', '267ba453-84e2-60a1-05a2-4ef3e1c6c3e3', NULL),
('3cd0c98b-481d-aa48-ab16-e8f9f36b55d9', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('3d04980a-5456-11e0-adfa-3c39675c97e2', 'SWE', 'Mat & Restaurant', NULL),
('3d4f6baf-e7a1-79c5-b676-0bd72f444427', 'SWE', 'b6b27c61-6ef2-19d0-8e6f-babdc989c530', NULL),
('3dd54d2f-b20e-6d6d-dc0e-b0d866d0fd93', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('3deaf5ab-ef97-039b-82cc-d727c61ae7b2', 'GER', 'Bücher & Zeitschriften', NULL),
('3df86927-4f2f-6179-83c3-e22016d73e79', 'ENG', 'Offer valid for Gurgaon shoppers stop1', NULL),
('3e200e88-2bd5-8512-4799-7b7578f41384', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('3e68995b-b0c6-3602-c28a-4c211459c77a', 'GER', 'Speisekartoffeln 2,6 kg Packung  - Jetzt €1.79', NULL),
('3e75a1b0-21a2-46d7-8738-542bcd0adc01', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('3e778174-6227-4680-86fa-c5f85fac7491', 'ENG', '60073840-bca0-ff45-99df-f8f7adfcaa1f', NULL),
('3e8403af-d952-2ca7-9a30-7cb54cd8d2be', 'ENG', '1f50924d-e48c-927f-547b-a06c1e7402b7', NULL),
('3ea33866-51e3-711a-7a97-5e98dc3fe577', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('3ed3195c-e662-8839-c2b6-4287e3d2891a', 'ENG', 'Lasange1', NULL),
('40f596b2-f795-9741-fcc8-e115013e60a5', 'ENG', '894e04e1-0ffe-0379-2ef0-c56c37cbafc4', NULL),
('40fbd6fd-20e1-4ff1-b2ac-81dfa628abdd', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('413405b4-8045-ed02-473e-76c003ebc11b', 'ENG', '8d62a7ad-c64c-d9be-30b1-4fcf11fe209d', NULL),
('4158e080-d6fa-0511-d644-88056bfa65a9', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('438f6a2d-4058-eb46-0c79-6f52b6995f1f', 'ENG', 'awasthi_desc', NULL),
('45324c7e-c77b-2777-fd9c-fe18ddb02e1a', 'ENG', 'Lasange8', NULL),
('4570c169-182a-2ebf-d8da-5a380e821808', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('45fc1f40-c275-9bde-cfc0-ec3473edf20a', 'SWE', 'Vegetarisk Lasange', NULL),
('45fd4d58-5e04-05d9-be8e-6111ea2ffbb6', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('463bb6a2-d259-f620-3d1d-84ab1d438fc5', 'GER', 'potatis', NULL),
('464b5b0c-3fc3-5a28-8ff4-301e841086d8', 'ENG', 'f488fc97-9c85-0c55-d5d2-3ccacd2ed1ca', NULL),
('46513c55-47e4-66c3-df9e-b1de58931c6e', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('4679469f-43d8-d502-9e0e-4000e9d57e52', 'ENG', 'potato', NULL),
('46c97ac8-9deb-dd35-f3ca-09eed9424876', 'SWE', 'Gorgonzola', NULL),
('46e884bc-e11d-276f-6890-7a3614a48e96', 'ENG', 'Hamburger Dressing', NULL),
('46f510b3-6fbe-f3a6-059e-99f70fc18a0f', 'ENG', 'pizza', NULL),
('47b98810-811b-02ad-4140-83a016a4cf47', 'SWE', 'pasta', NULL),
('47d9ee92-713c-5518-dda3-e4be6ba708f9', 'GER', ' Shirts Angebot', NULL),
('49a1c608-9c9c-5ed2-b516-3d96dbfa0897', 'GER', 'Spiralblöcke', NULL),
('49c884b4-0125-aed9-6b18-9138614103ea', 'SWE', 'pasta', NULL),
('4bc29e72-3523-adfa-904c-01d159146b5f', 'ENG', 'pasta', NULL),
('4c119ece-ffc0-d9c0-e00d-4410ec574a43', 'ENG', 'Dressing', NULL),
('4c2ce03b-f44f-df8c-beab-6d3f08cc8772', 'ENG', '407ca8b3-f773-c800-4cf9-a47c26844dd9', NULL),
('4c53c05f-e273-bb6b-1b7f-124ceeb70962', 'ENG', 'c125c44f-c294-734c-2804-27d6cdcb6504', NULL),
('4c92c44f-f9b6-39cf-6d42-dfcb2fa0d456', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('4d9df30f-9bb0-257d-4017-968c4613bef4', 'ENG', 'Gorgonzola', NULL),
('4da81f5c-73f1-558f-77f3-72a7d5c00b82', 'GER', 'Mittagstisch, Asia, thailändisch, asiatische Küche, Thai', NULL),
('4dd674c4-f5c3-1ffc-5e81-6a9b6e4f4ceb', 'ENG', 'pasta', NULL),
('4e5532be-86c7-860a-d62a-b2b21e21d9f2', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('4ebf8fd2-b739-71eb-1cf4-73118b329f4c', 'SWE', 'pasta', NULL),
('4f1d5187-f890-0620-a816-991e3c1c9b7a', 'ENG', 'c7dfaa18-20b6-dcb0-6fac-14dad1ce3491', NULL),
('5004fab1-feeb-ed5b-5761-b70cea798e67', 'ENG', 'test204', NULL),
('50456e49-c772-ae4f-7d32-11df39585d9b', 'ENG', 'asfdads', NULL),
('51e91228-85cf-cad2-6250-ccf9ea0f4872', 'GER', '2f6997aa-463b-b22c-cd5b-fcd3a17c7c34', NULL),
('52a78461-b2ba-2429-dbd1-73dd5be2bf40', 'SWE', 'burgare', NULL),
('5329a267-8b27-e8a4-89a3-e985264eddd7', 'GER', 'Pris:45Kr', NULL),
('5365d759-5a0a-7aac-e6f2-e2c0d2eef72e', 'SWE', 'pizza', NULL),
('537d2f94-e452-d174-ad3d-20891fc53161', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('543c56b2-1a08-b384-2a4b-4c68d3a4b897', 'GER', 'Shirts Angebot', NULL),
('546c3dea-9da3-d7d7-8fbf-109c069c6dcc', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('557ce5e2-6eca-c222-3ede-6b1348a55135', 'GER', 'Bistrot & Imbiss', NULL),
('558b0257-3aa2-8837-f091-b8928c893b85', 'GER', 'Oberbekleidung', NULL),
('561ff47d-9368-69e8-da43-b92dfe7c8598', 'ENG', 'Hamburger Dressing', NULL),
('562b8d71-6e53-96a9-ccf4-ef4fee45d306', 'GER', 'Pris:10Kr', NULL),
('56499a39-7baa-24c8-e150-7958dbde00c2', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('56749cd8-644e-f817-8d17-b972a6be2990', 'ENG', '78361dee-97c6-d475-6e6a-61f518989523', NULL),
('56abd4e8-23c4-3e61-fc82-a0ed401d7ac4', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('56be843e-a331-6a2f-310c-d7a0a42140f1', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('578ecff0-b4fc-d0aa-1a9a-109f4a09736e', 'SWE', 'såsser dressing sallad', NULL),
('57b03579-cbc9-9a4a-5ee1-45fb2d03277c', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('584d2265-27d4-1a7a-6cae-e40ed2f0b758', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('585840b2-0682-faa3-73c0-a0657ee041d6', 'ENG', 'Lasange9', NULL),
('59c2b04c-e128-ff97-2da4-14d6f2db55e0', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('5a7de689-9f28-f52e-3c49-282d53886c7b', 'ENG', 'Offer valid till end of july', NULL),
('5b64fb0c-563d-2b43-88e9-08beb88d7e54', 'GER', '3816e011-53cb-83bc-a4f4-9b9c858c9953', NULL),
('5b9b1b19-91d5-cefe-7722-ee8efb58daa7', 'GER', 'aa94e0ad-cc93-6465-7f54-2744e307d226', NULL),
('5bbba606-6e63-4297-f249-a8dbd75afa88', 'ENG', 'Speisekartoffeln kartoffeln', NULL),
('5c242efa-48b3-92e9-4aa7-709912213684', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('5c283a5b-3745-0871-a664-370f2c6a4cff', 'ENG', 'FirstCampimp', NULL),
('5c90bd51-1f08-c4d9-4b68-8fd05f2510e7', 'SWE', 'dressing', NULL),
('5c9bb91f-60eb-3ac9-0dfc-58adaab2ea92', 'GER', 'Unterhaltung', NULL),
('5cda7c99-35da-5374-cd2b-e5429d13dc76', 'SWE', 'Husets Special', NULL),
('5d9042d3-2798-02bd-5201-7fcf8ea0f5d3', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('5d954510-0cf5-7b72-e7ed-6d0bc0f4dd7b', 'GER', '51a19300-0146-6d80-840d-6eb2bba89c37', NULL),
('5daf466a-b05c-a0ea-74a6-72331cb935f3', 'SWE', 'Lasange2', NULL),
('5e0a1c1f-b4a6-1d9f-39cc-839dae14eafa', 'ENG', '0b764552-3de5-9bb1-ce6c-7865b656c820', NULL),
('5e9dee60-9b2c-eabe-c1aa-359060b2d70a', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('5ec26aa4-4ea2-adac-2f70-c9ef57de6f1c', 'SWE', 'min kampanj', NULL),
('5f20cc28-2e76-44ab-c69e-cdd763518ddc', 'SWE', '26b075fc-f940-9dc8-bff9-ab13f14c952a', NULL),
('5f90886e-0585-7785-9162-0a13f1085aba', 'ENG', 'cea7ac63-c98c-8cac-4315-7cd41382fe3e', NULL),
('5fa23279-1e34-438d-5dd5-8e9f6ab5d173', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('600802a9-d77f-d5f3-c335-80961855f967', 'ENG', 'new_qwerty_desc', NULL),
('6018b19d-6268-1f4d-5b18-e0e6f358c8c4', 'ENG', 'Dressing', NULL),
('6019ecc2-3c5f-bb7c-2d4a-340be2866f0f', 'GER', '20 % Rabatt auf Mittagsbuffet', NULL),
('6082e35e-3bce-00ec-09ba-e8d1e2889445', 'SWE', '721700ab-b552-2ec4-1829-a1d804b8bd94', NULL),
('60ddef19-993c-0c08-0367-9ce6a348ed2f', 'GER', 'Oberbekleidung', NULL),
('60e95954-bd40-4e7d-83f8-640e7fc2aab1', 'ENG', 'Hamburger Dressing', NULL),
('6145252a-43dd-7f62-021a-d7e9df6af966', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('622c7fa4-96e7-102e-bdd2-12313b062daf', 'ENG', 'Café', NULL),
('626af6c7-a0b6-dbf6-82e2-48480dc00541', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('63e65445-b704-0687-5c4f-37e274f21524', 'SWE', 'ade0660b-836e-0e64-8861-d5c28aaed213', NULL),
('64052fb0-8b1f-3ac3-94c4-cdbcdb49a558', 'SWE', 'Marinara', NULL),
('6405d850-9519-373d-7a78-2e9cb8f42a1d', 'GER', 'Shopping', NULL),
('64f8e6dd-b5cf-d319-a11f-bef3a0810a42', 'SWE', 'Pris:67Kr', NULL),
('655a544d-ed26-a80f-64f0-dafbc8e610bc', 'ENG', 'asdas', NULL),
('65a755b7-5b03-d013-c594-3da194a04c14', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('65ca7128-1a58-1eae-d102-394940153e1d', 'ENG', '575a08be-51ac-a6c5-4ab8-d543baa94ee6', NULL),
('65f04698-2034-78d2-1046-10a1c241adcc', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('663620b7-cf02-18a6-d97c-85577cedd81a', 'ENG', 'Buy 2 Get 4', NULL),
('669cd3e9-6a9f-5ac1-f4a4-e3e3b070cbdb', 'ENG', '3e562f8b-1f3f-4910-819e-55828eae67d6', NULL),
('66aee954-3830-2056-0c58-c9939911608a', 'GER', 'dc51d359-1a62-24ad-42e9-05382778ae56', NULL),
('6732c430-a8ca-e8c8-6d4d-c73723f23881', 'ENG', '52af7a06-0f55-5280-33a4-9ee57e9fe567', NULL),
('680925fc-ca09-6626-f540-d5368c151f23', 'ENG', 'Hamburger Dressing', NULL),
('685f6d36-d839-5c4f-37cd-185a5ab9d63c', 'ENG', '7e682c84-be4e-2efa-2fd6-230c31878249', NULL),
('68776bc2-4f7c-cc3e-6eb0-f1c3fa1212f1', 'ENG', 'Lasange2', NULL),
('68c0fc56-9790-102e-bdd2-12313b062daf', 'ENG', 'Health & Beauty', NULL),
('68dcc21f-eac1-3a4e-dddb-290133cf12f4', 'ENG', 'awasthi_ttitle', NULL),
('6aad2b05-e36d-5cdd-dde3-0d1bf9798c5f', 'ENG', 'awa_tittle', NULL),
('6adb5c77-4df6-3196-fa6a-aa1e049f5b47', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('6b0dc7a1-3a88-b9e3-045f-460678b034e9', 'ENG', 'Scotch, Whisky, Beer', NULL),
('6b31b982-fed8-b0aa-c606-57a3ab547e5c', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('6b4cce17-29d5-041b-d52a-4fd64d25212f', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('6b4f9489-21fb-6452-1f9a-02fa8294cbb3', 'ENG', 'Dressing', NULL),
('6b7035cd-c98b-ab05-a975-2010dc630bbb', 'ENG', 'Lasange5', NULL),
('6b72ce06-340d-fee4-62d2-7e77475343c6', 'ENG', 'Dressing', NULL),
('6ba487b7-fcd9-4d6a-6b7e-3f0cc65aef39', 'ENG', 'Lasange7', NULL),
('6c0143e4-a5b7-fbfd-cf5b-3dce4253f316', 'ENG', 'Hamburger Dressing', NULL),
('6c12b9b9-6505-11ea-f8ce-e6b50d9a2f07', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('6c693ceb-edab-d585-636d-e04dc5a19426', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('6c6e124d-9585-0f24-ef68-048249249e30', 'ENG', 'Lottery & Gambling', NULL),
('6c968f60-a242-7080-f0a6-eaa51650233d', 'ENG', 'cd808e22-799d-d53b-4909-58ce28793563', NULL),
('6c99126a-fe02-1d33-3c3e-32716454b061', 'ENG', 'awasthi_key', NULL),
('6ce57fd0-e876-8c92-7517-6498e152bac3', 'ENG', 'Hamburger Dressing', NULL),
('6cecf067-0d96-3063-aa15-efc912b8a371', 'ENG', 'test204', NULL),
('6e1ea0e0-ecd5-cc88-8a5d-41b0b32f6a00', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('6ed028c8-084d-f771-97cc-1d56aa22ed97', 'SWE', 'Spagetti Carbonara', NULL),
('6f5202bf-ab81-225c-3e5b-6c92b56485d9', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('6f5dd89b-d262-abe1-7084-d8600fc2a561', 'ENG', 'Dressing', NULL),
('6fbd8632-cc46-c1b6-941f-52405c3f1247', 'SWE', 'Primavera', NULL),
('70124bb8-7294-2af3-808f-4f16af95c243', 'GER', 'Heimdekor, Dekoration,', NULL),
('704e0509-9e40-5a75-7fbb-6c9b63147c03', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('70d60e6b-ef7d-49d8-99f1-2c39a716c4c8', 'ENG', 'pasta', NULL),
('70e7680a-3498-2927-7a30-b047752e02e8', 'ENG', 'Dressing', NULL),
('70eeb92d-181e-eb63-1351-84a9cd081633', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('713aea7a-cfc3-ad9f-2231-3eb02bdbce0f', 'ENG', 'Gets', NULL),
('716a46f4-41c3-5f1a-2cc9-5f2e90c224e3', 'SWE', 'Hälsa, sport, ', NULL),
('724f15aa-fd36-46ea-ae62-55953ed00271', 'ENG', '9b5223d6-2802-1ba7-6878-3bcbecb8de6b', NULL),
('72a7a64d-d36e-75a8-42b6-63f93ef41e50', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('72fce243-7abf-e02a-8f7b-1c1134158762', 'ENG', '3329c7e6-239b-d363-5090-e0a19271b90a', NULL),
('734cf13e-d6d9-0989-1139-25dd47f2b320', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('73e6c47e-91d7-6153-a8e8-bf788578e6e2', 'ENG', '7cef78d0-39e7-2012-fa20-c1a0ae1cf744', NULL),
('7406acc5-9d90-71c1-2b7c-cf828850bc7b', 'ENG', '5cad41a8-d939-8995-64df-443b66ad8fcd', NULL),
('7462c298-8bb4-78fd-e284-5002d942f4bb', 'ENG', 'Tests', NULL),
('74afeb3b-3858-ace5-9b63-b66222898871', 'ENG', 'aa962eb9-14bd-0d1a-cd42-52d016170eae', NULL),
('754fd8aa-0e8f-50c0-1050-613fd64700aa', 'SWE', '1a9002dd-e3b6-89c3-862d-405b3715a827', NULL),
('75578805-fc95-9e92-35b8-3943af438229', 'ENG', 'Price:40Rupee', NULL),
('75e34deb-730c-281f-763b-26ce18c08b4d', 'ENG', 'pasta', NULL),
('76174d02-ebec-5b60-fe09-cc08568a5f9b', 'ENG', 'Dressing', NULL),
('78defd9b-f6a1-50c9-e35d-c93595e9a29d', 'GER', '453b8a9c-c1fa-7dca-363a-6e6b21ec4c3b', NULL),
('78f6eec1-e364-5ce1-3e3d-ea164019d0b5', 'SWE', 'Test sponsor & cd', NULL),
('799b3ee7-09cd-6b1b-aab1-2d849ed30038', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('7a8e55ac-5457-11e0-adfa-3c39675c97e2', 'SWE', 'Mat & Snacks', NULL),
('7b15f2fe-2d64-4e21-f57d-1c4aed4a0aac', 'GER', '7b2b5be6-424c-da1a-0b16-3398592599bf', NULL),
('7bc922b5-9f3c-a232-7e18-344f565de27d', 'ENG', 'Shoppers Stop offer', NULL),
('7bf2c28b-5097-11e0-bb28-8a7ed7bb2586', 'SWE', 'Underhållning', NULL),
('7c002b98-9cb3-86f4-bca7-ae192e1142b8', 'ENG', 'new_qwerty_ttle', NULL),
('7d3f8682-d58d-be01-8f22-e043ce614020', 'ENG', '9e66f344-6b7d-3b71-6f04-c6a1fc8b8de3', NULL),
('7dc7ef28-883f-740f-7e14-3491b8ba6bbb', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('7df9e806-19ea-13ed-7611-405764252171', 'SWE', 'pasta', NULL),
('7f440001-ca75-2c34-fa43-bcad7b793dfc', 'ENG', 'fsgsgs', NULL),
('7f576455-416c-6881-8405-46fbc63a8898', 'ENG', 'wer', NULL),
('7fbe8b91-20ea-1551-4fb2-4cfc364b85fd', 'GER', '1000e von Flip Flops zum Schnäppchenpreis', NULL),
('8035ae2d-d166-34cf-5dce-aa4170919f84', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('80440f4d-5299-c09c-7202-0fd46c5228a3', 'ENG', 'Camp1', NULL),
('80569661-ef83-6170-c52d-029d8e15ccba', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('81c510ad-7dbb-6a06-1791-febaedc7c29d', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('81c9c88d-e221-522c-1e68-998074baca6b', 'ENG', 'test204', NULL),
('825441f6-0d6f-36c9-61d7-e99056a52866', 'ENG', '1234', NULL),
('82cb7fad-c7eb-a7b0-9cc4-2ee2db50d601', 'ENG', '$9 discount on test food with a new luxurus tast', NULL),
('83174976-35f8-c716-3cf2-1632c910621c', 'ENG', 'c4f46d83-ee4c-885f-dbb3-277a73d096d5', NULL),
('8335d2b9-3252-569c-43b0-a56bc6ff7cf3', 'ENG', 'Hamburger Dressing', NULL),
('836d2c00-9c52-9897-ab3d-73c530c76c64', 'GER', 'Badeschuhe, Bademode, Gummischuh', NULL),
('837afaaa-dff8-bfa7-e42e-0715c4c601d4', 'ENG', '68c05c4c-0ae1-4459-5471-a9573dcd93e1', NULL),
('839250d7-eefe-7e9c-13fb-a257aa1595ca', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('83c1a31c-2180-5064-7bbd-1d6de1622596', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('83e4b4eb-2593-4b29-59fe-2d035f7120a7', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('8457de1a-ac57-76d8-7ab0-246c5135b33e', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('850618a6-51d3-c15a-6cab-e5d84b786769', 'ENG', 'hair haircut hairdresser barber coiffeur stylist', NULL),
('85071591-86bd-0ca7-93cc-49829ffdfa91', 'ENG', 'awa_desc', NULL),
('851103f0-caf5-3620-2528-d06300619180', 'ENG', 'wets', NULL),
('854ca03e-f798-1a9f-ffc7-e1595c621bf7', 'ENG', 'pasta', NULL),
('8560bac0-bb67-f04c-32a6-7580e96f2906', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('857a5ddc-3814-0a26-1766-ebfa2e9d82fa', 'SWE', 'pasta', NULL),
('857d0e65-7271-6f75-ae37-769feb1dc506', 'ENG', 'Dressing', NULL),
('8657fcc5-f1d1-2794-7476-25da22e0ee59', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('86b1113e-a837-626c-14ea-5dc4cbdc83f2', 'ENG', 'eff42df8-6c4a-9a38-421b-c10c2bb2536d', NULL),
('86d1771b-1b47-7878-0c24-4c8607cbb191', 'GER', 'Bürobedarf, Schreibblock, Spiralblock, Kladde', NULL),
('86e1bc6d-a1ab-1347-49be-aaad1d5d24f1', 'ENG', 'hello', NULL),
('8702915a-5459-11e0-adfa-3c39675c97e2', 'SWE', 'Café', NULL),
('88281421-3978-9ac1-539c-0f7610a9f288', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('884f38e1-c07b-b432-8147-d8a1ae6dc61a', 'ENG', 'Get 50% discount on haircut', NULL),
('88abc1b7-1164-c902-7130-a0ec12f28a8c', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('892e0db5-a9c7-e823-c827-d0432b4c632f', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('89347193-fe6d-d0f3-f745-6a4a0e97e3a7', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('8b849e09-f6d6-239b-6380-001ffdce4fe0', 'GER', ' Keywords: 	Mittagstisch, Asia, thailändisch, asiatische Küche, Thai', NULL),
('8bf34826-b098-c0c2-879e-f21cc8a3e016', 'SWE', '6dabc792-09a1-ca94-90cd-06c65b25db6f', NULL),
('8c3b6f91-59c0-72c3-bf66-12536ff2cac2', 'ENG', 'FirstCamp', NULL),
('8d41a8d8-13bc-b955-4664-6f04bee21631', 'GER', 'Sternzeichen, Tierkreis, Fantasy, Frauen', NULL),
('8dbc159d-d6a6-d037-b419-6a1572fe86b4', 'ENG', 'July offer', NULL),
('8eb4d213-07c0-4d10-652c-29c2d906552d', 'ENG', '6c33b660-b543-a870-8d16-a3a1cf78afc9', NULL),
('8ec1337f-4476-626f-8b3b-f3f1f404cf5b', 'ENG', 'Offer Hurry', NULL),
('8f28ac14-a04d-7f2b-c098-a3fd9d4e72a4', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('8f74b092-0e75-e035-731a-56ac110e5adc', 'GER', '20 % Rabatt auf Mittagsbuffet', NULL),
('8fdb6f90-d10e-ab2e-ca25-26a37d191413', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('90009687-3055-810f-5bc7-1942b4934580', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('900b52e4-717b-5c69-97f4-111b67205380', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('9094afa8-679c-0117-0d39-0537a7f7d66a', 'ENG', 'ecde93d4-4b35-5d94-0d8e-1cbed4baeb7b', NULL),
('90e80721-245c-fcdc-e837-c444dc6dc10e', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('910b0738-9446-2697-ba38-501e223e12e9', 'ENG', 'f87051db-6d4e-f99f-91de-5774ca1913ce', NULL),
('912f8ae0-e799-2d75-d5fb-7b91f4b8f8b6', 'ENG', 'potatis', NULL),
('919cc119-2b2b-f839-9242-7eada63f5bd5', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('92100d36-9714-72dc-02e2-5a661a1d79d4', 'ENG', 'test', NULL),
('923227cb-8fd9-34fd-6296-0fed4064d2bf', 'ENG', 'FirstCamp23', NULL),
('92c0e821-b8dd-8ceb-65e1-4dfc2c183a25', 'ENG', 'Dressing', NULL),
('93814da9-61bc-60f9-f6e4-be8a191b93eb', 'ENG', 'wewe', NULL),
('939615ac-1804-549b-1b9c-f9924fc8f717', 'ENG', 'test204', NULL),
('94f303e7-27da-65d1-7f8d-d00643699468', 'ENG', 'testnew', NULL),
('9634175b-bcc5-f6c1-aa9f-5972dfdac5a3', 'SWE', 'b3b90310-dfd2-4cce-856d-775156c703a2', NULL),
('97fc05ac-cb1f-fd8a-06ca-a22ff22655fc', 'SWE', '83fb70c1-28b0-48cc-bb49-02901170b9c0', NULL),
('97fc33ad-7ca2-31a5-db2f-71a7ba6237bc', 'ENG', 'FirstCampimp', NULL),
('99586932-19ea-59fe-036b-517699ca2a86', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('9988b89b-c32f-34ee-ef91-ed67fec0f8b3', 'ENG', 'c1878232-9147-9341-5b8a-0869d0b0d057', NULL),
('99b9bea4-2e83-2d94-122c-1fad64e87451', 'ENG', 'Lasange8', NULL),
('99cffa38-8d7d-d61a-9db0-520c3f8c0283', 'ENG', 'camp_ttitle_pra', NULL),
('99db5ede-176b-be06-3ec7-4599db5eafad', 'ENG', 'b6836f43-80b1-31ce-526e-ac01d1979aa2', NULL),
('99f31247-ccb3-a36d-9433-b38f6ec3b646', 'ENG', 'Dressing', NULL),
('9b14b058-d970-8bb4-7608-f2a8191f9c36', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('9b3e4953-f2ca-2a77-19af-64cc196fb7c5', 'GER', '20 % Rabatt', NULL),
('9b7a7d5f-ede8-d656-55d8-7ff8dfbfb52f', 'GER', 'Speisekartoffeln kartoffeln', NULL),
('9bda3c28-93cf-4034-33c7-b5e36f8cf349', 'ENG', 'keyword', NULL),
('9c339d68-4197-2dd7-4e4b-d7c95e293f0f', 'GER', 'a2bace66-91e6-505a-b99b-d47be25e2419', NULL),
('9c9b5753-aff0-602c-ed93-dc60dedf0786', 'ENG', '2a41fe10-699d-8ff8-30d1-6ae15705d52b', NULL),
('9de47dc0-96e8-102e-bdd2-12313b062daf', 'ENG', 'Food & Dining', NULL),
('9dfb07ba-7fc6-a43c-c353-0f0a48663a18', 'ENG', 'Dressing', NULL),
('9e31fa36-94f6-5437-0b15-6126f851b2b9', 'SWE', 'pizza', NULL),
('9ef74421-ecfa-17b7-20c7-8716967f429c', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('9f27e332-74b5-19d2-b09c-2b7ad661a52a', 'ENG', '3e562f8b-1f3f-4910-819e-55828eae67d6', NULL),
('9fe9e564-f223-1773-4912-e84547d62fb6', 'ENG', '3dfa2b5b-2edd-00df-061b-998581250935', NULL),
('a01f44cc-80c8-2799-1f09-e1cfa0fa9806', 'GER', 'Buffet Rabatt', NULL),
('a07dd4df-25f2-23a4-0ceb-bba27e8596f2', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('a0844b62-978f-102e-bdd2-12313b062daf', 'ENG', 'Shopping', NULL),
('a0f5b615-bb77-4141-49fd-f8b28661d2f8', 'ENG', 'Hamburger Dressing', NULL),
('a15b9c53-fcaa-ef0d-f8e4-b373ab8debfa', 'ENG', '15b16cd7-0f09-7925-1c72-772896f0efc7', NULL),
('a1a6ba57-1f7e-b1d8-e565-d926228df02b', 'ENG', '5c2b69de-33bd-fa6c-8e2b-db55215c3265', NULL),
('a1c030e5-9571-36fa-f6e9-69bd3c50ba2d', 'ENG', 'Hamburger Dressing', NULL),
('a25832ab-1185-ec56-8e9f-fd152e55a3ff', 'ENG', 'Hamburger Dressing', NULL),
('a28f1a95-7ab6-f08c-5624-b76718e3f451', 'ENG', 'This offer valid for last july days', NULL),
('a29825da-be5e-6567-93e3-8b5f6bce06b1', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('a2dc762c-2623-a3ff-756d-a702f194ad46', 'ENG', 'Dressing', NULL),
('a362ff49-72ba-ec19-a545-1de03554b075', 'ENG', '5d775546-debe-c300-5512-6f6678c3843b', NULL),
('a3b7daf8-d3d3-47f3-2170-fa2e58a6a985', 'ENG', 'Shoppers Stop offer', NULL),
('a3e2695b-f660-0a6d-3075-c959872cc572', 'ENG', 'FirstCamp', NULL),
('a53b24ea-be45-4e65-920b-98b5911b307b', 'ENG', 'test204', NULL),
('a69f3503-bc07-280f-55f9-27c950158ee0', 'ENG', 'Dressing', NULL),
('a6e15c24-6824-9628-048d-f70dd2f9b951', 'ENG', '8e5c343a-51da-1e4c-c93b-52d03702b68b', NULL),
('a77a8ceb-baf4-fdb7-1b14-6f737440b670', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('a8bfcec4-dc5c-cf27-9fb9-c8db96ab80cc', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('a8e7caf9-7819-4c1f-ee34-20774b1ab09a', 'ENG', 'Dressing', NULL),
('a97702de-ae47-8109-a5e6-c5df560c7cab', 'ENG', '14a4e21f-1f28-d844-ab1d-cc99a82e8d71', NULL),
('a981c34e-96e9-102e-bdd2-12313b062daf', 'ENG', 'Food & Snacks', NULL),
('aad7636b-fa78-9402-75ef-59a7fb4806ef', 'ENG', 'FirstCamp', NULL),
('ab72e4a5-9359-dc74-dea6-996d8c27cc8e', 'GER', 'Restaurants', NULL),
('ab91fa2e-3488-6d7b-4461-c05b141e9d86', 'SWE', 'Test ÅÄÖ åäö', NULL),
('ac049e78-954e-da3b-0e63-86db41dd4558', 'SWE', '3a30b3b0-1d18-f005-dad3-f1955dea1d3a', NULL),
('ac863cde-66f3-ce30-4e5c-043ee086e2c8', 'ENG', 'b887ee0c-bc01-f7b9-cfa1-fb03b748e146', NULL),
('ac97cbd8-27ea-165e-245e-ffa4a64eb11f', 'ENG', 'test', NULL),
('ad1d3669-fadf-ee57-d000-f83c4bc06c5f', 'SWE', 'Pris:65Kr', NULL),
('ad601a13-ec9c-45f9-2f4a-b8b5e2e9051d', 'ENG', 'Dressing', NULL),
('ada7f7e5-41ec-772d-d8ed-bad596c5805f', 'ENG', 'Hamburger Dressing', NULL),
('ae94ceda-a2a4-9c5e-3a01-7dd20c87cf36', 'ENG', '50% Discount', NULL),
('af4af80b-6ca8-b7d7-936e-a49961163dcd', 'SWE', 'sponsor', NULL),
('afc11916-57b9-c06e-ca28-c2e68d713820', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('afd1c773-e1be-0e6c-34b8-be5c01cd28ac', 'ENG', 'Hamburger Dressing', NULL),
('b011382c-8a94-928a-dbcb-52e35fc98779', 'ENG', 'adsasdasd', NULL),
('b10cba6d-d2e8-de90-61c9-7f0207b597c4', 'ENG', 'Hamburger Dressing', NULL),
('b12340cd-b306-1663-9ff4-0909851c0598', 'ENG', ' Heimdekor, Dekoration, ', NULL),
('b1502dfc-656e-363b-d9fc-44785b29109c', 'ENG', 'pasta', NULL),
('b18db2a8-49f9-1c2b-e387-f4eabea2bef2', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('b21ca507-6a0e-c912-995e-6d42cbe19242', 'SWE', 'Lasange2', NULL),
('b31c118a-851b-6730-9b55-00a3811ce486', 'GER', 'Café', NULL),
('b34a3c92-f69b-af38-2acf-1aa8035d3b5f', 'ENG', '76c4b725-21c2-cfa5-4dc2-534a22a731c7', NULL),
('b3b23673-9574-a5c6-5b87-673aab3a2a52', 'ENG', 'Test Campaign', NULL),
('b4c23845-9d2c-6139-50bc-3fa3f0af5769', 'SWE', 'Lasange2', NULL),
('b4e824f8-cfaf-6178-94d2-7c0c565f9d7b', 'ENG', 'b39a2259-35f0-971e-2b60-58b0aa3e8182', NULL),
('b4ed3bb8-51d9-6b18-4f7a-d3e5226cd90e', 'SWE', 'såsser dressing sallad', NULL),
('b640567d-bdae-dfa8-3159-5fcda1887e77', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('b6856c67-9d39-4b00-80b9-30165a6868de', 'ENG', 'f23b5c44-94d7-388e-a098-29a17319ac43', NULL),
('b68c8520-fe2e-9b40-abac-67b1f69852f1', 'ENG', 'Hamburger Dressing', NULL),
('b6cd4688-35d9-1df7-623c-369041052977', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('b6d6c16f-6cfd-50a9-5c1a-6d0de60bb762', 'SWE', 'dbf9a46d-5ec6-d947-637e-538dbb3cda68', NULL),
('b6f4f46c-4969-ea51-4982-83857193c4a9', 'ENG', 'test204', NULL),
('b6fde6e8-25dd-ceed-4f0b-873c5df3da29', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('b716e5ac-a6f3-326d-1ea6-4523613b4784', 'ENG', '3e562f8b-1f3f-4910-819e-55828eae67d6', NULL),
('b7f76f8e-3ec3-51cb-dec3-f658dd2bd0e8', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('b8365762-0f85-5972-2657-2a4476ad1863', 'SWE', 'e6160fde-ba3d-132d-2643-e587edf86fa4', NULL),
('b88933c2-cb99-cf9f-93d7-01cd5727deba', 'ENG', 'pasta', NULL),
('b91ac1fa-954b-19f9-b17c-8d9ceb875da3', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('b9976402-e883-76b4-547e-483eabb826ca', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('b9ccf189-5bbb-1180-ab36-11fb23c5ec8a', 'ENG', 'sdfsdfsd', NULL),
('b9e46f21-7446-d66c-d1f5-bf9fe1ac6557', 'ENG', '5ab98245-6964-3690-ea74-b52e91e66701', NULL),
('bb141c68-9fc8-ad32-f20c-b64db7fba1cd', 'ENG', 'Hamburger Dressing', NULL),
('bb47477c-4e7e-3cd2-f418-759174871c94', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('bb4ecb9e-5c6a-f20a-1c09-3662e1924148', 'ENG', 'Hamburger Dressing', NULL),
('bb62ce33-dfc7-8154-0ac9-9e1debcbc944', 'ENG', 'test204', NULL),
('bc210224-9853-47ae-86d9-3703bcae1839', 'ENG', 'Test Campaign', NULL),
('bc9f8d93-ae9a-3c36-9732-1766654556aa', 'GER', 'e27366bb-2d86-0fd9-9ea8-3ce5edfb755d', NULL),
('bca70d64-e9e8-8fb2-52b2-808061fe7971', 'ENG', '6f1d49c2-76b0-57b1-8c05-80e7780d1ecd', NULL),
('bd0d84af-486a-6c47-ea8b-5d335b1447b0', 'ENG', 'Offer valid for Gurgaon shoppers stop', NULL),
('bd1e4adc-c2c4-a8c8-a791-cd7a04ae1ea8', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('be402f4b-97bb-111d-a5eb-11e157cf1618', 'ENG', 'FirstCampimp34', NULL),
('be91e5f2-d151-c0ae-9d0f-400108d2f399', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('befa4fec-11c5-1db4-67fc-e7eed753c95a', 'ENG', 'Hello', NULL),
('bf16b9a6-e39e-76ea-4e79-ec19d66a1a43', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('bf465785-c953-d883-2340-efe1e7cb1c6e', 'ENG', 'ca33b9b4-3f2b-151f-7d88-167354bc1444', NULL),
('c04be2d1-db46-7989-6296-67b1b510b0f6', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('c056ce5b-965a-b7ad-14eb-af5e5d60be00', 'ENG', '1023', NULL),
('c0a29a3c-b6ce-6a2c-b1c0-291d36354954', 'ENG', 'camp_desc', NULL),
('c1881255-d815-a31d-a288-edd0973b47f2', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('c18bd138-4500-3552-5047-cff6233f3295', 'ENG', '45859466-bf84-1edd-e127-99ec76e97aba', NULL),
('c19b19f4-69aa-02d8-2220-5378c253fd93', 'ENG', 'TEstst', NULL),
('c1ca2030-d8a1-0320-76f4-679da25f5636', 'ENG', 'Books & Magazines', NULL),
('c2a0a1af-240d-8f6b-b427-1e6312c50246', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('c2a14fb8-611e-a4b2-1353-519ebe47417c', 'ENG', '2bf16a45-df8a-fa77-9c31-16c0dbb94452', NULL),
('c2ec5373-51aa-a535-f5f2-5e5c3bc2f49a', 'ENG', '2b2cb770-089e-b0af-4abc-af8b5c8de00d', NULL),
('c3640ce0-0dfa-6862-e157-64e9b911bc8a', 'SWE', 'pasta', NULL),
('c49dae12-83a6-8b83-2f78-503b3057ff60', 'ENG', 'ee7992f2-963b-ccb7-617a-64007165b34d', NULL),
('c4d50d63-ff56-5ffd-2d1f-43a6050d907e', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('c622983a-8b77-a385-d526-0732a1f59db3', 'SWE', 'Böcker & Tidningar', NULL),
('c62d2069-b454-f285-3bad-dd0b1122dfc2', 'SWE', 'Lasange3', NULL),
('c636d99d-cc42-e497-97bd-1e76cdba6750', 'ENG', 'qsdfsdf', NULL),
('c66e116a-44dd-9bb4-a079-ac322bc7ef82', 'ENG', 'Hamburger Dressing', NULL),
('c70daa0c-ad7b-bff8-1971-3dfee4adf498', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('c75e2c4f-8c71-df7f-2c37-407bf453b1cd', 'GER', 'b4a451f3-6c68-b81c-d7b0-3d4bc6e20b02', NULL),
('c7bea401-f978-6736-dbd7-d2ac40e6d8d8', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('c825fc96-3979-eb06-b743-ca498247c6a0', 'ENG', '3e562f8b-1f3f-4910-819e-55828eae67d6', NULL),
('c82bb32b-0ab4-0310-9e10-e2e2f155f594', 'ENG', 'test2010second', NULL),
('c879b3a3-2b07-a8bf-1ddd-b4727272de7e', 'ENG', 'Hamburger Dressing', NULL),
('c898231e-f7f2-ca0e-1d4e-33383d1308d3', 'ENG', '2874f224-d7af-3e92-82b0-07faacf63dea', NULL),
('c8c61dae-666a-914e-664d-fcf7709014ee', 'ENG', 'pasta', NULL),
('c8f87184-4166-33b0-2ba4-04b5541a6098', 'GER', 'Gesundheit & Wellness', NULL),
('c90f6fa4-529a-f5dc-0b62-c16394051ead', 'ENG', 'Hamburger Dressing', NULL),
('c9e37df2-84ce-0d52-ba3e-75b08cfc1aaa', 'ENG', 'aeac7c7c-9bf2-6803-2fb4-62bcf2d0c699', NULL),
('ca3af2f3-6b90-0378-9d20-b3ca637609b0', 'ENG', 'Hamburger Dressing', NULL),
('ca47b7db-cd44-4196-5b86-7b0e115f6a50', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('cad4dd64-8d82-c3dc-4712-4432bb0de76f', 'SWE', 'pizza', NULL),
('cb2cd98c-dbf8-6665-586a-841a1413d29f', 'ENG', 'Dressing', NULL),
('cb8de114-a0a0-18e8-c6c4-bd36a8d22a60', 'ENG', '', NULL),
('cb8e1b97-dd5b-9f21-8694-7c042e0a237c', 'ENG', ' Kissen Schwarz We', NULL),
('cc2c9a32-c2a3-4732-c0f6-c5550f594de5', 'ENG', 'Dressing', NULL),
('cc48cb5a-cd44-f089-2d19-acfb1122e975', 'ENG', 'bd0accc4-2539-3f3c-25bd-72e1d40d414a', NULL),
('cc7ff8a1-6bf6-3e67-84a0-f8dd89345c0c', 'SWE', 'efaaeb70-19f4-a6f5-0845-90741dcc6117', NULL),
('cc8b7af5-4e6a-2fa9-8d2c-5f994aa4529e', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('cd1f928a-cf2f-b788-7ae0-06eb6ffc2758', 'SWE', 'pasta', NULL),
('cd3df440-11e9-759b-5565-8bfd904ef189', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('cd64bc78-4085-fc7d-1e6e-8563337f0540', 'SWE', 'pizza', NULL),
('cdb6c6db-ab99-56aa-c427-7b150afafa57', 'GER', 'foto', NULL),
('cdba0192-6892-0aa5-9313-152a85476b90', 'SWE', 'f51f0fb4-856d-074b-5c7c-75a319abf695', NULL),
('cedd2bc5-a463-a3d4-e090-15f7417b1775', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('cf26a8a1-1542-09ec-0258-7d65fd689432', 'ENG', 'Hamburger Dressing', NULL),
('cf5693db-daa6-bede-f512-60faccdef253', 'ENG', 'Buy 2 scotch shots and get 2 free', NULL),
('d016bb85-00a9-6e73-6173-71be610ee5c5', 'ENG', 'sdfdsf', NULL),
('d0f24876-615e-09a5-b684-b9d0cb64d95a', 'ENG', 'Dressing', NULL),
('d10e9eef-e2db-1d85-0658-c75aedcb38df', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('d11d243c-52a3-2bc3-6c7d-87a015dc9430', 'SWE', 'Lasange', NULL),
('d1e5416e-2108-07c4-d193-39679ce5fefd', 'ENG', '3e562f8b-1f3f-4910-819e-55828eae67d6', NULL),
('d255f5cf-c394-f255-677c-b1efc4ccd5f0', 'ENG', 'test2010first', NULL),
('d2c5eb50-5456-11e0-adfa-3c39675c97e2', 'SWE', 'Shopping', NULL),
('d2e74fce-3bcc-5851-d6f9-7a1d9e5c5699', 'GER', 'Kartoffel', NULL),
('d3818a52-1680-e95a-8447-3889089cec9d', 'ENG', 'test204', NULL),
('d38c8ec2-1d06-9c3a-0355-650654b8f15a', 'ENG', '2c758c69-393e-3879-a471-0dcb9e589a59', NULL),
('d3c82518-b146-b06d-8a1e-d4dc57f1e707', 'ENG', 'test2010first', NULL),
('d426db5e-e9f0-648d-dfdc-b72fa7a71a2c', 'ENG', 'test204', NULL),
('d4431e06-9e75-e724-bff2-fbf1e36d6b31', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('d4678d5a-5089-abb9-6247-1b85a13b17cf', 'ENG', 'Neue Kollektion von Kissen im Schwarz-Weiß Design', NULL),
('d4a5ec57-f656-aab3-505e-068c9e4fba48', 'ENG', 'pasta', NULL),
('d4e2f0ee-59af-1cf8-12e6-5aca763dc620', 'GER', 'Flip Flop Mania', NULL),
('d5265492-0418-3ad0-c465-e796b2e8e6d4', 'ENG', 'a9002ea6-96fd-8ee0-c6a5-9f4e2fb2889a', NULL),
('d5d0684d-019f-64a4-c3e9-47ea31b5ce1c', 'ENG', 'test2010', NULL),
('d65174d9-cf05-abaf-7274-bdd35b229ec5', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('d695cef8-cc7d-6780-6e0c-c5126ecdc802', 'GER', 'Pris:11Kr', NULL),
('d70f9cf5-69e1-73d9-e16d-c3922393ee08', 'ENG', '0fc7f813-5762-b82b-9d5e-1722698c84b4', NULL),
('d760f5ef-5efb-dfcf-1b08-b603e0de60ba', 'ENG', '707d4542-9a27-31ea-a53e-1dff2789820e', NULL),
('d7f58408-0dd9-91e0-5ea5-15b8b335d93a', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('d800d6ec-b2f0-a59e-5f8b-2f367f2b378e', 'SWE', 'mindre öäåÅÄÖ æüÜ', NULL),
('d8161ec1-bd0b-5028-233d-61647c5028bc', 'ENG', 'test2010second', NULL),
('d876ea49-5459-11e0-adfa-3c39675c97e2', 'SWE', 'Dryck & Drinkar', NULL),
('d8c15991-2988-cb53-a51b-ab901b1527f1', 'ENG', '50f01917-ef5a-cfb6-56d3-4743e13c40ae', NULL),
('d91c2321-53a1-97c3-ebcd-7107e1da43f4', 'ENG', 'Dressing', NULL),
('d9a3153b-0cbb-4b5b-5c14-dbe652316f74', 'ENG', 'Hamburger Dressing', NULL),
('d9c83c70-be18-0bf3-cd4f-20ae11951355', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('dbd5b1ee-ca80-9820-4156-6faf2c08ee7f', 'ENG', 'd171b0e1-2b3b-e176-0ea4-7c6c335562b4', NULL),
('dc2965c3-37c8-f97f-7fbd-7e503be97158', 'ENG', 'Dressing', NULL),
('dd204a84-1872-7058-175a-b1f89e254ada', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('dd6272ce-2b13-8997-a41f-c4ba654880cb', 'SWE', 'dressing', NULL),
('dd9f7f87-6fb5-a485-3bc9-fbca5ca3a7c4', 'ENG', 'dbcc81bb-cb6d-6e9b-7b9f-4a67cd3e2579', NULL),
('ddc21276-c09b-ce64-0585-691f5ebc8d91', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('ddf2d540-df93-4007-e712-5658388ef6d9', 'GER', 'Kamera LEICA', NULL),
('de0de14c-1990-1d37-fdc0-96e95a85908f', 'ENG', 'awa_tittle', NULL),
('df26c37d-e627-00cf-d8da-1d0b0b673658', 'SWE', '65aa77fd-d3c6-7761-8293-f4ecf9d234c3', NULL),
('e046dd37-ec1c-a02e-28b2-bd3be09a1e8e', 'ENG', 'Camp 1  for daily app', NULL),
('e0ba687c-7b65-1d90-aa53-3964f0edbc97', 'ENG', 'ec42a8b2-f7b3-8df2-fef6-b25cf7d38244', NULL),
('e139b9c4-ebdc-62b6-7b8c-3a19b92c4ad1', 'SWE', 'Tjänster & Service', NULL),
('e1a3a08a-7111-1078-725b-d67ee5cac40f', 'ENG', '4ee1a08f-08c3-5d6d-9257-345298018100', NULL),
('e1ca8a54-61d8-c22b-5dcd-ab6dd36b7fba', 'ENG', 'Hamburger Dressing', NULL),
('e27a1bf2-e30f-6e6a-f5df-8d44c8e74190', 'ENG', 'pasta', NULL),
('e29058d3-c93c-bbae-2877-c808400e62ac', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('e2dc262f-9f25-a539-615a-1e9ba582e7c4', 'ENG', 'wewew', NULL),
('e2dd5c92-fcf8-2074-0471-f49af392ab2b', 'ENG', 'pras_key', NULL),
('e3225414-14d0-6eeb-48d7-66487a0f3675', 'ENG', 'Dressing', NULL),
('e468d2fd-78cd-cba7-3025-6fa8804e80a0', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('e47fd018-9fa4-1826-966c-bab85c182fb6', 'ENG', '2fb91936-b246-811b-9ad9-2d2e3d7c9e09', NULL),
('e5835a77-3f93-7647-37a9-91fc711adec7', 'ENG', 'Hamburger Dressing', NULL),
('e5be69b1-3e62-1f85-1ada-8e0aa53b4725', 'GER', 'Bürobedarf, Schreibblock, Spiralblock, Kladde ', NULL),
('e6abea27-8b4f-4213-dcfc-75f42c4902da', 'GER', 'Buffet Rabatt', NULL),
('e71e9815-dd6f-95e1-8148-dbe2652de68f', 'SWE', '8b47d540-e0d1-f09d-a253-0ff904725820', NULL),
('e7233c92-b086-a521-8dcd-af84cacceed6', 'ENG', 'c0489739-891d-a9f4-6a93-57f36e7938ed', NULL),
('e7bc4625-92f1-3295-51c6-4229ebfc1814', 'ENG', 'Dressing ', NULL),
('e80e9411-ade1-94d2-3dcb-6721c5e645fb', 'ENG', '2916686d-8502-91e4-c917-e06f94c24af8', NULL),
('e83dff0f-31b0-82cb-1be4-f0bdd8444e2c', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('e8696e6e-a888-aeb3-6a6c-609eda2d4108', 'GER', 'Pris:20Kr', NULL),
('e87ac217-4d9d-4557-605c-53fefed88b0e', 'ENG', '276156d8-b3a4-1f0d-8bf4-4592286ea03b', NULL),
('e8804530-8e51-dd73-aeaf-da1ee03dc88f', 'GER', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('e8a0e0d0-23df-c3f2-b002-b792963e2fe7', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('e8e3537f-d1c3-f5d9-b78f-6e108285549c', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('ea3dff57-9f19-3fe9-46e2-d84c4c253ea4', 'SWE', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('eb6530af-3aed-19d3-0517-d585be34fc98', 'ENG', '58da5c21-4121-cce9-bd28-303b09533108', NULL),
('eb905001-f0d1-6693-db10-12ea5729a965', 'ENG', '36734f10-cdad-5329-9b4c-78b0c643d3b7', NULL),
('ed6252ce-b40b-cec0-c49e-5a1ee13bffac', 'ENG', 'c00cb55d-87e2-b4c7-1a18-025f7e9a7b58', NULL),
('ee277138-1a97-851a-2baf-bd5d84a9e21e', 'ENG', '33d1e1ff-6c39-1544-5b95-2af84652ed35', NULL),
('ee451b43-85a2-59af-e28f-41e66809e037', 'ENG', 'Dressing', NULL),
('ef10e205-bc15-3f91-eff5-c3e901fe7a5e', 'SWE', '0a364426-d0dc-a35c-c676-e00e31d8d5a7', NULL),
('ef14edf5-df28-8c01-38c8-66ba00f5c396', 'ENG', 'asdasd', NULL),
('f01dcb23-1d97-5446-f442-1f7c0eb4bfe4', 'ENG', '8f47ed8e-8935-d478-d47e-2f9fef76803b', NULL),
('f028f530-ebc2-9447-8139-c9f9eabf97cd', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL),
('f097a5cc-f6b5-d9c9-d8e7-a285553b7c55', 'SWE', 'mindre öäåÅÄÖ æüÜ Brand', NULL),
('f0a72a29-60e5-6767-ea82-29ff049b977f', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('f0b9fb21-a619-6d19-293f-2b54672f8112', 'ENG', 'b961a53a-65cc-954a-f69f-025776fcf489', NULL),
('f0cbe2c5-e05c-da2e-80bf-06baf2178113', 'ENG', 'Other', NULL),
('f0d97bac-ced4-d7d1-81d9-12ab96d8b526', 'ENG', 'Lasange9', NULL),
('f2729846-483e-ad7b-e492-e0b8871559dc', 'GER', '50% Discount', NULL),
('f298bcd9-dd6f-1215-ad91-89619a4019c9', 'ENG', '23d7811a-f76e-7b89-29f2-78ac1e9fa59e', NULL),
('f30e676c-caf5-8089-2d04-af00fc467664', 'ENG', 'pasta', NULL),
('f3b2bf23-c89f-407a-1ebe-0a6a50237e7e', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('f4318841-cc50-e8ed-ea9f-33ae798e93c6', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('f46adceb-c97c-3473-5942-c9e543eca5cb', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('f4e3e9f4-139c-f41b-9688-70d9d38fa850', 'GER', 'Pris:10Kr', NULL),
('f506d55e-f52c-8074-59cc-349a92586712', 'ENG', '375ad461-0af2-dc33-3065-f72d2cfe77b8', NULL),
('f5a1defb-1494-64e9-4ca1-cb15b24f6d23', 'ENG', 'Dressing', NULL),
('f5ae97ec-bd4e-36f8-8964-e24a0c613435', 'GER', '50ea0023-5b9d-ab73-22e1-d20b8218f7f7', NULL),
('f685c916-0ef3-f2bd-c965-25ec24b9cdbc', 'ENG', 'Hamburger Dressing', NULL),
('f736c2f3-6766-a428-56ca-38ad2a036243', 'ENG', 'sdfsf', NULL),
('f7ace839-7092-a2c1-7a19-9fefa9a4466b', 'GER', 'Kissen Schwarz Weiß', NULL),
('fa9a3544-dc63-3892-cdb5-09a369a04e8b', 'ENG', '4adc6df7-58d2-9020-f705-eabd2687daf0', NULL),
('fb391dd7-ee40-d19b-cce9-8457bbc893f7', 'ENG', 'test2010', NULL),
('fb540ab2-7e91-1dc6-7f25-1613d7b9a426', 'ENG', 'Dressing', NULL),
('fbb1ad67-db37-5465-4a81-d805ed95aedb', 'ENG', 'Hamburger Dressing', NULL),
('fc0906fd-2345-a94b-e5da-9e45c7c7ac3c', 'ENG', 'T-Shirts, Bikers, Tanks - nur 6,00 Euro', NULL),
('fc2a6aa7-dcc9-1ba4-1d8e-8b180d8625ca', 'ENG', 'pasta', NULL),
('fc6fffcd-f759-a706-09a5-a346aa4cf1f6', 'GER', '21ce1a74-551c-a393-10dc-3745ee55317c', NULL),
('fd8ec5a6-050a-8f79-30f9-e8dd40f816d8', 'ENG', '50% Discount', NULL),
('fd9021a6-5c7f-1979-3887-6247c255a8b3', 'ENG', '3702f071-6319-2a33-cd45-544ef0057236', NULL),
('fdd3145f-08dc-b691-f4c0-4379780664ad', 'ENG', 'test204', NULL),
('fe7a161e-6792-9f95-1c58-2ee7ce763c63', 'ENG', '45859466-bf84-1edd-e127-99ec76e97aba', NULL),
('fe7d5f92-097a-4f3b-f8d8-ff7c5aa33f3b', 'ENG', 'pasta', NULL),
('fe833ad8-d034-67d2-b2fc-f54a8584a968', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('fef9e9b0-d7bf-c903-3109-955b790450e8', 'ENG', '$1 discount on dressing with a new luxurus tast', NULL),
('fefb3ad1-bb51-4386-94ba-0401902b993e', 'SWE', 'Mama Mia', NULL),
('ff988967-6443-85cc-bc85-fc82d0da14f2', 'ENG', 'cfdfddbb-340e-fdba-abd2-5862812f515e', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `limit_period`
--

CREATE TABLE `limit_period` (
  `limit_id` char(36) NOT NULL,
  `end_time` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `valid_day` varchar(255) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_12_11_092023_create_todos_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_order_id` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `user_id` int(100) NOT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `order_type` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `deliver_date` varchar(255) DEFAULT NULL,
  `deliver_time` varchar(255) DEFAULT NULL,
  `check_deliveryDate` date DEFAULT NULL,
  `order_total` double DEFAULT NULL,
  `order_delivery_time` varchar(40) DEFAULT NULL,
  `order_ready` tinyint(1) NOT NULL DEFAULT '0',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `online_paid` tinyint(1) NOT NULL DEFAULT '0',
  `ready_notifaction` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `product_quality` int(100) NOT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `order_started` tinyint(1) NOT NULL DEFAULT '0',
  `order_ready` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_date` date DEFAULT NULL,
  `is_speak` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

CREATE TABLE `partner` (
  `partner_id` char(36) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `orgnr` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `user_id` int(100) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `amount` int(100) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `balance_transaction` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` char(36) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `dish_type` int(10) DEFAULT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `preparation_Time` time DEFAULT NULL,
  `brand_name` varchar(100) DEFAULT NULL,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `category` char(36) NOT NULL,
  `start_of_publishing` datetime NOT NULL,
  `is_sponsored` tinyint(1) NOT NULL COMMENT '0=No, 1= Yes',
  `coupon_delivery_type` varchar(255) DEFAULT NULL,
  `offer_type` tinyint(3) NOT NULL DEFAULT '0',
  `product_info_page` varchar(255) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=No, 1= Yes',
  `ean_code` int(11) DEFAULT NULL,
  `product_number` varchar(255) DEFAULT NULL,
  `u_id` char(36) NOT NULL,
  `company_id` char(36) NOT NULL,
  `s_activ` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Active, 2=Deleted',
  `reseller_status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_keyword`
--

CREATE TABLE `product_keyword` (
  `product_id` char(36) NOT NULL,
  `offer_keyword` char(36) DEFAULT NULL,
  `system_key` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_offer_slogan_lang_list`
--

CREATE TABLE `product_offer_slogan_lang_list` (
  `product_id` char(36) NOT NULL,
  `offer_slogan_lang_list` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_offer_sub_slogan_lang_list`
--

CREATE TABLE `product_offer_sub_slogan_lang_list` (
  `product_id` varchar(255) DEFAULT NULL,
  `offer_sub_slogan_lang_list` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_list`
--

CREATE TABLE `product_price_list` (
  `product_id` char(36) NOT NULL,
  `store_id` char(36) NOT NULL,
  `text` varchar(300) NOT NULL,
  `price` double NOT NULL,
  `lang` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reselleragrement`
--

CREATE TABLE `reselleragrement` (
  `u_id` char(36) NOT NULL,
  `store_email` varchar(255) NOT NULL,
  `store_mphone` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `raddr` varchar(255) DEFAULT NULL,
  `auth_metode` varchar(255) DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` char(36) NOT NULL,
  `u_id` char(36) DEFAULT NULL,
  `store_type` tinyint(1) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `country_code` varchar(2) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `store_link` varchar(255) DEFAULT NULL,
  `s_activ` tinyint(1) DEFAULT NULL COMMENT '1=Active, 2=Deleted',
  `version` int(11) DEFAULT NULL,
  `access_type` tinyint(4) NOT NULL COMMENT '0=public, 1=private',
  `chain` varchar(256) NOT NULL,
  `block` varchar(256) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `store_image` varchar(255) DEFAULT NULL,
  `store_open` time DEFAULT NULL,
  `store_close` time DEFAULT NULL,
  `store_open_days` varchar(255) DEFAULT NULL,
  `store_open_close_day_time` varchar(255) DEFAULT NULL,
  `store_close_dates` varchar(255) DEFAULT NULL,
  `online_payment` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `store_open_close`
--

CREATE TABLE `store_open_close` (
  `id` int(11) NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_open_close`
--

INSERT INTO `store_open_close` (`id`, `open_time`, `close_time`, `created_at`, `updated_at`) VALUES
(1, '00:00:00', '00:00:00', '2018-02-14 18:30:00', NULL),
(2, '01:00:00', '01:00:00', '2018-02-14 18:30:00', NULL),
(3, '02:00:00', '02:00:00', '2018-02-14 18:30:00', NULL),
(4, '03:00:00', '03:00:00', '2018-02-14 18:30:00', NULL),
(5, '04:00:00', '04:00:00', '2018-02-14 18:30:00', NULL),
(6, '05:00:00', '05:00:00', '2018-02-14 18:30:00', NULL),
(7, '06:00:00', '06:00:00', '2018-02-14 18:30:00', NULL),
(8, '07:00:00', '07:00:00', '2018-02-14 18:30:00', NULL),
(9, '08:00:00', '08:00:00', '2018-02-14 18:30:00', NULL),
(10, '09:00:00', '09:00:00', '2018-02-14 18:30:00', NULL),
(11, '10:00:00', '10:00:00', '2018-02-14 18:30:00', NULL),
(12, '11:00:00', '11:00:00', '2018-02-14 18:30:00', NULL),
(13, '12:00:00', '12:00:00', '2018-02-14 18:30:00', NULL),
(14, '13:00:00', '13:00:00', '2018-02-14 18:30:00', NULL),
(15, '14:00:00', '14:00:00', '2018-02-14 18:30:00', NULL),
(16, '15:00:00', '15:00:00', '2018-02-14 18:30:00', NULL),
(17, '16:00:00', '16:00:00', '2018-02-14 18:30:00', NULL),
(18, '17:00:00', '17:00:00', '2018-02-14 18:30:00', NULL),
(19, '18:00:00', '18:00:00', '2018-02-14 18:30:00', NULL),
(20, '19:00:00', '19:00:00', '2018-02-14 18:30:00', NULL),
(21, '20:00:00', '20:00:00', '2018-02-14 18:30:00', NULL),
(22, '21:00:00', '21:00:00', '2018-02-14 18:30:00', NULL),
(23, '22:00:00', '22:00:00', '2018-02-14 18:30:00', NULL),
(24, '23:00:00', '23:00:00', '2018-02-14 18:30:00', NULL),
(25, '23:59:00', '23:59:00', '2018-04-01 18:30:00', '2018-04-01 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_receipt`
--

CREATE TABLE `transaction_receipt` (
  `id` bigint(20) NOT NULL,
  `client_id` char(36) NOT NULL,
  `coupon_id` char(36) NOT NULL,
  `partner_id` char(36) DEFAULT NULL,
  `partner_ref` char(100) DEFAULT NULL,
  `purchase_time` datetime NOT NULL,
  `store_id` char(36) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_receipt_history`
--

CREATE TABLE `transaction_receipt_history` (
  `id` bigint(20) NOT NULL,
  `client_id` char(40) NOT NULL,
  `coupon_id` char(36) NOT NULL,
  `partner_id` char(36) DEFAULT NULL,
  `partner_ref` char(100) DEFAULT NULL,
  `purchase_time` datetime NOT NULL,
  `store_id` char(36) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `u_id` varchar(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` char(64) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `role` varchar(16) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `saddr` varchar(255) DEFAULT NULL,
  `street_addr` varchar(255) DEFAULT NULL,
  `city_addr` varchar(255) DEFAULT NULL,
  `home_zip` varchar(255) DEFAULT NULL,
  `country` varchar(5) DEFAULT NULL,
  `caddr` varchar(255) DEFAULT NULL,
  `resellers_bank` varchar(255) DEFAULT NULL,
  `social_number` varchar(255) DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `company_id` char(36) DEFAULT NULL,
  `store_id` varchar(36) DEFAULT NULL,
  `activ` tinyint(1) DEFAULT NULL,
  `temp` varchar(255) DEFAULT NULL,
  `email_varify_code` varchar(255) NOT NULL,
  `language` varchar(255) DEFAULT NULL,
  `text_speech` tinyint(1) NOT NULL DEFAULT '0',
  `access_token` varchar(255) DEFAULT NULL,
  `stripe_publishable_key` varchar(255) DEFAULT NULL,
  `stripe_user_id` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` varchar(32) CHARACTER SET latin1 NOT NULL,
  `user_id` varchar(36) CHARACTER SET latin1 NOT NULL,
  `support_user_id` varchar(40) CHARACTER SET latin1 NOT NULL,
  `session_id` varchar(40) CHARACTER SET latin1 NOT NULL,
  `in_time` datetime NOT NULL,
  `out_time` datetime NOT NULL,
  `user_type` int(1) NOT NULL DEFAULT '0' COMMENT '0:Normal User,1:Support User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_support`
--

CREATE TABLE `user_support` (
  `u_id` varchar(36) CHARACTER SET latin1 NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `passwd` char(64) CHARACTER SET latin1 NOT NULL,
  `fname` varchar(50) CHARACTER SET latin1 NOT NULL,
  `lname` varchar(50) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `web_version`
--

CREATE TABLE `web_version` (
  `id` int(11) NOT NULL,
  `version` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `web_version`
--

INSERT INTO `web_version` (`id`, `version`, `created_at`, `updated_at`) VALUES
(1, '1.1', '2018-04-05 07:01:43', '2018-04-05 07:01:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertise`
--
ALTER TABLE `advertise`
  ADD PRIMARY KEY (`advertise_id`);

--
-- Indexes for table `advertise_offer_slogan_lang_list`
--
ALTER TABLE `advertise_offer_slogan_lang_list`
  ADD PRIMARY KEY (`advertise_id`,`offer_slogan_lang_list`),
  ADD KEY `offer_slogan_lang_list` (`offer_slogan_lang_list`);

--
-- Indexes for table `advertise_offer_sub_slogan_lang_list`
--
ALTER TABLE `advertise_offer_sub_slogan_lang_list`
  ADD PRIMARY KEY (`advertise_id`,`offer_sub_slogan_lang_list`),
  ADD KEY `offer_sub_slogan_lang_list` (`offer_sub_slogan_lang_list`);

--
-- Indexes for table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`campaign_id`);

--
-- Indexes for table `campaign_limit_period_list`
--
ALTER TABLE `campaign_limit_period_list`
  ADD PRIMARY KEY (`campaign_id`,`limit_period_list`),
  ADD KEY `limit_period_list` (`limit_period_list`);

--
-- Indexes for table `campaign_offer_slogan_lang_list`
--
ALTER TABLE `campaign_offer_slogan_lang_list`
  ADD PRIMARY KEY (`campaign_id`,`offer_slogan_lang_list`),
  ADD KEY `offer_slogan_lang_list` (`offer_slogan_lang_list`);

--
-- Indexes for table `campaign_offer_sub_slogan_lang_list`
--
ALTER TABLE `campaign_offer_sub_slogan_lang_list`
  ADD PRIMARY KEY (`campaign_id`,`offer_sub_slogan_lang_list`),
  ADD KEY `offer_sub_slogan_lang_list` (`offer_sub_slogan_lang_list`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories_list_of_categories`
--
ALTER TABLE `categories_list_of_categories`
  ADD PRIMARY KEY (`categories`,`list_of_categories`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `category_names_lang_list`
--
ALTER TABLE `category_names_lang_list`
  ADD PRIMARY KEY (`category`,`names_lang_list`),
  ADD KEY `names_lang_list` (`names_lang_list`);

--
-- Indexes for table `ccode`
--
ALTER TABLE `ccode`
  ADD PRIMARY KEY (`ccode`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`iso`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`coupon_id`),
  ADD KEY `store` (`store`);

--
-- Indexes for table `coupon_limit_period_list`
--
ALTER TABLE `coupon_limit_period_list`
  ADD PRIMARY KEY (`coupon`,`limit_period_list`),
  ADD KEY `limit_period_list` (`limit_period_list`);

--
-- Indexes for table `coupon_offer_slogan_lang_list`
--
ALTER TABLE `coupon_offer_slogan_lang_list`
  ADD PRIMARY KEY (`coupon`,`offer_slogan_lang_list`),
  ADD KEY `sub_slogan_lang_list` (`offer_slogan_lang_list`);

--
-- Indexes for table `coupon_offer_title_lang_list`
--
ALTER TABLE `coupon_offer_title_lang_list`
  ADD PRIMARY KEY (`coupon`,`offer_title_lang_list`),
  ADD KEY `offer_title_lang_list` (`offer_title_lang_list`);

--
-- Indexes for table `coupon_usage_statistics`
--
ALTER TABLE `coupon_usage_statistics`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `coupon_usage_statistics_history`
--
ALTER TABLE `coupon_usage_statistics_history`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `c_s_rel`
--
ALTER TABLE `c_s_rel`
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `dish_type`
--
ALTER TABLE `dish_type`
  ADD PRIMARY KEY (`dish_id`);

--
-- Indexes for table `employer`
--
ALTER TABLE `employer`
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `lang_text`
--
ALTER TABLE `lang_text`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `limit_period`
--
ALTER TABLE `limit_period`
  ADD PRIMARY KEY (`limit_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partner`
--
ALTER TABLE `partner`
  ADD PRIMARY KEY (`partner_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_offer_slogan_lang_list`
--
ALTER TABLE `product_offer_slogan_lang_list`
  ADD PRIMARY KEY (`product_id`,`offer_slogan_lang_list`),
  ADD KEY `offer_slogan_lang_list` (`offer_slogan_lang_list`);

--
-- Indexes for table `product_price_list`
--
ALTER TABLE `product_price_list`
  ADD PRIMARY KEY (`product_id`,`store_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `reselleragrement`
--
ALTER TABLE `reselleragrement`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `store_open_close`
--
ALTER TABLE `store_open_close`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_receipt`
--
ALTER TABLE `transaction_receipt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_receipt_history`
--
ALTER TABLE `transaction_receipt_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_id` (`u_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `web_version`
--
ALTER TABLE `web_version`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dish_type`
--
ALTER TABLE `dish_type`
  MODIFY `dish_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_open_close`
--
ALTER TABLE `store_open_close`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaction_receipt`
--
ALTER TABLE `transaction_receipt`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaction_receipt_history`
--
ALTER TABLE `transaction_receipt_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `web_version`
--
ALTER TABLE `web_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaign_limit_period_list`
--
ALTER TABLE `campaign_limit_period_list`
  ADD CONSTRAINT `campaign_limit_period_list_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaign` (`campaign_id`),
  ADD CONSTRAINT `campaign_limit_period_list_ibfk_2` FOREIGN KEY (`limit_period_list`) REFERENCES `limit_period` (`limit_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
