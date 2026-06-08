-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 06:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bean_boss_tycoon_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

CREATE TABLE `registered_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`id`, `username`, `email`, `password`, `registration_date`) VALUES
(1, 'anbore', 'zakisdavis423@gmail.com', '$2y$10$vi9Gi0MaxZB7cP4bPcbbB.c58DnBb6sLQfELp9mlKaw.O05BOztiu', '2026-01-06 00:09:32'),
(50, 'jzy03', 'janiszakis03@gmail.com', '$2y$10$zx0GZg6Pi1XoWoQipNoNnu5hH8wKtXSBaYsuXpruTBTLdcYxsYkoq', '2026-06-08 18:09:49'),
(51, 'janis', 'zakisjanis5@inbox.lv', '$2y$10$R7GICnbbURgPoSN4E9x9l.WGpwXQI4doqZDaMKAh2IFXcmMcr9OoG', '2026-06-08 18:10:24'),
(52, 'ddz006', 'ddz006@proton.me', '$2y$10$qsOUDxvETGRBxUo2nzScLOccJmCvVG9eVaLO9JAdpTFmzCAjMQR.a', '2026-06-08 18:11:03'),
(53, 'd_zakiiss', 'davis123@gmail.com', '$2y$10$VKQUK7kFz.fsy0mGLnUnc.dWlNHKuMVgMxHo5APS/zADw9.EXeNha', '2026-06-08 19:03:41'),
(54, 'WaSd', 'wasd@gmail.com', '$2y$10$OdWCWFx9eEF/R3QC.z/E/uVpAnx77uV4lx2SC9SQbbgrwLvwP.aAi', '2026-06-08 19:09:52'),
(55, 'romaji', 'romaji@gmail.com', '$2y$10$8K3zIRg9qup2t4R09wSutu8YHp0NgNZafrZpPgHI/Bp/kN3Gw7T5e', '2026-06-08 19:10:43'),
(56, 'zxc', 'zxc@gmail.com', '$2y$10$mZi/Qu2jAOiyxBv.0GTDWegbc16W.Imn072Svj6tdl09FFePoLymu', '2026-06-08 19:11:24'),
(57, 'coffeeBrewer115', 'coffee@gmail.com', '$2y$10$vv0XfQpqZ5he5U79wA8XheJeDYuldlkL9KwTu8t0j.RhxQ1oTj9N.', '2026-06-08 19:12:08'),
(58, 'some random', 'random@icloud.com', '$2y$10$N/GD8y8D2w6rG1qNRr5K3uKYagsqpYNBIywADnN9X.Ns/r7IRp2uS', '2026-06-08 19:12:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_account_details`
--

CREATE TABLE `user_account_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_picture` varchar(255) NOT NULL DEFAULT 'default-pfp.jpg',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_banned` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_account_details`
--

