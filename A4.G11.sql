-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: db.cs.dal.ca
-- Generation Time: Jul 24, 2021 at 02:56 PM
-- Server version: 10.3.21-MariaDB
-- PHP Version: 7.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zhaohe`
--

-- --------------------------------------------------------

--
-- Table structure for table `AgentPassword_011`
--

CREATE TABLE `AgentPassword_011` (
  `agentUsername_011` varchar(16) NOT NULL,
  `agentPassword_011` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `AgentPassword_011`
--

INSERT INTO `AgentPassword_011` (`agentUsername_011`, `agentPassword_011`) VALUES
('agt1', 'agt1'),
('agt2', 'agt2');

-- --------------------------------------------------------

--
-- Table structure for table `Agent_Credential_011`
--

CREATE TABLE `Agent_Credential_011` (
  `agentId_011` int(11) NOT NULL,
  `agentUsername_011` varchar(16) NOT NULL,
  `agentPassword_011` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Agent_Credential_011`
--

INSERT INTO `Agent_Credential_011` (`agentId_011`, `agentUsername_011`, `agentPassword_011`) VALUES
(1, 'agt1', 'agt1');

-- --------------------------------------------------------

--
-- Table structure for table `Client_Credential_011`
--

CREATE TABLE `Client_Credential_011` (
  `clientCompId_011` int(11) NOT NULL,
  `clientUsername_011` varchar(16) NOT NULL,
  `clientPassword_011` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Client_Credential_011`
--

INSERT INTO `Client_Credential_011` (`clientCompId_011`, `clientUsername_011`, `clientPassword_011`) VALUES
(100001, 'cus1', 'cus1'),
(100002, 'cus2', 'cus2');

-- --------------------------------------------------------

--
-- Table structure for table `Client_User_011`
--

CREATE TABLE `Client_User_011` (
  `clientCompId_011` int(11) NOT NULL,
  `clientCompName_011` varchar(45) DEFAULT NULL,
  `clientCity_011` varchar(45) DEFAULT NULL,
  `clientCompPswd_011` varchar(45) NOT NULL,
  `clientBalance_011` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Client_User_011`
--

INSERT INTO `Client_User_011` (`clientCompId_011`, `clientCompName_011`, `clientCity_011`, `clientCompPswd_011`, `clientBalance_011`) VALUES
(100001, 'Ford Canada', 'Walkerville', 'abc123', '8980400'),
(100002, 'Toyota', 'Viva la Test', 'abc124', '96000000');

-- --------------------------------------------------------

--
-- Table structure for table `Parts_011`
--

CREATE TABLE `Parts_011` (
  `partNo_011` int(11) NOT NULL,
  `partName_011` varchar(45) NOT NULL,
  `partDescription_011` varchar(45) DEFAULT NULL,
  `partCurrentPrice_011` decimal(10,0) NOT NULL,
  `partQty_011` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Parts_011`
--

INSERT INTO `Parts_011` (`partNo_011`, `partName_011`, `partDescription_011`, `partCurrentPrice_011`, `partQty_011`) VALUES
(1, 'Wheel', 'Made in China', '200', 292),
(2, 'Front Glass', 'Bulletproof', '1000', 31),
(3, 'Back Glass', 'Not really bulletproof', '500', 112),
(4, 'Rear view mirrors', 'Made in China', '200', 80),
(5, 'bumper', 'Strong', '1500', 50),
(6, 'test2', 'Made in U.S.', '50', 90);

-- --------------------------------------------------------

--
-- Table structure for table `PO_011`
--

CREATE TABLE `PO_011` (
  `poNo_011` int(11) NOT NULL,
  `clientCompId_011` int(11) NOT NULL,
  `datePO_011` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status_011` enum('processing','canceled','confirmed','shipped','delivering','completed') NOT NULL,
  `subtotal_011` decimal(10,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PO_011`
--

INSERT INTO `PO_011` (`poNo_011`, `clientCompId_011`, `datePO_011`, `status_011`, `subtotal_011`) VALUES
(10153, 100001, '2021-07-24 17:34:27', 'confirmed', '10000'),
(10154, 100001, '2021-07-24 14:10:16', 'processing', '5000'),
(10155, 100001, '2021-07-24 14:51:27', 'confirmed', '2600'),
(10156, 100001, '2021-07-24 14:56:25', 'processing', '19500'),
(10157, 100001, '2021-07-24 17:39:44', 'processing', '21500');

--
-- Triggers `PO_011`
--
DELIMITER $$
CREATE TRIGGER `TRG_CANCEL_PROCESSING_PO_RETURN_FUND` AFTER UPDATE ON `PO_011` FOR EACH ROW BEGIN
	IF
		NEW.status_011 = 'canceled' AND OLD.status_011 = 'processing'
	THEN
		UPDATE Client_User_011
		SET clientBalance_011 = clientBalance_011 + (SELECT SUM(qty_011*linePrice_011)
		FROM PO_011 JOIN PO_Lines_011 USING (poNo_011)
		GROUP BY poNo_011
		HAVING poNo_011 = NEW.poNo_011)
		WHERE clientCompId_011 = NEW.clientCompId_011 AND NEW.status_011 = 'canceled' AND OLD.status_011 = 'processing';
	ELSEIF
		NEW.status_011 = 'canceled' AND OLD.status_011 != 'processing'
	THEN
		UPDATE Client_User_011
		SET clientBalance_011 = clientBalance_011 + 0.9* (SELECT SUM(qty_011*linePrice_011)
		FROM PO_011 JOIN PO_Lines_011 USING (poNo_011)
		GROUP BY poNo_011
		HAVING poNo_011 = NEW.poNo_011)
		WHERE clientCompId_011 = NEW.clientCompId_011 AND NEW.status_011 = 'canceled' AND OLD.status_011 != 'processing';
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TRG_CANCEL_PROCESSING_PO_RETURN_STOCK` AFTER UPDATE ON `PO_011` FOR EACH ROW BEGIN
	DECLARE done INT DEFAULT FALSE;
   DECLARE ids INT;
   DECLARE ids2 INT;
   DECLARE cur1 CURSOR FOR SELECT partNo_011, qty_011 FROM (SELECT partNo_011, qty_011, poNo_011
															 FROM PO_Lines_011 JOIN (SELECT DISTINCT(poNo_011) FROM PO_011 WHERE NEW.status_011 = 'canceled' AND OLD.status_011 != 'canceled') AS P USING (poNo_011)) AS Q;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur1;
		ins_loop: LOOP
			FETCH cur1 INTO ids, ids2;
            IF done THEN
				LEAVE ins_loop;
			END IF;
            UPDATE Parts_011
			SET Parts_011.partQty_011 = Parts_011.partQty_011 + ids2
			WHERE Parts_011.partNo_011 = ids;
		END LOOP;
	CLOSE cur1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PO_Lines_011`
--

CREATE TABLE `PO_Lines_011` (
  `LineNO_011` int(11) NOT NULL,
  `partNo_011` int(11) NOT NULL,
  `poNo_011` int(11) NOT NULL,
  `qty_011` int(11) NOT NULL,
  `linePrice_011` decimal(10,0) NOT NULL DEFAULT 0,
  `status_011` enum('processing','canceled','confirmed','shipped','delivering','completed') DEFAULT 'processing'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PO_Lines_011`
--

INSERT INTO `PO_Lines_011` (`LineNO_011`, `partNo_011`, `poNo_011`, `qty_011`, `linePrice_011`, `status_011`) VALUES
(99, 1, 10153, 2, '200', 'processing'),
(100, 4, 10153, 3, '200', 'processing'),
(101, 2, 10153, 4, '1000', 'processing'),
(102, 1, 10154, 2, '200', 'processing'),
(103, 4, 10154, 3, '200', 'processing'),
(104, 2, 10154, 4, '1000', 'processing'),
(105, 1, 10155, 3, '200', 'processing'),
(106, 3, 10155, 4, '500', 'processing'),
(107, 2, 10156, 6, '1000', 'processing'),
(108, 5, 10156, 9, '1500', 'processing'),
(109, 5, 10157, 9, '1500', 'processing'),
(110, 2, 10157, 8, '1000', 'processing');

--
-- Triggers `PO_Lines_011`
--
DELIMITER $$
CREATE TRIGGER `TRG_CALCULATE_SUBTOTAL` AFTER INSERT ON `PO_Lines_011` FOR EACH ROW UPDATE PO_011 SET subtotal_011 = subtotal_011 + NEW.qty_011 * NEW.linePrice_011 WHERE poNo_011 = NEW.poNo_011
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TRG_POST_NEW_ORDER` AFTER UPDATE ON `PO_Lines_011` FOR EACH ROW BEGIN
	UPDATE Parts_011
	SET Parts_011.partQty_011 = Parts_011.partQty_011 - NEW.qty_011
	WHERE NEW.status_011 = 'confirmed' AND Parts_011.partNo_011 = NEW.partNo_011;
    
    UPDATE Client_User_011
    SET clientBalance_011 = clientBalance_011 - NEW.qty_011 * NEW.linePrice_011
    WHERE NEW.status_011 = 'confirmed' AND clientCompId_011 = (SELECT DISTINCT(PO_011.clientCompId_011) FROM PO_Lines_011 JOIN PO_011 USING (poNo_011) WHERE PO_011.poNo_011 = NEW.poNo_011);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TRG_POST_NEW_PO_LINE_SET_LinePrice` BEFORE INSERT ON `PO_Lines_011` FOR EACH ROW BEGIN
	SET NEW.linePrice_011 = (SELECT partCurrentPrice_011 FROM Parts_011
	WHERE Parts_011.partNo_011 = NEW.partNo_011);
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AgentPassword_011`
--
ALTER TABLE `AgentPassword_011`
  ADD PRIMARY KEY (`agentUsername_011`);

--
-- Indexes for table `Agent_Credential_011`
--
ALTER TABLE `Agent_Credential_011`
  ADD PRIMARY KEY (`agentId_011`),
  ADD UNIQUE KEY `agentUsername` (`agentUsername_011`);

--
-- Indexes for table `Client_Credential_011`
--
ALTER TABLE `Client_Credential_011`
  ADD PRIMARY KEY (`clientCompId_011`),
  ADD UNIQUE KEY `clientUsername` (`clientUsername_011`);

--
-- Indexes for table `Client_User_011`
--
ALTER TABLE `Client_User_011`
  ADD PRIMARY KEY (`clientCompId_011`),
  ADD UNIQUE KEY `clientCompId_011_UNIQUE` (`clientCompId_011`);

--
-- Indexes for table `Parts_011`
--
ALTER TABLE `Parts_011`
  ADD PRIMARY KEY (`partNo_011`),
  ADD UNIQUE KEY `partNo_011_UNIQUE` (`partNo_011`);

--
-- Indexes for table `PO_011`
--
ALTER TABLE `PO_011`
  ADD PRIMARY KEY (`poNo_011`,`clientCompId_011`),
  ADD UNIQUE KEY `poNo_011_UNIQUE` (`poNo_011`),
  ADD KEY `clientCompNo_011_idx` (`clientCompId_011`);

--
-- Indexes for table `PO_Lines_011`
--
ALTER TABLE `PO_Lines_011`
  ADD PRIMARY KEY (`LineNO_011`,`poNo_011`),
  ADD UNIQUE KEY `LineNO_011_UNIQUE` (`LineNO_011`),
  ADD KEY `partNo_011_idx` (`partNo_011`),
  ADD KEY `poNo_011_idx` (`poNo_011`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Agent_Credential_011`
--
ALTER TABLE `Agent_Credential_011`
  MODIFY `agentId_011` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Client_User_011`
--
ALTER TABLE `Client_User_011`
  MODIFY `clientCompId_011` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100003;

--
-- AUTO_INCREMENT for table `Parts_011`
--
ALTER TABLE `Parts_011`
  MODIFY `partNo_011` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `PO_011`
--
ALTER TABLE `PO_011`
  MODIFY `poNo_011` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10158;

--
-- AUTO_INCREMENT for table `PO_Lines_011`
--
ALTER TABLE `PO_Lines_011`
  MODIFY `LineNO_011` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Client_Credential_011`
--
ALTER TABLE `Client_Credential_011`
  ADD CONSTRAINT `Client_Credential_011_ibfk_1` FOREIGN KEY (`clientCompId_011`) REFERENCES `Client_User_011` (`clientCompId_011`);

--
-- Constraints for table `PO_011`
--
ALTER TABLE `PO_011`
  ADD CONSTRAINT `clientCompNo_011` FOREIGN KEY (`clientCompId_011`) REFERENCES `Client_User_011` (`clientCompId_011`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `PO_Lines_011`
--
ALTER TABLE `PO_Lines_011`
  ADD CONSTRAINT `partNo_011` FOREIGN KEY (`partNo_011`) REFERENCES `Parts_011` (`partNo_011`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `poNo_011` FOREIGN KEY (`poNo_011`) REFERENCES `PO_011` (`poNo_011`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
