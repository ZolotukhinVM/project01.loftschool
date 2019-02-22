SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `project01_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders_tbl`
--

CREATE TABLE `orders_tbl` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `address` tinytext NOT NULL,
  `comment` tinytext NOT NULL,
  `pay` set('cash','card','','') NOT NULL,
  `callback` char(1) DEFAULT NULL,
  `orderdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users_tbl`
--

CREATE TABLE `users_tbl` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `phone` varchar(12) NOT NULL,
  `email` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders_tbl`
--
ALTER TABLE `orders_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders_tbl`
--
ALTER TABLE `orders_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
