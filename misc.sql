-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2024 at 09:24 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `misc`
--

--
-- Dumping data for table `autos`
--

INSERT INTO `autos` (`autos_id`, `make`, `model`, `year`, `mileage`) VALUES
(1, 'r', 'r', 2, 1),
(21, 'tomato', 'tomato', 1, 1),
(22, 'tomato', 'tomato', 1, 1),
(23, 'dd', 'g', 1, 1);

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`profile_id`, `institution_id`, `rank`, `year`) VALUES
(51, 4, 1, 190),
(54, 4, 1, 1990),
(55, 2, 1, 1990),
(59, 4, 1, 1995),
(61, 4, 1, 1995),
(62, 4, 1, 1996),
(63, 4, 1, 23423),
(64, 1, 1, 2222),
(64, 3, 2, 14534),
(67, 4, 1, 1195),
(68, 1, 1, 1195),
(69, 3, 1, 1195),
(71, 10, 1, 1123),
(72, 4, 1, 1123);

--
-- Dumping data for table `institution`
--

INSERT INTO `institution` (`institution_id`, `name`) VALUES
(6, 'Duke University'),
(7, 'Michigan State University'),
(8, 'Mississippi State University'),
(9, 'Montana State University'),
(5, 'Stanford University'),
(10, 'uni of chicken'),
(4, 'University of Cambridge'),
(1, 'University of Michigan'),
(3, 'University of Oxford'),
(2, 'University of Virginia');

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`position_id`, `profile_id`, `rank`, `year`, `description`) VALUES
(5, 54, 1, 1990, 'edit'),
(7, 57, 1, 1990, 'tyrytryry'),
(9, 60, 1, 1990, 'errorboy'),
(10, 62, 1, 1990, 'ytrytry'),
(12, 65, 1, 34234, 'sdfsfsfd'),
(13, 66, 1, 34234, 'sdfsfsfd');

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profile_id`, `user_id`, `first_name`, `last_name`, `email`, `headline`, `summary`) VALUES
(42, 1, 'sdf', 'sdf', 'sdffd@sdffs', 'sdfsdf', 'sf'),
(43, 1, 'sdf', 'sdf', 'sdffd@sdffs', 'sdfsdf', 'sf'),
(44, 1, 'sdf', 'sdf', 'sdffd@sdffs', 'sdfsdf', 'sf'),
(45, 1, 'dfgdfg', 'dfgdg', 'gfdgdfg@fdgdfg', 'dfgfdg', 'dgdfg'),
(46, 1, 'dfgdfg', 'dfgdg', 'gfdgdfg@fdgdfg', 'dfgfdg', 'dgdfg'),
(47, 1, 'dfgdfg', 'dfgdg', 'gfdgdfg@fdgdfg', 'dfgfdg', 'dgdfg'),
(48, 1, 'dfgdfg', 'dfgdg', 'gfdgdfg@fdgdfg', 'dfgfdg', 'dgdfg'),
(49, 1, 'tyrty', 'rtyrty', 'rtyryt@sfsdf', 'sdfsfs', 'sdfsf'),
(50, 1, 'tyrty', 'rtyrty', 'rtyryt@sfsdf', 'sdfsfs', 'sdfsf'),
(51, 1, 'rtret', 'reter', 'ertet@sfsdf', 'sfdsf', 'sfsdf'),
(53, 1, 'reer', 'erer', 'erwrew@sdfsdf', 'sdfsdf', 'sdfsdf'),
(54, 1, 'edit', 'edit', 'edit@edit.com', 'edit', 'edit'),
(55, 1, 'fsdfs', 'sdfsdf', 'sffsdf@sdfsf', 'sfsdf', 'sfsdf'),
(56, 1, 'test', 'sdfsdf', 'sffsdf@sdfsf', 'sfsdf', 'sfsdf'),
(57, 1, 'test', 'sdfsdf', 'sffsdf@sdfsf', 'sfsdf', 'sfsdf'),
(58, 1, 'test', 'sdfsdf', 'sffsdf@sdfsf', 'sfsdf', 'sfsdf'),
(59, 1, 'errorboy', 'errorboy', 'errorboy@errorboy', 'errorboy', 'errorboy'),
(60, 1, 'errorboy', 'errorboy', 'errorboy@errorboy', 'errorboy', 'errorboy'),
(61, 1, 'test', 'test', 'test@test', 'test', 'test'),
(62, 1, 'erwr', 'ewrwer', 'wrwerwer@sfsdfs', 'sfsdf', 'sdfsdf'),
(63, 1, 'erwr', 'ewrwer', 'wrwerwer@sfsdfs', 'sfsdf', 'sdfsdf'),
(64, 1, 'hgjghj', 'gjgjghj', 'ghjghj@dsfsdf', 'gjgh', 'ghjghj'),
(65, 1, 'hgjghj', 'gjgjghj', 'ghjghj@dsfsdf', 'gjgh', 'ghjghj'),
(66, 1, 'hgjghj', 'gjgjghj', 'ghjghj@dsfsdf', 'gjgh', 'ghjghj'),
(67, 1, 'first', 'last', 'last@email.com', 'last', 'last'),
(68, 1, 'first', 'last', 'last@email.com', 'last', 'last'),
(69, 1, 'first', 'last', 'last@email.com', 'last', 'last'),
(70, 1, 'first', 'last', 'last@email.com', 'last', 'last'),
(71, 1, 'dfs', 'sdf', 'sdfsfd@dsfsf', 'sfsdf', 'sfdsdf'),
(72, 1, 'dfs', 'sdf', 'sdfsfd@dsfsf', 'sfsdf', 'sfdsdf'),
(73, 2, 'Cloches', 'Outweary', 'blah@example.com', 'Bilious', 'Denizens'),
(75, 2, 'Correlated', 'Relegated', 'blah@example.com', 'Source', 'Skydive');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`) VALUES
(1, 'Chuck', 'csev@umich.edu', '1a52e17fa899cf40fb04cfc42e6352f1'),
(2, 'UMSI', 'umsi@umich.edu', '1a52e17fa899cf40fb04cfc42e6352f1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
