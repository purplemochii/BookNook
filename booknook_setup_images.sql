-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 10:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booknook_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `author_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `author_name`) VALUES
(1, 'Maren Solace'),
(2, 'Idris K. Lowell'),
(3, 'Elara Finch'),
(4, 'Tobias Wren'),
(5, 'Samira Delacourt'),
(6, 'Dmitri Osin'),
(7, 'Rowan Hale'),
(8, 'Kiara Mendoza'),
(9, 'Cassian Roe'),
(10, 'Jae-Min Park');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `year` smallint(6) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `blurb` text DEFAULT NULL,
  `img` varchar(255) NOT NULL DEFAULT 'images/default-cover.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `year`, `genre_id`, `isbn`, `price`, `blurb`, `img`) VALUES
(1, 'The Glass Orchard', 2018, 1, '978-0123456781', 12.99, 'A twisty mystery set in a remote glass-making town.', 'images/book1.jpg'),
(2, 'Stars Beneath the Water', 2021, 2, '978-0123456782', 14.50, 'Deep space adventure with complex moral dilemmas.', 'images/book2.jpg'),
(3, 'A Map of Quiet Places', 2015, 3, '978-0123456783', 10.99, 'A poignant coming-of-age story in rural Canada.', 'images/book3.jpg'),
(4, 'The Clockmaker\'s Dilemma', 2009, 4, '978-0123456784', 11.25, 'Gears, steam, and an impossible time machine.', 'images/book4.jpg'),
(5, 'Honey on the Horizon', 2022, 5, '978-0123456785', 13.99, 'A heartwarming summer romance on the coast.', 'images/book5.jpg'),
(6, 'Winter\'s Algebra', 2011, 6, '978-0123456786', 15.75, 'A challenging tale of philosophy and mathematics.', 'images/book6.jpg'),
(7, 'The Ninefold Pact', 2019, 7, '978-0123456787', 16.99, 'Epic high fantasy with dark magic and ancient gods.', 'images/book7.jpg'),
(8, 'Concrete Roses', 2017, 8, '978-0123456788', 9.99, 'A powerful story of resilience in an urban landscape.', 'images/book8.jpg'),
(9, 'Shadows on the Fifth Floor', 2013, 9, '978-0123456789', 12.50, 'Gritty, hard-boiled detective fiction.', 'images/book9.jpg'),
(10, 'Synthetic Dawn', 2024, 10, '978-0123456790', 17.20, 'Future dystopia with artificial intelligence conflict.', 'images/book10.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `book_authors`
--

CREATE TABLE `book_authors` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_authors`
--

INSERT INTO `book_authors` (`book_id`, `author_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genre_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genre_name`) VALUES
(1, 'Mystery Thriller'),
(2, 'Science Fiction'),
(3, 'Contemporary Fiction'),
(4, 'Steampunk'),
(5, 'Fantasy'),
(6, 'Romance'),
(7, 'Literary Fiction'),
(8, 'High Fantasy'),
(9, 'Young Adult'),
(10, 'Crime Thriller'),
(11, 'Cyberpunk'),
(12, 'Sci-Fi');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `sold_at` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `book_id`, `order_date`, `sold_at`) VALUES
(1, 1, 2, '2025-11-29 22:37:38', 14.99),
(2, 1, 5, '2025-11-29 22:37:38', 13.99);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `date_registered`) VALUES
(1, 'cisco', 'class', 'cisco@booknook.com', '2025-11-29 22:30:00'),
(2, 'ling', 'cardealer', 'ling@lingscars.com', '2025-12-01 21:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_library`
--

CREATE TABLE `user_library` (
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_status` varchar(255) NOT NULL,
  `date_acquired` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_library`
--

INSERT INTO `user_library` (`user_id`, `book_id`, `book_status`, `date_acquired`) VALUES
(1, 1, 'readlist', '2025-11-29 22:37:38'),
(1, 2, 'owned', '2025-11-29 22:37:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `books_genre_fk` (`genre_id`);

--
-- Indexes for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `ba_author_fk` (`author_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_user_fk` (`user_id`),
  ADD KEY `orders_book_fk` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_library`
--
ALTER TABLE `user_library`
  ADD PRIMARY KEY (`user_id`,`book_id`),
  ADD KEY `library_book_fk` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_genre_fk` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`);

--
-- Constraints for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD CONSTRAINT `ba_author_fk` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ba_book_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_book_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `orders_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_library`
--
ALTER TABLE `user_library`
  ADD CONSTRAINT `library_book_fk` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `library_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
