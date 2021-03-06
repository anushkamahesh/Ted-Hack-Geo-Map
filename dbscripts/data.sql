
--
-- Dumping data for table `courrier_service`
--

INSERT INTO `courrier_service` (`id`, `name`, `address`, `telephone`, `other_telephone`) VALUES
(1, 'DHL', 'DHL @ Bambalapitiya', '123456789', '123454321'),
(2, 'Fedex', 'Fedex  @ colombo', '987654321', '987656789');


--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`id`, `NIC`, `first_name`, `last_name`, `address`, `mobile_number`, `other_number`, `courrier_service_provide_id`) VALUES
(1, '12345', 'Cesar', 'Wilson', '4744 Woodland Terrace, Sacramento, CA 95814 ', '916-860-1451', '', NULL),
(2, '9900990', 'Anthony', 'Williams', '4754 Norman Street,Bell, CA 90201 ', '323-326-0948', '5388 9319 3081', 1),
(3, '99887766', 'Alvin', 'Henderson', '1709 Luke Lane,Duncan, OK 73533', '254-598-6341', '860-625-5202', 1),
(4, '77665544', 'Peter ', 'Ahmad', '9 Pennsylvania Avenue,Rochelle Park, NJ 07662 ', '732-371-2858', '732-371-2211', 1),
(5, '223333221', 'Ryan', 'Barboza', '1938 Hillview Drive San Francisco, CA 94103', '707-248-0381', '', 2),
(6, '1987123', 'Russell', 'Moore', '2169 Harrison Street,Oakland, CA 94612 ', '415-475-1009', '', NULL),
(7, '675557755', 'Rodney', 'Blackmon', '4170 Cityview Drive,Springfield, PA 19064 ', '610-543-4004', '', NULL);

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`id`, `source`, `destination`, `depature_time`, `arrival_time`, `days`, `driver_id`) VALUES
(1, 4, 7, '00:00:00', '00:00:00', '', 5),
(2, 3, 2, '00:00:00', '00:00:00', '', 2),
(3, 1, 7, '00:00:00', '00:00:00', '', 1),
(4, 1, 3, '00:00:00', '00:00:00', '', 2),
(5, 1, 5, '00:00:00', '00:00:00', '', 3),
(6, 2, 6, '00:00:00', '00:00:00', '', 5);

-- --------------------------------------------------------
--
-- Dumping data for table `way_point`
--

INSERT INTO `way_point` (`route_id`, `lat`, `log`, `order`) VALUES
(1,  6.886473178863525000000000000, 79.856285095214840000000000000,1),
(2,  6.897994041442871000000000000, 79.922286987304690000000000000,1),
(2,  6.053518295288086000000000000, 80.220977783203120000000000000,2),
(2,  6.864908218383789000000000000, 79.899681091308600000000000000,3),
(3,  6.852214813232422000000000000, 79.924865722656250000000000000,1),
(4,  6.838520526885986000000000000, 79.965431213378900000000000000,1),
(5,  5.954919815063477000000000000, 80.554954528808600000000000000,1),
(4,  6.244152069091797000000000000, 80.059082031250000000000000000,2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `driver_ibfk_1` FOREIGN KEY (`courrier_service_provide_id`) REFERENCES `courrier_service` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `route`
--
ALTER TABLE `route`
  ADD CONSTRAINT `route_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `way_point`
--
ALTER TABLE `way_point`
  ADD CONSTRAINT FOREIGN KEY (`route_id`) REFERENCES `route` (`id`) ON DELETE CASCADE;

