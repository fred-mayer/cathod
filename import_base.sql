-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 12 2013 г., 20:16
-- Версия сервера: 5.1.70
-- Версия PHP: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `profing_profing`
--

-- --------------------------------------------------------

--
-- Структура таблицы `article_category`
--

CREATE TABLE IF NOT EXISTS `article_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_par` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `hide` enum('show','hide','','') NOT NULL DEFAULT 'show',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;


-- --------------------------------------------------------

--
-- Структура таблицы `article_items`
--

CREATE TABLE IF NOT EXISTS `article_items` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_cat` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `introtext` text NOT NULL,
  `fulltext` text NOT NULL,
  `url_readmore` varchar(255) NOT NULL COMMENT 'Ссылка на произвольную страницу',
  `img_readmore` enum('hide','show','','') NOT NULL DEFAULT 'show' COMMENT 'Показывать картинку в подробном материале',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hide` enum('show','hide','','') NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Структура таблицы `banner`
--

CREATE TABLE IF NOT EXISTS `banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `src` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `level` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `banner`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_attr`
--

CREATE TABLE IF NOT EXISTS `catalog_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iditem` int(10) unsigned NOT NULL,
  `field_name` varchar(30) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iditem` (`iditem`,`field_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_attr`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_buyers`
--

CREATE TABLE IF NOT EXISTS `catalog_buyers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `pass` varchar(8) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `home` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_buyers`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_cats`
--

CREATE TABLE IF NOT EXISTS `catalog_cats` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `parentid` int(255) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `alias` varchar(255) NOT NULL,
  `hide` int(10) NOT NULL DEFAULT '1',
  `order` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_cats`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_items`
--

CREATE TABLE IF NOT EXISTS `catalog_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cat` int(10) unsigned NOT NULL,
  `id_mag` int(11) NOT NULL,
  `url` varchar(2024) NOT NULL,
  `picture` varchar(2024) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(16) NOT NULL,
  `price_old` varchar(16) NOT NULL,
  `sale` varchar(16) NOT NULL,
  `description` text NOT NULL,
  `articul` varchar(140) NOT NULL,
  `hash` char(32) NOT NULL,
  `hide` enum('true','false') NOT NULL DEFAULT 'false',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_cat` (`id_cat`,`id_mag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_items`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_items_params`
--

CREATE TABLE IF NOT EXISTS `catalog_items_params` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_item` int(255) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_items_params`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_magazine`
--

CREATE TABLE IF NOT EXISTS `catalog_magazine` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `trekking_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL,
  `script_parser` varchar(255) CHARACTER SET utf8 NOT NULL,
  `hide` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_magazine`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_mag_cats`
--

CREATE TABLE IF NOT EXISTS `catalog_mag_cats` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_cat` int(255) NOT NULL,
  `id_mag` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_mag_cats`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_orders`
--

CREATE TABLE IF NOT EXISTS `catalog_orders` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_buyers` int(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `sum` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_orders`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_order_tems`
--

CREATE TABLE IF NOT EXISTS `catalog_order_tems` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_orders` int(255) NOT NULL,
  `id_item` int(255) NOT NULL,
  `id_attr` int(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `count` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_order_tems`
--


-- --------------------------------------------------------

--
-- Структура таблицы `catalog_pictures`
--

CREATE TABLE IF NOT EXISTS `catalog_pictures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iditem` int(10) unsigned NOT NULL,
  `picture` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `iditem` (`iditem`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `catalog_pictures`
--


-- --------------------------------------------------------

--
-- Структура таблицы `collect_mail`
--

CREATE TABLE IF NOT EXISTS `collect_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(140) CHARACTER SET utf8 NOT NULL,
  `user` enum('men','women') NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`),
  UNIQUE KEY `email_3` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Структура таблицы `core_modules`
--

CREATE TABLE IF NOT EXISTS `core_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(32) NOT NULL,
  `params` varchar(255) NOT NULL,
  `exist` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `core_modules`
--

INSERT INTO `core_modules` (`id`, `title`, `name`, `params`, `exist`) VALUES
(0, 'Купоны от Миксмаркет', 'coupons', '', 1),
(1, 'Категории каталога товаров', 'catalog_tree', '', 1),
(2, 'Каталог товаров', 'catalog', '', 1),
(3, 'Корзина', 'catalog_basket', '', 1),
(4, 'Хлебные крошки', 'breadcrumb', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `core_modules_group`
--

CREATE TABLE IF NOT EXISTS `core_modules_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `version` varchar(16) NOT NULL,
  `tables` varchar(255) NOT NULL,
  `exist` tinyint(3) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `core_modules_group`
--

INSERT INTO `core_modules_group` (`id`, `title`, `description`, `name`, `icon`, `version`, `tables`, `exist`, `date`) VALUES
(8, 'Купоны от Миксмаркет', '', 'coupons', '', '0.9', 'coupon_material', 1, '2013-07-31 13:02:55'),
(7, 'HTML контент', '', 'content', 'icon-align-left', '0.9', 'content', 0, '2013-07-31 13:02:51'),
(9, 'Слайдшоу', 'Слайдшоу банеры', 'banner', '', '0.9', 'banner', 0, '2013-08-05 12:31:23'),
(10, 'Формы', 'Редактор форм обратной связи', 'forms', '', '0.9', 'forms,forms_fields', 0, '2013-08-05 14:04:30'),
(11, 'Каталог товаров', 'Каталог товаров на основе парсинга', 'catalog', '', '0.9', 'catalog_cats,catalog_items,catalog_items_params,catalog_magazine,catalog_mag_cats', 1, '2013-08-05 14:20:19'),
(12, 'Категории каталога товаров', 'Вывод дерева категорий каталога', 'catalog_tree', '', '0.9', '', 1, '2013-08-12 08:29:42'),
(13, 'Корзина', 'Отображение корзины для интернет-магазина', 'catalog_basket', '', '0.9', '', 1, '2013-08-30 14:10:02'),
(14, 'Статьи', 'Модуль статей - вывод списка статей в виде блога и т.п. Подходит для новостей', 'article', 'icon-book', '1', 'article_category,article_items', 0, '2013-09-13 17:48:45'),
(15, 'Путь сайта', 'Хлебные крошки', 'breadcrumb', 'icon-road', '0.9', '', 1, '2013-09-17 20:48:56'),
(16, 'Меню', 'Меню сайта', 'menu', 'icon-th-list', '0.9', 'menu', 0, '2013-09-17 21:32:12');

-- --------------------------------------------------------

--
-- Структура таблицы `core_page`
--

CREATE TABLE IF NOT EXISTS `core_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(255) NOT NULL,
  `pagename` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `template` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(1000) NOT NULL,
  `descripion` varchar(1000) NOT NULL,
  `script` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  `hide` enum('show','hide') NOT NULL DEFAULT 'hide',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Дамп данных таблицы `core_page`
--

INSERT INTO `core_page` (`id`, `id_parent`, `pagename`, `alias`, `template`, `title`, `keywords`, `descripion`, `script`, `style`, `hide`) VALUES
(1, 0, '', 'default', '', 'Вконтактер))', '', '', '', '', 'show');
-- --------------------------------------------------------

--
-- Структура таблицы `core_page_modules`
--

CREATE TABLE IF NOT EXISTS `core_page_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idpage` int(10) unsigned NOT NULL,
  `idmodule` int(11) NOT NULL,
  `set_pos` varchar(32) NOT NULL,
  `hide` enum('show','hide') NOT NULL DEFAULT 'show',
  `level` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idpage` (`idpage`,`idmodule`,`set_pos`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=326 ;


-- --------------------------------------------------------

--
-- Структура таблицы `core_templates`
--

CREATE TABLE IF NOT EXISTS `core_templates` (
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
-- Структура таблицы `coupon_material`
--

CREATE TABLE IF NOT EXISTS `coupon_material` (
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

--
-- Дамп данных таблицы `coupon_material`
--


-- --------------------------------------------------------

--
-- Структура таблицы `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


-- --------------------------------------------------------

--
-- Структура таблицы `forms_fields`
--

CREATE TABLE IF NOT EXISTS `forms_fields` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_form` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` text NOT NULL,
  `placeholder` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `options` text NOT NULL,
  `is_required` enum('yes','no') NOT NULL DEFAULT 'no',
  `pattern` varchar(255) NOT NULL,
  `order` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Структура таблицы `listsite`
--

CREATE TABLE IF NOT EXISTS `listsite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `descripion` varchar(500) CHARACTER SET utf8 NOT NULL,
  `descripion_all` text CHARACTER SET utf8 NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_parent` int(255) NOT NULL,
  `name_group` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `id_page` int(255) NOT NULL DEFAULT '1',
  `sfx` varchar(255) NOT NULL COMMENT 'Суффикс - доп класс для пункта меню',
  `separator` enum('no','yes','','') NOT NULL DEFAULT 'no' COMMENT 'Разделитель',
  `hide` enum('hide','show','','') NOT NULL DEFAULT 'show',
  `order` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;


-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(40) DEFAULT NULL,
  `email` varchar(80) NOT NULL,
  `password` char(32) NOT NULL,
  `hash` char(32) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_index` (`email`),
  UNIQUE KEY `login_index` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `hash`, `date`) VALUES
(4, 'admin', 'mail@site-don.ru', '33d0fc8b9ec9acb34d3ff45a33e96322', 'f592ec1370dbb91b1ec4af963556cd5d', '2013-09-23 15:24:34');
