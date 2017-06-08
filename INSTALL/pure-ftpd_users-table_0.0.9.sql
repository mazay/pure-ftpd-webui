CREATE TABLE `ftpd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User` varchar(16) NOT NULL DEFAULT '',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `Password` varchar(64) NOT NULL DEFAULT '',
  `Uid` varchar(11) NOT NULL DEFAULT '2001',
  `Gid` varchar(11) NOT NULL DEFAULT '2001',
  `Dir` varchar(128) NOT NULL DEFAULT '/media/FTP',
  `ULBandwidth` int(5) NOT NULL DEFAULT '0',
  `DLBandwidth` int(5) NOT NULL DEFAULT '0',
  `comment` tinytext NOT NULL,
  `ipaccess` varchar(15) NOT NULL DEFAULT '*',
  `QuotaSize` bigint(20) NOT NULL DEFAULT '0',
  `QuotaFiles` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`User`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
