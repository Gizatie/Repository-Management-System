-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2022 at 11:37 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `repository`
--

-- --------------------------------------------------------

--
-- Table structure for table `community_service_budget_details`
--

CREATE TABLE `community_service_budget` (
  `Proposal_ID` int(11) NOT NULL,
  `Duplication_and_Stationery` double DEFAULT NULL,
  `Investigators_perdiem_for_supervision` double DEFAULT NULL,
  `Investigators_perdiem_for_training_and_pre_test` double DEFAULT NULL,
  `Data_collectors_perdiem_for_training_and_pre_test` double DEFAULT NULL,
  `Data_collectors_perdiem_for_data_collection` double DEFAULT NULL,
  `identification_of_eligible_study` double DEFAULT NULL,
  `data_entry` double DEFAULT NULL,
  `Transport_cost` double DEFAULT NULL,
  `Transport_cost_for_purchasing` double DEFAULT NULL,
  `Perdiem_for_purchasing` double DEFAULT NULL,
  `Perdiem_for_laboratory_work` double DEFAULT NULL,
  `Materials_tobe_Purchased` double DEFAULT NULL,
  `Software_development` double DEFAULT NULL,
  `Daily_labourer_payment` double DEFAULT NULL,
  `Land_rent` double DEFAULT NULL,
  `Laboratory_setup_cost` double DEFAULT NULL,
  `Laboratory_Technician_cost` double DEFAULT NULL,
  `Focused_group_discussion` double DEFAULT NULL,
  `Local_transport` double DEFAULT NULL,
  `Guider_cost` double DEFAULT NULL,
  `Security_cost` double DEFAULT NULL,
  `Boat_rent` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `community_service_budget_details`
--

INSERT INTO `community_service_budget_details` (`Proposal_ID`, `Duplication_and_Stationery`, `Investigators_perdiem_for_supervision`, `Investigators_perdiem_for_training_and_pre_test`, `Data_collectors_perdiem_for_training_and_pre_test`, `Data_collectors_perdiem_for_data_collection`, `identification_of_eligible_study`, `data_entry`, `Transport_cost`, `Transport_cost_for_purchasing`, `Perdiem_for_purchasing`, `Perdiem_for_laboratory_work`, `Materials_tobe_Purchased`, `Software_development`, `Daily_labourer_payment`, `Land_rent`, `Laboratory_setup_cost`, `Laboratory_Technician_cost`, `Focused_group_discussion`, `Local_transport`, `Guider_cost`, `Security_cost`, `Boat_rent`) VALUES
(203, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(205, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(206, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `community_service_budget_details`
--
ALTER TABLE `community_service_budget_details`
  ADD PRIMARY KEY (`Proposal_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
