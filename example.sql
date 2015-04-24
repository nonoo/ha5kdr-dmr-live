CREATE TABLE IF NOT EXISTS `dmr-live` (
  `date` datetime NOT NULL,
  `callsign` varchar(8) NOT NULL,
  `repeater` varchar(8) NOT NULL,
  `repeaterid` int(11) NOT NULL,
  `callsignid` int(11) NOT NULL,
  `timeslot` tinyint(4) NOT NULL,
  `group` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `dtmf` int(6) NOT NULL,
  `city` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  PRIMARY KEY (`date`,`callsignid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
