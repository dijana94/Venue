--
-- Database: `venue`
--
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
-- --------------------------------------------------------
--
-- Database: `venue`
--

-- --------------------------------------------------------

--
-- Table structure for table `buysticketsfor`
--

CREATE TABLE `buysticketsfor` (
  `ticketID` int(11) NOT NULL,
  `branchID` int(11) DEFAULT NULL,
  `evid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buysticketsfor`
--

INSERT INTO `buysticketsfor` (`ticketID`, `branchID`, `evid`, `cid`) VALUES
(1, 124566, 3, 1),
(2, 124569, 4, 7),
(3, 124570, 2, 1),
(4, 124569, 4, 3),
(5, 124569, 4, 2),
(6, 124570, 1, 6),
(7, 124566, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` int(11) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `l_name` varchar(20) NOT NULL,
  `hotness` int(11) DEFAULT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cid`, `f_name`, `l_name`, `hotness`, `email`, `password`) VALUES
(1, 'Diana', 'Jagodic', 10, 'diana@ubccs.ca', 'diana'),
(2, 'Danya', 'Karras', 10, 'danya@ubccs.ca', 'danya'),
(3, 'Eric', 'Thompson', 7, 'nerd92@gmail.com', 'eric'),
(4, 'Alena', 'Safina', 10, 'alena@ubccs.ca', 'alena'),
(5, 'Michael', 'Young', 9, 'hot@hotmail.ca', 'imhot'),
(6, 'Robby', 'Dennis', NULL, 'robby@gmail.com', 'robby'),
(7, 'Rick', 'Martinez', NULL, 'rickym@yahoo.com', 'ricky');

-- --------------------------------------------------------

--
-- Table structure for table `entertainment`
--

CREATE TABLE `entertainment` (
  `enid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `genre` varchar(20) DEFAULT NULL,
  `cost` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `entertainment`
--

INSERT INTO `entertainment` (`enid`, `name`, `genre`, `cost`) VALUES
(1, 'Lordi', 'rock', 3500),
(2, 'Space Girls', 'pop', 10000),
(3, 'Truck Boiz', 'country', 10),
(4, 'Skrillerz', 'trap', 6000),
(5, 'Pentatonix', 'acapella', 4000);

-- --------------------------------------------------------

--
-- Table structure for table `hostedevent`
--

CREATE TABLE `hostedevent` (
  `evid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `branchID` int(11) DEFAULT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hostedevent`
--

INSERT INTO `hostedevent` (`evid`, `name`, `date`, `start_time`, `branchID`, `price`) VALUES
(1, 'Zumba Night', '2016-11-16', '22:00:00', 124570, 20),
(2, 'Crazy Train', '2016-11-29', '19:00:00', 124570, 25.65),
(3, 'Intergalactic Rave', '2016-12-31', '19:00:00', 124566, 34.99),
(4, 'Seashore Gala', '2017-02-14', '17:00:00', 124569, 79.95),
(5, 'Bones', '2016-11-25', '19:00:00', 124569, 54.95),
(6, 'Superhero Night', '2016-11-30', '18:00:00', 124565, 24.99);

-- --------------------------------------------------------

--
-- Table structure for table `playsat`
--

CREATE TABLE `playsat` (
  `evid` int(11) DEFAULT NULL,
  `enid` int(11) DEFAULT NULL,
  `branchID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `playsat`
--

INSERT INTO `playsat` (`evid`, `enid`, `branchID`) VALUES
(4, 5, 124569),
(2, 3, 124570),
(3, 2, 124566),
(5, 5, 124569),
(6, 1, 124565),
(1, 4, 124570);

-- --------------------------------------------------------

--
-- Table structure for table `staffemployed`
--

CREATE TABLE `staffemployed` (
  `sid` int(11) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `l_name` varchar(20) NOT NULL,
  `branchID` int(11) DEFAULT NULL,
  `manager` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staffemployed`
--

INSERT INTO `staffemployed` (`sid`, `f_name`, `l_name`, `branchID`, `manager`) VALUES
(10000, 'Joanna', 'White', 124569, 1),
(10001, 'Jenny', 'Lam', 124570, 1),
(10002, 'Priya', 'Kapoor', 124568, 1),
(10003, 'Leonard', 'Roberts', 124566, 1),
(10004, 'Jackson', 'Jonson', 124565, 1),
(10005, 'Bob', 'Dibbles', 124569, 0),
(10006, 'Donald', 'Drumph', 124570, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tablereservation`
--

CREATE TABLE `tablereservation` (
  `confirmationNum` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `numOfGuests` int(11) NOT NULL,
  `cid` int(11) DEFAULT NULL,
  `tableNum` int(11) DEFAULT NULL,
  `branchID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tablereservation`
--

INSERT INTO `tablereservation` (`confirmationNum`, `date`, `time`, `numOfGuests`, `cid`, `tableNum`, `branchID`) VALUES
(1000, '2017-02-14', '17:00:00', 1, 7, 1, 124569),
(1001, '2017-02-14', '17:00:00', 1, 3, 1, 124569),
(1002, '2016-12-03', '09:00:00', 1, 1, 6, 124568),
(1003, '2016-11-20', '09:00:00', 1, 2, 2, 124570);

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `branchID` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `address` varchar(64) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `cover_charge` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`branchID`, `name`, `address`, `capacity`, `cover_charge`) VALUES
(124565, 'TGIF', '6371 Crescent Rd, Vancouver, BC, Canada', 100, 12),
(124566, 'Fortune', '1022 Davie St, Vancouver, BC, Canada', 250, 10),
(124567, 'Renegades', '750 Pacific Blvd Vancouver, BC, Canada', 200, 5),
(124568, 'Stargazer', '881 Granville St, Vancouver, BC, Canada', 200, 10),
(124569, 'Blue Lagoon', '350 Water St, Vancouver, BC, Canada', 500, 15),
(124570, 'Thrills', '2010 W 4th Ave Vancouver, BC, Canada', 300, 8);

-- --------------------------------------------------------

--
-- Table structure for table `venuehastable`
--

CREATE TABLE `venuehastable` (
  `tableNum` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `numOfTableType` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `cost` double NOT NULL,
  `branchID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venuehastable`
--

INSERT INTO `venuehastable` (`tableNum`, `size`, `numOfTableType`, `type`, `cost`, `branchID`) VALUES
(1, 2, 10, 'intimate', 30, 124565),
(2, 8, 5, 'bar', 100, 124565),
(3, 10, 15, 'regular', 50, 124565),
(4, 8, 10, 'booth', 20, 124565),
(5, 1, 30, 'bar', 5, 124566),
(6, 4, 20, 'regular', 9.95, 124566),
(7, 1, 32, 'bar', 6, 124567),
(8, 2, 15, 'intimate', 14.95, 124567),
(9, 5, 9, 'booth', 24.99, 124567),
(10, 4, 8, 'booth', 20, 124568),
(11, 2, 17, 'intimate', 16.95, 124568),
(12, 6, 20, 'patio', 30, 124568),
(13, 6, 8, 'patio', 11.95, 124569),
(14, 5, 9, 'patio', 7.99, 124570),
(15, 4, 20, 'regular', 4.99, 124569),
(16, 2, 8, 'intimate', 9.99, 124569),
(17, 1, 26, 'bar', 3, 124569),
(18, 2, 12, 'intimate', 5.99, 124570),
(19, 10, 30, 'regular', 6.99, 124570),
(20, 2, 10, 'intimate', 12.95, 124566),
(21, 1, 30, 'bar', 3.95, 124568),
(22, 6, 15, 'regular', 7.99, 124567),
(23, 1, 30, 'bar', 3.95, 124570),
(24, 6, 15, 'regular', 7.99, 124568);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buysticketsfor`
--
ALTER TABLE `buysticketsfor`
  ADD PRIMARY KEY (`ticketID`),
  ADD KEY `buysticketsfor_ibfk_3` (`cid`),
  ADD KEY `buysticketsfor_ibfk_2` (`evid`),
  ADD KEY `buysticketsfor_ibfk_1` (`branchID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `entertainment`
--
ALTER TABLE `entertainment`
  ADD PRIMARY KEY (`enid`);

--
-- Indexes for table `hostedevent`
--
ALTER TABLE `hostedevent`
  ADD PRIMARY KEY (`evid`),
  ADD KEY `hostedevent_ifkb_1` (`branchID`);

--
-- Indexes for table `playsat`
--
ALTER TABLE `playsat`
  ADD KEY `playsat_ibfk_2` (`enid`),
  ADD KEY `playsat_ibfk_1` (`evid`),
  ADD KEY `playsat_ibfk_3` (`branchID`);

--
-- Indexes for table `staffemployed`
--
ALTER TABLE `staffemployed`
  ADD PRIMARY KEY (`sid`),
  ADD KEY `staffemployed_ifkb_1` (`branchID`);

--
-- Indexes for table `tablereservation`
--
ALTER TABLE `tablereservation`
  ADD PRIMARY KEY (`confirmationNum`),
  ADD KEY `tablereservation_ibfk_2` (`tableNum`),
  ADD KEY `tablereservation_ibfk_1` (`cid`),
  ADD KEY `tablereservation_ibfk_3` (`branchID`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`branchID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `venuehastable`
--
ALTER TABLE `venuehastable`
  ADD PRIMARY KEY (`tableNum`),
  ADD KEY `venuehastable_1` (`branchID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buysticketsfor`
--
ALTER TABLE `buysticketsfor`
  MODIFY `ticketID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `entertainment`
--
ALTER TABLE `entertainment`
  MODIFY `enid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `hostedevent`
--
ALTER TABLE `hostedevent`
  MODIFY `evid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `staffemployed`
--
ALTER TABLE `staffemployed`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10007;
--
-- AUTO_INCREMENT for table `tablereservation`
--
ALTER TABLE `tablereservation`
  MODIFY `confirmationNum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;
--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `branchID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124571;
--
-- AUTO_INCREMENT for table `venuehastable`
--
ALTER TABLE `venuehastable`
  MODIFY `tableNum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `buysticketsfor`
--
ALTER TABLE `buysticketsfor`
  ADD CONSTRAINT `buysticketsfor_ibfk_1` FOREIGN KEY (`branchID`) REFERENCES `venue` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buysticketsfor_ibfk_2` FOREIGN KEY (`evid`) REFERENCES `hostedevent` (`evid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buysticketsfor_ibfk_3` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hostedevent`
--
ALTER TABLE `hostedevent`
  ADD CONSTRAINT `hostedevent_ifkb_1` FOREIGN KEY (`branchID`) REFERENCES `venue` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playsat`
--
ALTER TABLE `playsat`
  ADD CONSTRAINT `playsat_ibfk_1` FOREIGN KEY (`evid`) REFERENCES `hostedevent` (`evid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `playsat_ibfk_2` FOREIGN KEY (`enid`) REFERENCES `entertainment` (`enid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `playsat_ibfk_3` FOREIGN KEY (`branchID`) REFERENCES `venue` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staffemployed`
--
ALTER TABLE `staffemployed`
  ADD CONSTRAINT `staffemployed_ifkb_1` FOREIGN KEY (`branchID`) REFERENCES `venue` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tablereservation`
--
ALTER TABLE `tablereservation`
  ADD CONSTRAINT `tablereservation_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`),
  ADD CONSTRAINT `tablereservation_ibfk_2` FOREIGN KEY (`tableNum`) REFERENCES `venuehastable` (`tableNum`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tablereservation_ibfk_3` FOREIGN KEY (`branchID`) REFERENCES `venue` (`branchID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
