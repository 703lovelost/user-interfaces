-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 30 2022 г., 20:22
-- Версия сервера: 8.0.19
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `userinterfaces_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'client');

-- --------------------------------------------------------

--
-- Структура таблицы `sexes`
--

CREATE TABLE `sexes` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sexes`
--

INSERT INTO `sexes` (`id`, `name`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `sex` varchar(30) NOT NULL,
  `birth_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role_id`, `login`, `password`, `first_name`, `last_name`, `sex`, `birth_date`) VALUES
(5, 1, 'admin', '$2y$10$BlqSJqv1afx7V15x6fxlLull2O.a.1ola3gESFvDNC/6wwviHp.cO', 'John', 'Doe', 'Male', '2022-05-12'),
(6, 2, 'thecoolestfrknname', '$2y$10$q9h9ZsrXj/BPOs/YkGt.Nu7Fzpn9MhHh4sd/LP6H.hMUOaKMDds7S', 'Seryozha', 'Myachikov', 'Male', '2022-05-21'),
(9, 2, 'adekvat', '$2y$10$GyWqQgiagIEOyDumdvmZjOxM2IrFEX09KUS55RhbAimR33OwivVI.', 'Milena', 'Velichko', 'Female', '1999-08-14'),
(10, 2, 'DeHbrU_Ha_CTo/\\', '$2y$10$bNlarFZg1.Lo6HWY4iCGXe2NOvHqa6XvihuLPwklekY/ztL9bT9XC', 'Vladimir', 'Rozhkov', 'Male', '2002-01-07'),
(11, 1, 'moguschestvo', '$2y$10$uEESfiToua7bKWdWs4O8MegtTOaQ6BuFqR18RKEJt9bWrMdUDp8nu', 'Alina', 'Pavlova', 'Female', '1998-05-10'),
(12, 2, 'ya_snyal_brat', '$2y$10$1era8KxpJR8wMLHFHkC3K.vIZb48lw1Wa9wohWmkTgRYz5O2ElsbG', 'Алексей', 'Балабанов', 'Male', '1959-02-25');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sexes`
--
ALTER TABLE `sexes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `sexes`
--
ALTER TABLE `sexes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
