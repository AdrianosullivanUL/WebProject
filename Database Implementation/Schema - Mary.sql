-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 27, 2018 at 01:14 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `group05`
--

-- --------------------------------------------------------

--
-- Table structure for table `black_list_words`
-- This table flags black listed words and identifies user that should be added to the black list
--


-- --------------------------------------------------------

--
-- Table structure for table `city`
-- Store the locations of the User and match
--


-- --------------------------------------------------------

-- 
-- Table structure for table `gender`
-- ID for gender of user and gender preference, 
-- 

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `gender_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `interests`
-- Table f
--


-- --------------------------------------------------------

--
-- Table structure for table `match_table`
--


-- --------------------------------------------------------

--
-- Table structure for table `relationship_type`
--



-- --------------------------------------------------------

--
-- Table structure for table `status`
-- 


-- --------------------------------------------------------

--
-- Table structure for table `user_communication`
--



-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
-- User interests table where each entry is for one users interests
-- The primary key is a combination of (user_id, interest_id)
--


-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
-- This table si the user profile containing all personal details of a user. Primary Key is ID

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `password_hash` varchar(200) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `gender_preference_id` int(11) DEFAULT NULL,
  `From_age` int(11) DEFAULT NULL,
  `to_age` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `Travel_distance` int(11) DEFAULT NULL,
  `relationship_type_id` int(11) DEFAULT NULL,
  `picture` blob,
  `my_bio` varchar(1000) DEFAULT NULL,
  `black_listed_user` tinyint(1) DEFAULT NULL,
  `black_listed_reason` varchar(100) NOT NULL,
  `black_listed_date` date DEFAULT NULL,
  `user_status` varchar(50) NOT NULL,
  `is_administrator` boolean NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

