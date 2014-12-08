-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.32 - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры базы данных rifftest
CREATE DATABASE IF NOT EXISTS `rifftest` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `rifftest`;


-- Дамп структуры для таблица rifftest.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gender` char(1) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `status` char(1) NOT NULL,
  `body_type` char(1) DEFAULT NULL,
  `breast` varchar(50) DEFAULT NULL,
  `waist` varchar(50) DEFAULT NULL,
  `hips` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы rifftest.users: ~19 rows (приблизительно)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `gender`, `username`, `password`, `email`, `first_name`, `surname`, `country`, `city`, `birthday`, `status`, `body_type`, `breast`, `waist`, `hips`, `photo`, `role`) VALUES
	(1, 'm', 'admin', '202cb962ac59075b964b07152d234b70', 'admin@admin.com', 'admin', 'admin', 'ukraine', 'kharkov', '1990-01-01', '', NULL, NULL, NULL, NULL, NULL, 'admin'),
	(2, 'm', 'test1', '81dc9bdb52d04dc20036dbd8313ed055', 'deded@dde.com', 'test4', 'test4', 'test4', 'test4', '1990-10-15', 'm', 't', '', NULL, NULL, '/photos/2/images.jpg', 'user'),
	(3, 'm', 'test2', '202cb962ac59075b964b07152d234b70', 'qwerty@qwe.com', 'test2', 'test2', 'test2', 'test2', '2014-11-30', 'm', 'm', NULL, NULL, NULL, NULL, 'user'),
	(4, 'm', 'test4', '202cb962ac59075b964b07152d234b70', 'qwety@qwe.com', 'test4', 'test4', 'test4', 'test4', '2014-11-30', 'm', 'm', NULL, NULL, NULL, NULL, 'user'),
	(5, 'm', 'test5', '202cb962ac59075b964b07152d234b70', 'qwety@qgtwe.com', 'test5', 'test5', 'test5', 'test5', '2014-11-30', 'm', 'm', NULL, NULL, NULL, NULL, 'user'),
	(6, 'm', 'test6', '202cb962ac59075b964b07152d234b70', 'qwety@qgtwe.com', 'test6', 'test6', 'test6', 'test6', '2014-11-30', 'm', 'm', NULL, NULL, NULL, NULL, 'user'),
	(7, 'm', 'test7', '202cb962ac59075b964b07152d234b70', 'qwety@qgtwe.com', 'test7', 'test7', 'test7', 'test7', '2014-11-30', 'm', 'm', NULL, NULL, NULL, NULL, 'user'),
	(8, 'm', 'test8', '202cb962ac59075b964b07152d234b70', 'qwehyhyty@qgtwe.com', 'test8', 'test8', 'test8', 'test8', '2014-11-30', 'm', 'm', NULL, NULL, NULL, NULL, 'user'),
	(9, 'm', 'test9', '202cb962ac59075b964b07152d234b70', 'qwhyty@qgtwe.com', 'test9', 'test9', 'test9', 'test9', '2014-11-30', 'm', 'm', NULL, NULL, NULL, NULL, 'user'),
	(10, 'f', 'test10', '202cb962ac59075b964b07152d234b70', 'qwhyy@qgtwe.com', 'test10', 'test10', 'test10', 'test10', '1990-11-30', 'm', 'm', '40', '60', '100', NULL, 'user'),
	(16, 'm', 'sdad', '202cb962ac59075b964b07152d234b70', 'asasa@asas.ru', 'petro', 'petrus', 'Ukraine', 'Kharkov', '1990-01-01', 'm', 'm', '', '', NULL, NULL, 'user'),
	(23, 'f', 'sergey', '202cb962ac59075b964b07152d234b70', 'sergey@gmail.ru', 'dsdsds', 'dsdsdsd', 'dsdsdsd', 'dsdsdsd', '1990-01-01', 'n', 'm', '90', '60', '90', NULL, 'user'),
	(30, 'm', 'qwqw', '202cb962ac59075b964b07152d234b70', 'qwqw@gmail.com', 'John', 'Doe', 'USA', 'Boston', '1990-01-01', 'm', 't', '', '', NULL, NULL, 'user'),
	(31, 'm', 'asasa', '202cb962ac59075b964b07152d234b70', 'erty@reerer.com', 'frfdf', 'fвав', 'авczczsc', 'авкаdsadasda', '1990-01-01', 'm', 't', '', '', NULL, NULL, 'user'),
	(35, 'm', 'grdgd', '202cb962ac59075b964b07152d234b70', 'deded@dede.com', 'dgdr', 'drgd', 'drgdr', 'dgdr', '1990-01-01', 'm', 'm', NULL, NULL, NULL, '/photos/35/Mockup.png', 'user'),
	(36, 'f', 'test20', '202cb962ac59075b964b07152d234b70', 'qwerty@dot.com', 'test20', 'test20', 'Ukraine', 'Kiev', '1987-02-02', 'm', NULL, '100', '60', '100', '/photos/36/images.jpg', 'user'),
	(38, 'f', 'Liza', '202cb962ac59075b964b07152d234b70', 'qwerty@ytrewq.com', 'Liza', 'Smith', 'USA', 'New York', '1985-02-06', 'm', NULL, '100', '60', '90', '/photos/38/images2.jpg', 'user'),
	(39, 'm', 'sergey2', '202cb962ac59075b964b07152d234b70', 'serg@sdsd.com', 'sergey', 'sergey', 'sergey', 'sergey', '1990-01-01', 'm', 't', NULL, NULL, NULL, '/photos/39/images.jpg', 'user'),
	(40, 'f', 'sarahc', '81dc9bdb52d04dc20036dbd8313ed055', 'female@female.com', 'Sarah', 'Chonor', 'USA', 'Chicago', '1989-02-05', 'm', NULL, '100', '60', '90', '/photos/40/default.png', 'user');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
