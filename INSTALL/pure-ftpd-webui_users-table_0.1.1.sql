CREATE TABLE `userlist` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) COLLATE utf8_bin NOT NULL,
  `pass` varchar(50) COLLATE utf8_bin NOT NULL,
  `language` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;