-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
--
-- Wed 03 February 2016 at 11:37 am

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `altitude-example`
--

-- --------------------------------------------------------

--
-- Structure of table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `FK_user_ID` int(11) NOT NULL,
  `FK_item_ID` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Content of table `comments`
--

INSERT INTO `comments` (`id`, `date`, `FK_user_ID`, `FK_item_ID`, `text`) VALUES
(1, '2015-11-21', 1, 4, 'What a nice comment, gniuk gniuk!'),
(2, '2015-12-04', 3, 2, 'I''m writing something about balls.'),
(3, '2015-12-23', 5, 5, 'Wazzaaaaa!');

-- --------------------------------------------------------

--
-- Structure of table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(56) NOT NULL,
  `FK_user_ID` int(11) NOT NULL,
  `FK_comment_ID` int(11) DEFAULT NULL,
  `date_creation` date NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref` (`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Content of table `items`
--

INSERT INTO `items` (`id`, `ref`, `FK_user_ID`, `FK_comment_ID`, `date_creation`, `content`) VALUES
(1, 'itemFlask', 1, NULL, '2015-02-28', '["a","bit","of","json"]'),
(2, 'itemBall', 3, 2, '2015-07-29', '{"parts":22,"type":"leather","hardness":5.314,"filled":true}'),
(3, 'itemFlow', 4, NULL, '2015-07-06', '[]'),
(4, 'itemNiuk', 3, 1, '2015-01-14', '{"leski":"mow","gniuk":"gniuk"}'),
(5, 'itemZaa', 5, 3, '2015-12-05', '[7,356,20,16]');

-- --------------------------------------------------------

--
-- Structure of table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(56) NOT NULL,
  `pseudo` varchar(56) NOT NULL,
  `age` int(3) NOT NULL,
  `last_action` date NOT NULL,
  `alive` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Content of table `users`
--

INSERT INTO `users` (`id`, `name`, `pseudo`, `age`, `last_action`, `alive`) VALUES
(1, 'Paul', 'Polo', 34, '2016-01-14', 1),
(2, 'Marcel', 'Mamar', 75, '2012-04-24', 0),
(3, 'Jacques', 'Jack', 22, '2014-10-28', 1),
(4, 'Julie', 'Grenouille', 32, '2015-10-28', 1),
(5, 'Henri', 'Riton', 30, '2015-09-22', 1),
(6, 'Alex', 'AK', 29, '0000-00-00', 0),
(27, 'Alex1', 'AK1', 29, '0000-00-00', 0);
 
