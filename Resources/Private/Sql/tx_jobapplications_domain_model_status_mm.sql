-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Erstellungszeit: 22. Nov 2019 um 13:36
-- Server-Version: 5.7.23
-- PHP-Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Daten für Tabelle `tx_jobapplications_domain_model_status_mm`
--

INSERT INTO `tx_jobapplications_domain_model_status_mm` (`uid_local`, `uid_foreign`, `sorting`, `sorting_foreign`) VALUES
(3, 2, 1, 0),
(16, 2, 1, 0),
(14, 2, 1, 0),
(13, 14, 1, 0),
(13, 7, 2, 0),
(13, 2, 3, 0),
(13, 16, 4, 0),
(12, 13, 1, 0),
(12, 7, 2, 0),
(12, 2, 3, 0),
(12, 16, 4, 0),
(11, 12, 1, 0),
(11, 7, 2, 0),
(11, 2, 3, 0),
(11, 16, 4, 0),
(10, 12, 1, 0),
(10, 11, 2, 0),
(10, 6, 3, 0),
(10, 5, 4, 0),
(10, 7, 5, 0),
(10, 2, 6, 0),
(10, 4, 7, 0),
(10, 16, 8, 0),
(9, 10, 1, 0),
(9, 7, 2, 0),
(9, 12, 3, 0),
(9, 2, 4, 0),
(9, 16, 5, 0),
(8, 9, 1, 0),
(8, 10, 2, 0),
(8, 7, 3, 0),
(8, 3, 4, 0),
(8, 2, 5, 0),
(8, 16, 6, 0),
(7, 2, 1, 0),
(7, 16, 2, 0),
(6, 2, 1, 0),
(6, 16, 2, 0),
(5, 6, 1, 0),
(5, 7, 2, 0),
(5, 3, 3, 0),
(5, 2, 4, 0),
(5, 16, 5, 0),
(4, 2, 1, 0),
(4, 3, 2, 0),
(4, 6, 3, 0),
(4, 7, 4, 0),
(4, 9, 5, 0),
(4, 5, 6, 0),
(4, 8, 7, 0),
(4, 16, 8, 0),
(3, 5, 2, 0),
(3, 8, 3, 0),
(3, 4, 4, 0),
(3, 6, 5, 0),
(3, 9, 6, 0),
(3, 16, 7, 0),
(2, 3, 1, 0),
(2, 6, 2, 0),
(2, 7, 3, 0),
(2, 4, 4, 0),
(2, 9, 5, 0),
(2, 10, 6, 0),
(2, 12, 7, 0),
(2, 5, 8, 0),
(2, 8, 9, 0),
(2, 11, 10, 0),
(2, 13, 11, 0),
(2, 14, 12, 0),
(2, 16, 13, 0),
(1, 2, 1, 0),
(1, 7, 2, 0),
(1, 4, 3, 0),
(1, 6, 4, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tx_jobapplications_domain_model_status_mm`
--
ALTER TABLE `tx_jobapplications_domain_model_status_mm`
  ADD KEY `uid_local` (`uid_local`),
  ADD KEY `uid_foreign` (`uid_foreign`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
