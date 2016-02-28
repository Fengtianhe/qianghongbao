-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 02 月 28 日 11:54
-- 服务器版本: 5.5.40
-- PHP 版本: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `qhb`
--

-- --------------------------------------------------------

--
-- 表的结构 `qhb_config`
--

CREATE TABLE IF NOT EXISTS `qhb_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `qhb_config`
--

INSERT INTO `qhb_config` (`id`, `type`, `value`) VALUES
(1, 'min', 5),
(2, 'max', 10),
(3, 'pay_cash', 100);

-- --------------------------------------------------------

--
-- 表的结构 `qhb_rob`
--

CREATE TABLE IF NOT EXISTS `qhb_rob` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `first_rob` varchar(11) NOT NULL COMMENT '第一次抢得金额',
  `total_rob` varchar(11) NOT NULL COMMENT '累计金额',
  `friend` int(11) NOT NULL DEFAULT '0' COMMENT '邀请人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `qhb_rob`
--

INSERT INTO `qhb_rob` (`id`, `uid`, `first_rob`, `total_rob`, `friend`) VALUES
(2, 1, '9.58', '9.58', 0);

-- --------------------------------------------------------

--
-- 表的结构 `qhb_rob_list`
--

CREATE TABLE IF NOT EXISTS `qhb_rob_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `rob_price` varchar(11) NOT NULL,
  `rob_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `qhb_rob_list`
--

INSERT INTO `qhb_rob_list` (`id`, `uid`, `friendid`, `rob_price`, `rob_time`) VALUES
(2, 1, 1, '9.58', 1456629317);

-- --------------------------------------------------------

--
-- 表的结构 `qhb_user`
--

CREATE TABLE IF NOT EXISTS `qhb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `nickname` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `qhb_user`
--

INSERT INTO `qhb_user` (`id`, `user`, `nickname`) VALUES
(1, 'fth545704061', '丿轻羽丶');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
