DROP TABLE IF EXISTS `tbl_gambar_tempat`;
CREATE TABLE `tbl_gambar_tempat` (
  `id_gambar` int(11) NOT NULL AUTO_INCREMENT,
  `nm_gambar` varchar(100) NOT NULL,
  `id_tempat` int(11) NOT NULL,
  PRIMARY KEY (`id_gambar`),
  KEY `id_tempat` (`id_tempat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tbl_tempat`;
CREATE TABLE `tbl_tempat` (
  `id_tempat` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `koordinat_lat` double NOT NULL,
  `koordinat_lng` double NOT NULL,
  PRIMARY KEY (`id_tempat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_gambar_tempat` ADD CONSTRAINT `tbl_gambar_tempat_ibfk_1` FOREIGN KEY (`id_tempat`) REFERENCES `tbl_tempat` (`id_tempat`) ON DELETE CASCADE ON UPDATE CASCADE;
INSERT INTO `tbl_user` (`username`, `password`, `email`, `tipe_user`) VALUES ('superuser1@eventos.events',	'ce4e76790b3dd9f0b2880dac73bc8c0f',	'superuser1@eventos.events',	'Admin');
