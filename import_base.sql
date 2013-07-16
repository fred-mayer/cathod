-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 16 2013 г., 15:57
-- Версия сервера: 5.5.29
-- Версия PHP: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `fred_cathod`
--

-- --------------------------------------------------------

--
-- Структура таблицы `banner`
--

CREATE TABLE `banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `src` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `level` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_cats`
--

CREATE TABLE `catalog_cats` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `parentid` int(255) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `alias` varchar(255) NOT NULL,
  `hide` int(10) NOT NULL DEFAULT '1',
  `order` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_items`
--

CREATE TABLE `catalog_items` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `group_id` int(255) NOT NULL,
  `hide` tinyint(10) NOT NULL DEFAULT '1',
  `url` text CHARACTER SET utf8 NOT NULL,
  `price` int(255) NOT NULL,
  `currencyid` varchar(255) CHARACTER SET utf8 NOT NULL,
  `catid` int(255) NOT NULL,
  `mag_id` int(255) NOT NULL,
  `picture` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `vendor` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `params` text CHARACTER SET utf8 NOT NULL,
  `price_old` float NOT NULL COMMENT 'Старая цена',
  `sale` int(4) NOT NULL COMMENT 'Скидка',
  `size` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Размер',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_items_params`
--

CREATE TABLE `catalog_items_params` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_item` int(255) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_magazine`
--

CREATE TABLE `catalog_magazine` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `trekking_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL,
  `script_parser` varchar(255) CHARACTER SET utf8 NOT NULL,
  `hide` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `catalog_magazine`
--

INSERT INTO `catalog_magazine` (`id`, `name`, `url`, `trekking_url`, `logo`, `script_parser`, `hide`) VALUES
(1, 'Atlas for Man', 'http://www.atlasformen.ru', 'http://ucl.mixmarket.biz/uni/clk.php?id=1294953531&zid=1294952941&prid=1294931457&stat_id=0&sub_id=zeazon&redir=http://www.atlasformen.ru/', 'atlasformanLogo.png', 'atlasforman.php', 1),
(3, 'Trends Brands', 'http://www.trendsbrands.ru/', 'http://ucl.mixmarket.biz/uni/clk.php?id=1294954555&zid=1294952941&prid=1294931875&stat_id=0&sub_id=&redir=http://www.trendsbrands.ru', 'tblogo-small.png', 'trendsbrands.php', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_mag_cats`
--

CREATE TABLE `catalog_mag_cats` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_cat` int(255) NOT NULL,
  `id_mag` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE `content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias_3` (`alias`),
  KEY `alias` (`alias`),
  KEY `alias_2` (`alias`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `core_modules`
--

CREATE TABLE `core_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(32) NOT NULL,
  `params` varchar(255) NOT NULL,
  `exist` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;

--
-- Дамп данных таблицы `core_modules`
--

