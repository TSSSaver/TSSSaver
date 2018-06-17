SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS tsssaver;

USE tsssaver;

CREATE TABLE `devices` (
  `deviceIdentifier` text NOT NULL,
  `deviceType` text NOT NULL,
  `deviceID` text NOT NULL,
  `deviceECID` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `devices`
  ADD PRIMARY KEY (`deviceECID`),
  ADD UNIQUE KEY `deviceECID` (`deviceECID`);