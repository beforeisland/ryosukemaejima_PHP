-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2020 年 10 月 10 日 03:23
-- サーバのバージョン： 5.7.26
-- PHP のバージョン: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `mini_bbs`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `good`
--

CREATE TABLE `good` (
  `good_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `good`
--

INSERT INTO `good` (`good_id`, `member_id`, `post_id`, `created`, `modified`) VALUES
(2, 2, 17, '2020-10-06 21:26:20', '2020-10-06 12:26:20'),
(4, 2, 18, '2020-10-06 22:11:57', '2020-10-06 13:11:57'),
(6, 2, 15, '2020-10-06 23:37:37', '2020-10-06 14:37:37'),
(9, 1, 16, '2020-10-07 21:35:33', '2020-10-07 12:35:33'),
(13, 1, 21, '2020-10-08 00:00:39', '2020-10-07 15:00:39'),
(17, 1, 18, '2020-10-08 22:23:46', '2020-10-08 13:23:46'),
(22, 1, 19, '2020-10-10 12:00:48', '2020-10-10 03:00:48');

-- --------------------------------------------------------

--
-- テーブルの構造 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `members`
--

INSERT INTO `members` (`id`, `name`, `email`, `password`, `picture`, `created`, `modified`) VALUES
(1, 'aaaa', 'aaaa@gmail.com', '70c881d4a26984ddce795f6f71817c9cf4480e79', '20201004094415izakaya.jpg', '2020-10-04 18:44:19', '2020-10-04 09:44:19'),
(2, 'bbbb', 'bbbb@gmail.com', '8aed1322e5450badb078e1fb60a817a1df25a2ca', '20201004151829', '2020-10-05 00:18:33', '2020-10-04 15:18:33');

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `message` text,
  `member_id` int(11) DEFAULT NULL,
  `reply_post_id` int(11) DEFAULT NULL,
  `rt_post_id` int(11) DEFAULT NULL,
  `rt_member_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `message`, `member_id`, `reply_post_id`, `rt_post_id`, `rt_member_id`, `created`, `modified`) VALUES
(1, 'こんにちは', 1, NULL, 0, 0, '2020-10-04 23:47:52', '2020-10-04 14:47:52'),
(2, 'どうもです', 2, NULL, 0, 0, '2020-10-05 00:19:36', '2020-10-04 15:19:36'),
(3, '@aaaa こんにちは\r\nラーメン食べたい\r\n', 1, 1, 0, 0, '2020-10-05 00:55:44', '2020-10-04 15:55:44'),
(4, 'テスト', 1, 0, 0, 0, '2020-10-05 00:56:08', '2020-10-04 15:56:08'),
(5, 'https://www.google.com/', 1, 0, 0, 0, '2020-10-05 21:46:23', '2020-10-05 12:46:23'),
(7, '1', 1, 0, 0, 0, '2020-10-05 22:32:20', '2020-10-05 13:32:20'),
(8, '2', 1, 0, 0, 0, '2020-10-05 22:32:24', '2020-10-05 13:32:24'),
(9, '3', 1, 0, 0, 0, '2020-10-05 22:32:25', '2020-10-05 13:32:25'),
(10, '4', 1, 0, 0, 0, '2020-10-05 22:32:28', '2020-10-05 13:32:28'),
(11, '5', 1, 0, 0, 0, '2020-10-05 22:32:29', '2020-10-05 13:32:29'),
(12, '6', 1, 0, 0, 0, '2020-10-05 22:32:31', '2020-10-05 13:32:31'),
(13, '7', 1, 0, 0, 0, '2020-10-05 22:32:32', '2020-10-05 13:32:32'),
(14, '8', 1, 0, 0, 0, '2020-10-05 22:32:34', '2020-10-05 13:32:34'),
(15, '9', 1, 0, 0, 0, '2020-10-05 22:32:36', '2020-10-05 13:32:36'),
(16, '10', 1, 0, 0, 0, '2020-10-05 22:32:39', '2020-10-05 13:32:39'),
(17, 'いいね用', 1, 0, 0, 0, '2020-10-06 21:21:43', '2020-10-06 12:21:43'),
(18, 'いいね用b', 2, 0, 0, 0, '2020-10-06 21:25:17', '2020-10-06 12:25:17'),
(19, 'テストa', 1, 0, 0, 0, '2020-10-07 22:32:19', '2020-10-07 13:32:19'),
(26, 'テストa', 1, NULL, 19, 2, '2020-10-09 00:55:54', '2020-10-08 15:55:54'),
(28, 'テストa', 1, NULL, 19, 1, '2020-10-10 12:00:56', '2020-10-10 03:00:56'),
(29, '8', 1, NULL, 14, 1, '2020-10-10 12:06:24', '2020-10-10 03:06:24');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `good`
--
ALTER TABLE `good`
  ADD PRIMARY KEY (`good_id`);

--
-- テーブルのインデックス `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `good`
--
ALTER TABLE `good`
  MODIFY `good_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- テーブルのAUTO_INCREMENT `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルのAUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