INSERT INTO `core_modules` (`id`, `title`, `name`, `params`, `exist`) VALUES
(1, 'Контент', 'content', '', 0),
(16, 'Новости', 'news', '', 0),
(17, 'Каталог товаров', 'catalog', '', 0),
(25, 'Каталог', 'catalog', 'true', 1),
(22, 'Категории Каталога Товаров', 'catalog_tree', '', 0),
(26, 'Ктегории Каталога', 'catalog_tree', '', 1),
(35, 'Баннер', 'banner', '', 0),
(45, 'Купоны', 'coupons', '', 0),
(46, 'Купоны от Миксмаркет', 'coupons', '', 1),
(47, 'Купоны список брендов', 'coupons_brand', '', 0),
(48, 'Список брендов', 'coupons_brand', '', 1),
(49, 'Купоны (Тип купона)', 'coupons_type', '', 0),
(50, 'Тип купона', 'coupons_type', '', 1),
(51, 'Купоны (Категория)', 'coupons_category', '', 0),
(52, 'Категории', 'coupons_category', '', 1),
(53, 'Формы обратной связи', 'forms', '', 0),
(72, 'Twitter', 'twitter', '', 0),
(73, 'Twitter profile', 'twitter_profile', '', 0),
(75, 'twitter', 'twitter', '{"id":false}', 1),
(76, 'Twitter profile', 'twitter_profile', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `core_page`
--

CREATE TABLE `core_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(32) NOT NULL,
  `template` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(1000) NOT NULL,
  `descripion` varchar(1000) NOT NULL,
  `script` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  `hide` enum('show','hide') NOT NULL DEFAULT 'hide',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `core_page`
--

INSERT INTO `core_page` (`id`, `alias`, `template`, `title`, `keywords`, `descripion`, `script`, `style`, `hide`) VALUES
(4, 'news', '', 'Новости', 'ключевые слова1', 'описание1', 'jquery.flexslider.js,banner.js', 'flexslider.css', 'show'),
(5, 'catalog', '', 'Каталог товаров', '1', '1', 'jquery.flexslider.js,banner.js', 'flexslider.css', 'show'),
(6, 'default', '', 'Главная страница', '', '', 'jquery.flexslider.js,banner.js', 'flexslider.css', 'show'),
(17, 'coupons', '', 'coupons', '', '', '', '', 'show'),
(20, 'profile', '', 'profile', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `core_page_modules`
--

CREATE TABLE `core_page_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idpage` int(10) unsigned NOT NULL,
  `idmodule` int(11) NOT NULL,
  `set_pos` varchar(32) NOT NULL,
  `hide` enum('show','hide') NOT NULL DEFAULT 'show',
  `level` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idpage` (`idpage`,`idmodule`,`set_pos`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `core_page_modules`
--

INSERT INTO `core_page_modules` (`id`, `idpage`, `idmodule`, `set_pos`, `hide`, `level`) VALUES
(1, 20, 76, 'section', 'show', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `core_templates`
--

CREATE TABLE `core_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `core_templates`
--

INSERT INTO `core_templates` (`id`, `name`, `title`) VALUES
(1, '', ''),
(2, 'temp1', 'шаблон 1');

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_brand`
--

CREATE TABLE `coupon_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_category`
--

CREATE TABLE `coupon_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_material`
--

CREATE TABLE `coupon_material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` int(10) unsigned NOT NULL,
  `idbrand` int(10) unsigned NOT NULL,
  `idcategory` int(10) unsigned NOT NULL,
  `title` text NOT NULL,
  `desc` text NOT NULL,
  `img` text NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `promocode` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idtype` (`idtype`,`idbrand`,`idcategory`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `coupon_type`
--

CREATE TABLE `coupon_type` (
  `id` int(10) unsigned NOT NULL,
  `title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `forms`
--

CREATE TABLE `forms` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_module` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hide` enum('show','hide') NOT NULL DEFAULT 'show',
  `mailto` varchar(255) NOT NULL,
  `mailfrom` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `from` varchar(255) NOT NULL,
  `textSuccess` text NOT NULL,
  `scriptAfterSend` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forms_fields`
--

CREATE TABLE `forms_fields` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_form` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` text NOT NULL,
  `placeholder` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_required` enum('yes','no') NOT NULL DEFAULT 'no',
  `pattern` varchar(255) NOT NULL,
  `order` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(455) NOT NULL,
  `link` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `text` text NOT NULL,
  `site` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `parser_new`
--

CREATE TABLE `parser_new` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `parser` enum('on','off') NOT NULL DEFAULT 'on',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `parser_new`
--

INSERT INTO `parser_new` (`id`, `content`, `title`, `link`, `img`, `description`, `text`, `site`, `parser`, `date`) VALUES
(1, '.post-preview', 'h2 a', 'h2 a', 'a img', '.description', '.description', 'http://www.trendsbrands.ru/blog/1319/', 'on', '2013-04-23 07:55:48');

-- --------------------------------------------------------

--
-- Структура таблицы `twitter`
--

CREATE TABLE `twitter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idtwitter_profile` int(10) unsigned NOT NULL,
  `post` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idtwitter_profil` (`idtwitter_profile`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1511 ;

-- --------------------------------------------------------

--
-- Структура таблицы `twitter_profile`
--

CREATE TABLE `twitter_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(40) DEFAULT NULL,
  `email` varchar(80) NOT NULL,
  `password` char(32) NOT NULL,
  `hash` char(32) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_index` (`email`),
  UNIQUE KEY `login_index` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `hash`, `date`) VALUES
(1, 'mizko', 'mizko85@mail.ru', '202cb962ac59075b964b07152d234b70', '91a8deb8fe5399a2f06b89224d24eb12', '2013-03-18 13:50:29'),
(2, 'fred', 'fred-mayer@list.ru', '2a235d94ad2e78be3bb7aa500366ddb2', '972bb71b40171fd6a3bcc9465f722eef', '2013-03-27 12:56:37'),
(3, 'user', 'i@mextra.ru', '81dc9bdb52d04dc20036dbd8313ed055', '73840c4c1a2007d82eb05527ad76da44', '2013-05-31 12:08:32');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
