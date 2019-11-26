-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Erstellungszeit: 22. Nov 2019 um 13:32
-- Server-Version: 5.7.23
-- PHP-Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tx_jobs_domain_model_status`
--

CREATE TABLE `tx_jobs_domain_model_status` (
  `uid` int(10) UNSIGNED NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `crdate` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `cruser_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `deleted` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `hidden` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `starttime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `endtime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `t3ver_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `t3ver_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `t3ver_wsid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3ver_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `t3ver_tstamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `t3ver_move_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `followers` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `tx_jobs_domain_model_status`
--

INSERT INTO `tx_jobs_domain_model_status` (`uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `deleted`, `hidden`, `starttime`, `endtime`, `sys_language_uid`, `l10n_parent`, `l10n_state`, `l10n_diffsource`, `t3ver_oid`, `t3ver_id`, `t3ver_label`, `t3ver_wsid`, `t3ver_state`, `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`, `name`, `followers`) VALUES
(1, %pid%, 1574341232, 1574339265, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '01 New', 4),
(2, %pid%, 1574341207, 1574339369, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '02 Processing', 13),
(3, %pid%, 1574341125, 1574339505, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '03 Set for evaluation', 7),
(4, %pid%, 1574341075, 1574339571, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '04 Postponed', 8),
(5, %pid%, 1574341025, 1574339848, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '05 Reject Application', 5),
(6, %pid%, 1574340943, 1574339900, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '06 Applicant Rejected', 2),
(7, %pid%, 1574340918, 1574339968, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '07 Rejection by Applicant', 2),
(8, %pid%, 1574340878, 1574340054, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '08 Invite to job interview', 6),
(9, %pid%, 1574340827, 1574340084, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '09 Invited to job interview', 5),
(10, %pid%, 1574340763, 1574340134, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '10 Date for interview set', 8),
(11, %pid%, 1574340692, 1574340237, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '11 Send job offer', 4),
(12, %pid%, 1574340663, 1574340258, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '12 Job offer sent', 4),
(13, %pid%, 1574340593, 1574340346, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '13 Contract sent', 4),
(14, %pid%, 1574340545, 1574340375, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '14 Received signed contract', 1),
(15, %pid%, 1574340428, 1574340428, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '15 Deleted', 0),
(16, %pid%, 1574340492, 1574340477, 1, 0, 0, 0, 0, %lang%, 0, NULL, 0x613a363a7b733a31363a227379735f6c616e67756167655f756964223b4e3b733a363a2268696464656e223b4e3b733a343a226e616d65223b4e3b733a393a22666f6c6c6f77657273223b4e3b733a393a22737461727474696d65223b4e3b733a373a22656e6474696d65223b4e3b7d, 0, 0, '', 0, 0, 0, 0, 0, 0, '16 Applicant-Pool', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tx_jobs_domain_model_status`
--
ALTER TABLE `tx_jobs_domain_model_status`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `parent` (`pid`,`deleted`,`hidden`),
  ADD KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tx_jobs_domain_model_status`
--
ALTER TABLE `tx_jobs_domain_model_status`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
