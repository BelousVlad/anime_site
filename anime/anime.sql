-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 30 2020 г., 08:37
-- Версия сервера: 10.3.22-MariaDB-log
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `anime`
--

-- --------------------------------------------------------

--
-- Структура таблицы `alter_titles`
--

CREATE TABLE `alter_titles` (
  `id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `alter_titles`
--

INSERT INTO `alter_titles` (`id`, `title_id`, `name`) VALUES
(1, 1, 'tolst1');

-- --------------------------------------------------------

--
-- Структура таблицы `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `genre`
--

INSERT INTO `genre` (`id`, `title`) VALUES
(1, 'Романтика'),
(2, 'Экшен'),
(5, 'Сёнен'),
(6, 'Меха'),
(7, 'Антиутопия'),
(8, 'Фэнтези');

-- --------------------------------------------------------

--
-- Структура таблицы `series`
--

CREATE TABLE `series` (
  `id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL,
  `voice_actor_id` int(11) NOT NULL,
  `cell_text` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sibnet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `series`
--

INSERT INTO `series` (`id`, `title_id`, `voice_actor_id`, `cell_text`, `sibnet_id`) VALUES
(1, 1, 2, '1 (test)', 1502426),
(2, 1, 1, '1 (test - anilib)', 1502426),
(3, 1, 1, '2', 1502426);

-- --------------------------------------------------------

--
-- Структура таблицы `studios`
--

CREATE TABLE `studios` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `studios`
--

INSERT INTO `studios` (`id`, `name`) VALUES
(1, 'Nut');

-- --------------------------------------------------------

--
-- Структура таблицы `titles`
--

CREATE TABLE `titles` (
  `id` int(11) NOT NULL,
  `primary_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview_path` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `studio_id` int(11) DEFAULT 1,
  `original` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Манга',
  `rating` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PG-13 (от 13 лет)',
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Нет описания'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `titles`
--

INSERT INTO `titles` (`id`, `primary_name`, `preview_path`, `year`, `studio_id`, `original`, `rating`, `description`) VALUES
(1, 'test1', 'hunter_x_hunter.jpg', 2015, 1, 'Манга', 'PG-13 (от 13 лет)', 'Нет описания'),
(2, 'test2', '', 2015, 1, 'Манга', 'PG-13 (от 13 лет)', 'Нет описания'),
(3, 'test3', '', 2015, 1, 'Манга', 'PG-13 (от 13 лет)', 'Нет описания'),
(4, 'test4', '', 2015, 1, 'Манга', 'PG-13 (от 13 лет)', 'Нет описания');

-- --------------------------------------------------------

--
-- Структура таблицы `title_genre`
--

CREATE TABLE `title_genre` (
  `id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `title_genre`
--

INSERT INTO `title_genre` (`id`, `title_id`, `genre_id`) VALUES
(2, 1, 8),
(3, 1, 2),
(4, 1, 7),
(5, 1, 5),
(6, 2, 7),
(7, 3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `voice_actors`
--

CREATE TABLE `voice_actors` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `voice_actors`
--

INSERT INTO `voice_actors` (`id`, `name`) VALUES
(1, 'Anilibria'),
(2, 'Anidub');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `alter_titles`
--
ALTER TABLE `alter_titles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title_id_fk` (`title_id`);

--
-- Индексы таблицы `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voice_actor_fk` (`voice_actor_id`),
  ADD KEY `title_fk` (`title_id`);

--
-- Индексы таблицы `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studio_id_fk` (`studio_id`);

--
-- Индексы таблицы `title_genre`
--
ALTER TABLE `title_genre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_title_id` (`title_id`),
  ADD KEY `fk_genre_id` (`genre_id`);

--
-- Индексы таблицы `voice_actors`
--
ALTER TABLE `voice_actors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `alter_titles`
--
ALTER TABLE `alter_titles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `series`
--
ALTER TABLE `series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `studios`
--
ALTER TABLE `studios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `titles`
--
ALTER TABLE `titles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `title_genre`
--
ALTER TABLE `title_genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `voice_actors`
--
ALTER TABLE `voice_actors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `alter_titles`
--
ALTER TABLE `alter_titles`
  ADD CONSTRAINT `title_id_fk` FOREIGN KEY (`title_id`) REFERENCES `titles` (`id`);

--
-- Ограничения внешнего ключа таблицы `series`
--
ALTER TABLE `series`
  ADD CONSTRAINT `title_fk` FOREIGN KEY (`title_id`) REFERENCES `titles` (`id`),
  ADD CONSTRAINT `voice_actor_fk` FOREIGN KEY (`voice_actor_id`) REFERENCES `voice_actors` (`id`);

--
-- Ограничения внешнего ключа таблицы `titles`
--
ALTER TABLE `titles`
  ADD CONSTRAINT `studio_id_fk` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`);

--
-- Ограничения внешнего ключа таблицы `title_genre`
--
ALTER TABLE `title_genre`
  ADD CONSTRAINT `fk_genre_id` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`),
  ADD CONSTRAINT `fk_title_id` FOREIGN KEY (`title_id`) REFERENCES `titles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
