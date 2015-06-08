
--
-- Table structure for table `courrier_service`
--

CREATE TABLE IF NOT EXISTS `courrier_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `telephone` varchar(64) NOT NULL,
  `other_telephone` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE IF NOT EXISTS `driver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NIC` varchar(20) DEFAULT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `mobile_number` varchar(200) NOT NULL,
  `other_number` varchar(200) NOT NULL,
  `courrier_service_provide_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courrier_service_provide_id` (`courrier_service_provide_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

-- CREATE TABLE IF NOT EXISTS `location` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(600) NOT NULL,
--   `lat` float(30,27) NOT NULL,
--   `log` float(30,27) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE IF NOT EXISTS `route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` int(11) NOT NULL DEFAULT '0',
  `destination` int(11) NOT NULL DEFAULT '0',
  `depature_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `days` varchar(255) NOT NULL,
  `driver_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `route_ibfk_4` (`destination`),
  KEY `route_ibfk_2` (`driver_id`),
  KEY `route_ibfk_3` (`source`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `way_point`
--

CREATE TABLE IF NOT EXISTS `way_point` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL,
  `lat` float(30,27) NOT NULL,
  `log` float(30,27) NOT NULL,
  `order` int(11) NOT NULL
  PRIMARY KEY (`id`),
  FOREIGN KEY (`route_id`) REFERENCES `route`(`id`) ON DELETE CASCADE;
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
