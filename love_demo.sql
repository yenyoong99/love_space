-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2025-03-16 21:41:23
-- 服务器版本： 8.0.35
-- PHP 版本： 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `love_demo`
--

-- --------------------------------------------------------

--
-- 表的结构 `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `event_date` date NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `expenses`
--

CREATE TABLE `expenses` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mood` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `timeline`
--

CREATE TABLE `timeline` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `visitor_logs`
--

CREATE TABLE `visitor_logs` (
  `id` int NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `visit_date` datetime NOT NULL,
  `referer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `additional_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `wishes`
--

CREATE TABLE `wishes` (
  `id` int NOT NULL,
  `sender_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `x` float NOT NULL,
  `y` float NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `visitor_logs`
--
ALTER TABLE `visitor_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `wishes`
--
ALTER TABLE `wishes`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `timeline`
--
ALTER TABLE `timeline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `visitor_logs`
--
ALTER TABLE `visitor_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `wishes`
--
ALTER TABLE `wishes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
