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

CREATE TABLE `black_list_words` (
  `id` int(11) NOT NULL,
  `word` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `city`
-- Store the locations of the User and match
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `city` varchar(200) NOT NULL,
  `count` varchar(200) NOT NULL
);

-- --------------------------------------------------------

-- 
-- Table structure for table `gender`
-- ID for gender of user and gender preference, 
-- 

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `Gender_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `interests`
-- Table f
--

CREATE TABLE `interests` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `match_table`
--

CREATE TABLE `match_table` (
  `id` int(11) NOT NULL,
  `Initiating_user_id` int(11) DEFAULT NULL,
  `initiation_date` datetime DEFAULT NULL,
  `initiation_interest_level` int(11) DEFAULT NULL,
  `initiation_prefered_meeting_location` varchar(150) NOT NULL,
  `initiating_preferred_meet_datetime` datetime DEFAULT NULL,
  `match_status_id` int(11) DEFAULT NULL,
  `status_date` datetime DEFAULT NULL,
  `reciprocating_user_id` int(11) DEFAULT NULL,
  `reciprocating_response_date` datetime DEFAULT NULL,
  `reciprocating_response` varchar(2000) NOT NULL,
  `reciprocating_interest_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `relationship_type`
--

CREATE TABLE `relationship_type` (
  `id` int(11) NOT NULL,
  `relationship_type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
-- 

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_communication`
--

CREATE TABLE `user_communication` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `message` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL,
  `status_date` date DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `replying_to_communication_id` int(11) DEFAULT NULL,
  `black_listed` tinyint(1) DEFAULT NULL,
  `black_listed_date` datetime DEFAULT NULL,
  `black_listed_word_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
-- User interests table where each entry is for one users interests
-- The primary key is a combination of (user_id, interest_id)
--

CREATE TABLE `user_interests` (
  `user_id` int(11) NOT NULL,
  `interest_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `user_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