INSERT INTO `user_account_details` (`id`, `user_id`, `profile_picture`, `is_admin`, `is_banned`, `last_active`) VALUES
(1, 1, 'pfp_1_1780856710.png', 1, 0, '2026-06-08 19:16:19'),
(32, 50, 'default-pfp.jpg', 0, 0, '2026-06-08 18:09:49'),
(33, 51, 'default-pfp.jpg', 0, 0, '2026-06-08 18:10:24'),
(34, 52, 'default-pfp.jpg', 0, 0, '2026-06-08 18:11:03'),
(35, 53, 'default-pfp.jpg', 0, 0, '2026-06-08 19:03:41'),
(36, 54, 'default-pfp.jpg', 0, 0, '2026-06-08 19:09:52'),
(37, 55, 'default-pfp.jpg', 0, 0, '2026-06-08 19:10:43'),
(38, 56, 'default-pfp.jpg', 0, 0, '2026-06-08 19:11:24'),
(39, 57, 'default-pfp.jpg', 0, 0, '2026-06-08 19:12:08'),
(40, 58, 'default-pfp.jpg', 0, 0, '2026-06-08 19:12:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_account_preferences`
--

CREATE TABLE `user_account_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_updates` tinyint(1) NOT NULL DEFAULT 1,
  `show_on_leaderboard` tinyint(1) NOT NULL DEFAULT 1,
  `background_music` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_account_preferences`
--

INSERT INTO `user_account_preferences` (`id`, `user_id`, `email_updates`, `show_on_leaderboard`, `background_music`, `updated_at`) VALUES
(2, 1, 1, 1, 0, '2026-06-08 19:14:32'),
(30, 50, 1, 1, 0, '2026-06-08 18:09:49'),
(31, 51, 1, 1, 0, '2026-06-08 18:10:24'),
(32, 52, 1, 1, 0, '2026-06-08 18:11:03'),
(33, 53, 0, 1, 0, '2026-06-08 19:14:48'),
(34, 54, 0, 1, 0, '2026-06-08 19:15:07'),
(35, 55, 0, 1, 0, '2026-06-08 19:15:14'),
(36, 56, 0, 1, 0, '2026-06-08 19:15:21'),
(37, 57, 0, 1, 0, '2026-06-08 19:15:28'),
(38, 58, 0, 1, 0, '2026-06-08 19:15:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_game_progress`
--

CREATE TABLE `user_game_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL DEFAULT 'My Coffee Stand',
  `day` int(11) NOT NULL DEFAULT 1,
  `hour` int(11) NOT NULL DEFAULT 7,
  `minute` int(11) NOT NULL DEFAULT 0,
  `money` int(11) NOT NULL DEFAULT 0,
  `beans` int(11) NOT NULL DEFAULT 250,
  `upgrade_level` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_game_progress`
--

INSERT INTO `user_game_progress` (`id`, `user_id`, `business_name`, `day`, `hour`, `minute`, `money`, `beans`, `upgrade_level`) VALUES
(53, 1, 'My Coffee Stand', 1, 17, 52, 268, 119, 1),
(54, 50, 'Stenders Cafe', 1, 7, 0, 0, 250, 1),
(55, 51, 'Starbucks 2', 1, 7, 0, 0, 250, 1),
(56, 52, 'My Coffee Stand', 1, 7, 0, 0, 250, 1),
(57, 53, 'Starbucks 2', 1, 7, 0, 0, 250, 1),
(58, 54, 'QWERTY Coffee', 1, 7, 0, 0, 250, 1),
(59, 55, 'Romaji Brews', 1, 7, 0, 0, 250, 1),
(60, 56, 'Stenders Kafejnīca', 1, 7, 0, 0, 250, 1),
(61, 57, 'ABC', 1, 7, 0, 0, 250, 1),
(62, 58, 'Random Coffee', 1, 7, 0, 0, 250, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_upgrades`
--

CREATE TABLE `user_upgrades` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upgrade_key` varchar(255) NOT NULL,
  `owned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_upgrades`
--

INSERT INTO `user_upgrades` (`id`, `user_id`, `upgrade_key`, `owned`) VALUES
(651, 1, 'coffeeMachine', 1),
(652, 1, 'businessSign', 1),
(653, 1, 'hireBarista', 1),
(654, 1, 'premiumBeans', 0),
(655, 1, 'biggerCoffeeStand', 0),
(656, 1, 'espressoBeans', 0),
(657, 1, 'espressoMachine', 0),
(658, 1, 'hireFullTimeBarista', 0),
(659, 1, 'biggerBusinessSign', 0),
(660, 1, 'smallCoffeeShop', 0),
(661, 1, 'newMenu', 0),
(662, 1, 'advancedCoffeeMachine', 0),
(663, 1, 'hireManager', 0),
(664, 1, 'betterBranding', 0),
(665, 1, 'mediumCoffeeShop', 0),
(666, 1, 'onlineOrders', 0),
(667, 1, 'hireProBarista', 0),
(668, 1, 'advertising', 0),
(669, 1, 'betterOnlineServer', 0),
(670, 1, 'largeCoffeeShop', 0),
(671, 1, 'futuristicCoffeeMachine', 0),
(672, 1, 'socialMediaMarketing', 0),
(673, 1, 'expandMenu', 0),
(674, 1, 'orderAutomation', 0),
(675, 1, 'coffeeEmpire', 0),
(676, 50, 'coffeeMachine', 0),
(677, 50, 'businessSign', 0),
(678, 50, 'hireBarista', 0),
(679, 50, 'premiumBeans', 0),
(680, 50, 'biggerCoffeeStand', 0),
(681, 50, 'espressoBeans', 0),
(682, 50, 'espressoMachine', 0),
(683, 50, 'hireFullTimeBarista', 0),
(684, 50, 'biggerBusinessSign', 0),
(685, 50, 'smallCoffeeShop', 0),
(686, 50, 'newMenu', 0),
(687, 50, 'advancedCoffeeMachine', 0),
(688, 50, 'hireManager', 0),
(689, 50, 'betterBranding', 0),
(690, 50, 'mediumCoffeeShop', 0),
(691, 50, 'onlineOrders', 0),
(692, 50, 'hireProBarista', 0),
(693, 50, 'advertising', 0),
(694, 50, 'betterOnlineServer', 0),
(695, 50, 'largeCoffeeShop', 0),
(696, 50, 'futuristicCoffeeMachine', 0),
(697, 50, 'socialMediaMarketing', 0),
(698, 50, 'expandMenu', 0),
(699, 50, 'orderAutomation', 0),
(700, 50, 'coffeeEmpire', 0),
(701, 51, 'coffeeMachine', 0),
(702, 51, 'businessSign', 0),
(703, 51, 'hireBarista', 0),
(704, 51, 'premiumBeans', 0),
(705, 51, 'biggerCoffeeStand', 0),
(706, 51, 'espressoBeans', 0),
(707, 51, 'espressoMachine', 0),
(708, 51, 'hireFullTimeBarista', 0),
(709, 51, 'biggerBusinessSign', 0),
(710, 51, 'smallCoffeeShop', 0),
(711, 51, 'newMenu', 0),
(712, 51, 'advancedCoffeeMachine', 0),
(713, 51, 'hireManager', 0),
(714, 51, 'betterBranding', 0),
(715, 51, 'mediumCoffeeShop', 0),
(716, 51, 'onlineOrders', 0),
(717, 51, 'hireProBarista', 0),
(718, 51, 'advertising', 0),
(719, 51, 'betterOnlineServer', 0),
(720, 51, 'largeCoffeeShop', 0),
(721, 51, 'futuristicCoffeeMachine', 0),
(722, 51, 'socialMediaMarketing', 0),
(723, 51, 'expandMenu', 0),
(724, 51, 'orderAutomation', 0),
(725, 51, 'coffeeEmpire', 0),
(726, 52, 'coffeeMachine', 0),
(727, 52, 'businessSign', 0),
(728, 52, 'hireBarista', 0),
(729, 52, 'premiumBeans', 0),
(730, 52, 'biggerCoffeeStand', 0),
(731, 52, 'espressoBeans', 0),
(732, 52, 'espressoMachine', 0),
(733, 52, 'hireFullTimeBarista', 0),
(734, 52, 'biggerBusinessSign', 0),
(735, 52, 'smallCoffeeShop', 0),
(736, 52, 'newMenu', 0),
(737, 52, 'advancedCoffeeMachine', 0),
(738, 52, 'hireManager', 0),
(739, 52, 'betterBranding', 0),
(740, 52, 'mediumCoffeeShop', 0),
(741, 52, 'onlineOrders', 0),
(742, 52, 'hireProBarista', 0),
(743, 52, 'advertising', 0),
(744, 52, 'betterOnlineServer', 0),
(745, 52, 'largeCoffeeShop', 0),
(746, 52, 'futuristicCoffeeMachine', 0),
(747, 52, 'socialMediaMarketing', 0),
(748, 52, 'expandMenu', 0),
(749, 52, 'orderAutomation', 0),
(750, 52, 'coffeeEmpire', 0),
(751, 53, 'coffeeMachine', 0),
(752, 53, 'businessSign', 0),
(753, 53, 'hireBarista', 0),
(754, 53, 'premiumBeans', 0),
(755, 53, 'biggerCoffeeStand', 0),
(756, 53, 'espressoBeans', 0),
(757, 53, 'espressoMachine', 0),
(758, 53, 'hireFullTimeBarista', 0),
(759, 53, 'biggerBusinessSign', 0),
(760, 53, 'smallCoffeeShop', 0),
(761, 53, 'newMenu', 0),
(762, 53, 'advancedCoffeeMachine', 0),
(763, 53, 'hireManager', 0),
(764, 53, 'betterBranding', 0),
(765, 53, 'mediumCoffeeShop', 0),
(766, 53, 'onlineOrders', 0),
(767, 53, 'hireProBarista', 0),
(768, 53, 'advertising', 0),
(769, 53, 'betterOnlineServer', 0),
(770, 53, 'largeCoffeeShop', 0),
(771, 53, 'futuristicCoffeeMachine', 0),
(772, 53, 'socialMediaMarketing', 0),
(773, 53, 'expandMenu', 0),
(774, 53, 'orderAutomation', 0),
(775, 53, 'coffeeEmpire', 0),
(776, 54, 'coffeeMachine', 0),
(777, 54, 'businessSign', 0),
(778, 54, 'hireBarista', 0),
(779, 54, 'premiumBeans', 0),
(780, 54, 'biggerCoffeeStand', 0),
(781, 54, 'espressoBeans', 0),
(782, 54, 'espressoMachine', 0),
(783, 54, 'hireFullTimeBarista', 0),
(784, 54, 'biggerBusinessSign', 0),
(785, 54, 'smallCoffeeShop', 0),
(786, 54, 'newMenu', 0),
(787, 54, 'advancedCoffeeMachine', 0),
(788, 54, 'hireManager', 0),
(789, 54, 'betterBranding', 0),
(790, 54, 'mediumCoffeeShop', 0),
(791, 54, 'onlineOrders', 0),
(792, 54, 'hireProBarista', 0),
(793, 54, 'advertising', 0),
(794, 54, 'betterOnlineServer', 0),
(795, 54, 'largeCoffeeShop', 0),
(796, 54, 'futuristicCoffeeMachine', 0),
(797, 54, 'socialMediaMarketing', 0),
(798, 54, 'expandMenu', 0),
(799, 54, 'orderAutomation', 0),
(800, 54, 'coffeeEmpire', 0),
(801, 55, 'coffeeMachine', 0),
(802, 55, 'businessSign', 0),
(803, 55, 'hireBarista', 0),
(804, 55, 'premiumBeans', 0),
(805, 55, 'biggerCoffeeStand', 0),
(806, 55, 'espressoBeans', 0),
(807, 55, 'espressoMachine', 0),
(808, 55, 'hireFullTimeBarista', 0),
(809, 55, 'biggerBusinessSign', 0),
(810, 55, 'smallCoffeeShop', 0),
(811, 55, 'newMenu', 0),
(812, 55, 'advancedCoffeeMachine', 0),
(813, 55, 'hireManager', 0),
(814, 55, 'betterBranding', 0),
(815, 55, 'mediumCoffeeShop', 0),
(816, 55, 'onlineOrders', 0),
(817, 55, 'hireProBarista', 0),
(818, 55, 'advertising', 0),
(819, 55, 'betterOnlineServer', 0),
(820, 55, 'largeCoffeeShop', 0),
(821, 55, 'futuristicCoffeeMachine', 0),
(822, 55, 'socialMediaMarketing', 0),
(823, 55, 'expandMenu', 0),
(824, 55, 'orderAutomation', 0),
(825, 55, 'coffeeEmpire', 0),
(826, 56, 'coffeeMachine', 0),
(827, 56, 'businessSign', 0),
(828, 56, 'hireBarista', 0),
(829, 56, 'premiumBeans', 0),
(830, 56, 'biggerCoffeeStand', 0),
(831, 56, 'espressoBeans', 0),
(832, 56, 'espressoMachine', 0),
(833, 56, 'hireFullTimeBarista', 0),
(834, 56, 'biggerBusinessSign', 0),
(835, 56, 'smallCoffeeShop', 0),
(836, 56, 'newMenu', 0),
(837, 56, 'advancedCoffeeMachine', 0),
(838, 56, 'hireManager', 0),
(839, 56, 'betterBranding', 0),
(840, 56, 'mediumCoffeeShop', 0),
(841, 56, 'onlineOrders', 0),
(842, 56, 'hireProBarista', 0),
(843, 56, 'advertising', 0),
(844, 56, 'betterOnlineServer', 0),
(845, 56, 'largeCoffeeShop', 0),
(846, 56, 'futuristicCoffeeMachine', 0),
(847, 56, 'socialMediaMarketing', 0),
(848, 56, 'expandMenu', 0),
(849, 56, 'orderAutomation', 0),
(850, 56, 'coffeeEmpire', 0),
(851, 57, 'coffeeMachine', 0),
(852, 57, 'businessSign', 0),
(853, 57, 'hireBarista', 0),
(854, 57, 'premiumBeans', 0),
(855, 57, 'biggerCoffeeStand', 0),
(856, 57, 'espressoBeans', 0),
(857, 57, 'espressoMachine', 0),
(858, 57, 'hireFullTimeBarista', 0),
(859, 57, 'biggerBusinessSign', 0),
(860, 57, 'smallCoffeeShop', 0),
(861, 57, 'newMenu', 0),
(862, 57, 'advancedCoffeeMachine', 0),
(863, 57, 'hireManager', 0),
(864, 57, 'betterBranding', 0),
(865, 57, 'mediumCoffeeShop', 0),
(866, 57, 'onlineOrders', 0),
(867, 57, 'hireProBarista', 0),
(868, 57, 'advertising', 0),
(869, 57, 'betterOnlineServer', 0),
(870, 57, 'largeCoffeeShop', 0),
(871, 57, 'futuristicCoffeeMachine', 0),
(872, 57, 'socialMediaMarketing', 0),
(873, 57, 'expandMenu', 0),
(874, 57, 'orderAutomation', 0),
(875, 57, 'coffeeEmpire', 0),
(876, 58, 'coffeeMachine', 0),
(877, 58, 'businessSign', 0),
(878, 58, 'hireBarista', 0),
(879, 58, 'premiumBeans', 0),
(880, 58, 'biggerCoffeeStand', 0),
(881, 58, 'espressoBeans', 0),
(882, 58, 'espressoMachine', 0),
(883, 58, 'hireFullTimeBarista', 0),
(884, 58, 'biggerBusinessSign', 0),
(885, 58, 'smallCoffeeShop', 0),
(886, 58, 'newMenu', 0),
(887, 58, 'advancedCoffeeMachine', 0),
(888, 58, 'hireManager', 0),
(889, 58, 'betterBranding', 0),
(890, 58, 'mediumCoffeeShop', 0),
(891, 58, 'onlineOrders', 0),
(892, 58, 'hireProBarista', 0),
(893, 58, 'advertising', 0),
(894, 58, 'betterOnlineServer', 0),
(895, 58, 'largeCoffeeShop', 0),
(896, 58, 'futuristicCoffeeMachine', 0),
(897, 58, 'socialMediaMarketing', 0),
(898, 58, 'expandMenu', 0),
(899, 58, 'orderAutomation', 0),
(900, 58, 'coffeeEmpire', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_account_details`
--
ALTER TABLE `user_account_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_account_preferences`
--
ALTER TABLE `user_account_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_game_progress`
--
ALTER TABLE `user_game_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `relation3` (`user_id`);

--
-- Indexes for table `user_upgrades`
--
ALTER TABLE `user_upgrades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `relation4` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registered_users`
--
ALTER TABLE `registered_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `user_account_details`
--
ALTER TABLE `user_account_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `user_account_preferences`
--
ALTER TABLE `user_account_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `user_game_progress`
--
ALTER TABLE `user_game_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `user_upgrades`
--
ALTER TABLE `user_upgrades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=901;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_account_details`
--
ALTER TABLE `user_account_details`
  ADD CONSTRAINT `relation1` FOREIGN KEY (`user_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_account_preferences`
--
ALTER TABLE `user_account_preferences`
  ADD CONSTRAINT `relation2` FOREIGN KEY (`user_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_game_progress`
--
ALTER TABLE `user_game_progress`
  ADD CONSTRAINT `relation3` FOREIGN KEY (`user_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_upgrades`
--
ALTER TABLE `user_upgrades`
  ADD CONSTRAINT `relation4` FOREIGN KEY (`user_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
