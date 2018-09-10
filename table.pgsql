DROP TABLE IF EXISTS tbl_gambar_tempat;
CREATE SEQUENCE tbl_gambar_tempat_seq;

CREATE TABLE tbl_gambar_tempat (
  id_gambar int NOT NULL DEFAULT NEXTVAL ('tbl_gambar_tempat_seq'),
  nm_gambar varchar(100) NOT NULL,
  id_tempat int NOT NULL,
  PRIMARY KEY (id_gambar)
) ;

CREATE INDEX id_tempat ON tbl_gambar_tempat (id_tempat);


DROP TABLE IF EXISTS tbl_tempat;
CREATE SEQUENCE tbl_tempat_seq;

CREATE TABLE tbl_tempat (
  id_tempat int NOT NULL DEFAULT NEXTVAL ('tbl_tempat_seq'),
  id_user int NOT NULL,
  judul varchar(100) NOT NULL,
  deskripsi text NOT NULL,
  kategori varchar(100) NOT NULL,
  koordinat_lat double precision NOT NULL,
  koordinat_lng double precision NOT NULL,
  PRIMARY KEY (id_tempat)
) ;

DROP TABLE IF EXISTS tbl_user;
CREATE SEQUENCE tbl_user_seq;

CREATE TABLE tbl_user (
  id_user int NOT NULL DEFAULT NEXTVAL ('tbl_user_seq'),
  username varchar(50) NOT NULL,
  password text NOT NULL,
  email varchar(50) NOT NULL,
  tipe_user varchar(50) NOT NULL,
  PRIMARY KEY (id_user)
) ;

ALTER TABLE tbl_gambar_tempat ADD CONSTRAINT tbl_gambar_tempat_ibfk_1 FOREIGN KEY (id_tempat) REFERENCES tbl_tempat (id_tempat) ON DELETE CASCADE ON UPDATE CASCADE;
INSERT INTO tbl_user (username, password, email, tipe_user) VALUES ('superuser1@eventos.events',	'ce4e76790b3dd9f0b2880dac73bc8c0f',	'superuser1@eventos.events',	'Admin');
INSERT INTO tbl_user (username, password, email, tipe_user) VALUES ('admin',	md5('qwe123*iop'),	'admin@admin.com',	'Admin');
INSERT INTO tbl_tempat (id_user, judul, deskripsi, kategori, koordinat_lat, koordinat_lng) VALUES 
(1,'Banda Aceh','Ini adalah nama Kota','Kota',5.54829,95.323753),       
(1,'Ambon','Ini adalah nama Kota','Kota',-3.654703,128.190643),
(1,'Palopo','Ini adalah nama Kota','Kota',-2.994494,120.195465),
(1,'Cabuyao','Ini adalah nama Kota','Kota',14.247142,121.136673),
(1,'Soreang','Ini adalah nama Kota','Kota',-7.025253,107.51976),
(1,'Dumai','Ini adalah nama Kota','Kota',1.694394,101.445007),
(1,'Pematang Siantar City','Ini adalah nama Kota','Kota',2.970042,99.068169),
(1,'Banjarsari','Ini adalah nama Kota','Kota',-7.550676,110.828316),
(1,'Lhoksukon','Ini adalah nama Kota','Kota',5.051701,97.318123),
(1,'Trenggalek','Ini adalah nama Kota','Kota',-8.08641,111.713127),
(1,'Ponorogo','Ini adalah nama Kota','Kota',-7.866688,111.466614),
(1,'Stabat','Ini adalah nama Kota','Kota',3.750531,98.470528),
(1,'Pangkal Pinang','Ini adalah nama Kota','Kota',-2.133333,106.116669),
(1,'Makassar City','Ini adalah nama Kota','Kota',-5.135399,119.42379),
(1,'Pamulang','Ini adalah nama Kota','Kota',-6.347891,106.741158),
(1,'Jepara City','Ini adalah nama Kota','Kota',-6.574958,110.670525),
(1,'Manado City','Ini adalah nama Kota','Kota',1.47483,124.842079),
(1,'Tegal City','Ini adalah nama Kota','Kota',-6.879704,109.125595),
(1,'Cibinong','Ini adalah nama Kota','Kota',-6.497641,106.828224),
(1,'Kendari City','Ini adalah nama Kota','Kota',-3.972201,122.5149),
(1,'Tangerang','Ini adalah nama Kota','Kota',-6.178306,106.631889),
(1,'Serpong','Ini adalah nama Kota','Kota',-6.320138,106.665596),
(1,'Semarang','Ini adalah nama Kota','Kota',-6.966667,110.416664),
(1,'Amuntai','Ini adalah nama Kota','Kota',-2.423779,115.250832),
(1,'Batam','Ini adalah nama Kota','Kota',1.045626,104.030457),
(1,'Banjarmasin','Ini adalah nama Kota','Kota',-3.316694,114.590111),
(1,'Jambi City','Ini adalah nama Kota','Kota',-1.609972,103.607254),
(1,'Serang','Ini adalah nama Kota','Kota',-6.12,106.150276),
(1,'Sampit','Ini adalah nama Kota','Kota',-2.539465,112.958687),
(1,'Kupang City','Ini adalah nama Kota','Kota',-10.178757,123.597603),
(1,'Banjar','Ini adalah nama Kota','Kota',-7.374585,108.558189),
(1,'Samarinda','Ini adalah nama Kota','Kota',-0.502106,117.153709),
(1,'Subang','Ini adalah nama Kota','Kota',-6.571589,107.758736),
(1,'Jember','Ini adalah nama Kota','Kota',-8.184486,113.668076),
(1,'Rancasari','Ini adalah nama Kota','Kota',-6.953946,107.677765),
(1,'Cileunyi','Ini adalah nama Kota','Kota',-6.939008,107.740753),
(1,'Bogor','Ini adalah nama Kota','Kota',-6.595038,106.816635),
(1,'Yogyakarta','Ini adalah nama Kota','Kota',-7.797068,110.370529),
(1,'Makasar','Ini adalah nama Kota','Kota',-6.271194,106.894547),
(1,'Cirebon','Ini adalah nama Kota','Kota',-6.737246,108.550659),
(1,'Palembang','Ini adalah nama Kota','Kota',-2.990934,104.756554),
(1,'Medan','Ini adalah nama Kota','Kota',3.597031,98.678513),
(1,'Kediri','Ini adalah nama Kota','Kota',-7.82284,112.011864),
(1,'Bekasi','Ini adalah nama Kota','Kota',-6.241586,106.992416),
(1,'Surabaya','Ini adalah nama Kota','Kota',-7.250445,112.768845),
(1,'Kanigoro','Ini adalah nama Kota','Kota',-8.121262,112.205429),
(1,'Sidoarjo Regency','Ini adalah kabupaten','Kabupaten',-7.447661,112.698265),
(1,'Jagakarsa','Ini adalah kabupaten','Kabupaten',-6.330212,106.826378),
(1,'Rambatan Wetan','Ini adalah kabupaten','Kabupaten',-6.354013,108.292618),
(1,'Semambung','Ini adalah kabupaten','Kabupaten',-7.377019,112.739639),
(1,'Tabanan Regency','Ini adalah kabupaten','Kabupaten',-8.459556,115.0466),
(1,'Mangunjaya','Ini adalah kabupaten','Kabupaten',-6.237689,107.056816),
(1,'Sidoarjo Regency','Ini adalah kabupaten','Kabupaten',-7.472613,112.667542),
(1,'Sungai Kunjang','Ini adalah kabupaten','Kabupaten',-0.514462,117.09211),
(1,'Tibubeneng','Ini adalah kabupaten','Kabupaten',-8.64699,115.150375),
(1,'Koja','Ini adalah kabupaten','Kabupaten',-6.117664,106.906349),
(1,'Cengkareng','Ini adalah kabupaten','Kabupaten',-6.148665,106.73526),
(1,'Setiabudi','Ini adalah kabupaten','Kabupaten',-6.213519,106.822266),
(1,'Central Klaten','Ini adalah kabupaten','Kabupaten',-7.703403,110.600502),
(1,'Bantaeng Regency','Ini adalah kabupaten','Kabupaten',-5.516932,120.020294),
(1,'West Jakarta City','Ini adalah kabupaten','Kabupaten',-6.168329,106.75885),
(1,'Pendopo','Ini adalah kabupaten','Kabupaten',-3.797575,103.006699),
(1,'Keudah','Ini adalah kabupaten','Kabupaten',5.561168,95.315155),
(1,'Lapau Mak Uniang','Ini adalah kabupaten','Kabupaten',-0.960765,100.395981),
(1,'South Kuta','Ini adalah kabupaten','Kabupaten',-8.785502,115.199806),
(1,'Batu Licin','Ini adalah kabupaten','Kabupaten',-3.025327,116.000519),
(1,'Sagulung','Ini adalah kabupaten','Kabupaten',1.036698,103.949203),
(1,'Sipahutar District','Ini adalah kabupaten','Kabupaten',2.105982,99.085609),
(1,'Cileunyi','Ini adalah kabupaten','Kabupaten',-6.931659,107.741974),
(1,'Lengkong','Ini adalah kabupaten','Kabupaten',-6.932694,107.627449),
(1,'Lubuklinggau','Ini adalah kabupaten','Kabupaten',-3.2811,102.910988),
(1,'Apartemen Sky View','Ini adalah kabupaten','Kabupaten',-6.29461,106.682693),
(1,'Perum Pulo Mas Residence','Ini adalah kabupaten','Kabupaten',1.109786,104.036003),
(1,'Gambir','Ini adalah kabupaten','Kabupaten',-6.17311,106.829361),
(1,'Tipar','Ini adalah kabupaten','Kabupaten',-6.105821,106.881226),
(1,'Cempaka Putih','Ini adalah kabupaten','Kabupaten',-6.174355,106.876404),
(1,'Jalan Samudra','Ini adalah kabupaten','Kabupaten',-3.80859,102.264435),
(1,'Cipayung','Ini adalah kabupaten','Kabupaten',-6.427918,106.80014),
(1,'Bontoala','Ini adalah kabupaten','Kabupaten',-5.128928,119.424461),
(1,'Peranap','Ini adalah kabupaten','Kabupaten',-0.773957,102.026138),
(1,'Pancoran','Ini adalah kabupaten','Kabupaten',-6.2523,106.847336),
(1,'Cakung','Ini adalah kabupaten','Kabupaten',-6.182198,106.944695),
(1,'Pesawaran Regency','Ini adalah kabupaten','Kabupaten',-5.493245,105.079124),
(1,'Sukaraja','Ini adalah kabupaten','Kabupaten',-6.610811,106.848862),
(1,'Ciracas','Ini adalah kabupaten','Kabupaten',-6.323116,106.870941),
(1,'Pemalang Regency','Ini adalah kabupaten','Kabupaten',-6.929645,109.366425),
(1,'Banguntapan','Ini adalah kabupaten','Kabupaten',-7.840243,110.408333),
(1,'Bungo','Ini adalah kabupaten','Kabupaten',-1.640134,101.889175),
(1,'Jatinegara','Ini adalah kabupaten','Kabupaten',-6.230702,106.882744),
(1,'Pamulang','Ini adalah kabupaten','Kabupaten',-6.347891,106.741158),
(1,'Cikampek','Ini adalah kabupaten','Kabupaten',-6.400078,107.443756),
(1,'Gresik','Ini adalah kabupaten','Kabupaten',-7.156576,112.655472),
(1,'Tanjung Priok','Ini adalah kabupaten','Kabupaten',-6.132055,106.871483),
(1,'Blitar','Ini adalah kabupaten','Kabupaten',-8.1,112.150002),
(1,'Sawah Besar','Ini adalah kabupaten','Kabupaten',-6.153194,106.832588),
(1,'East Lombok','Ini adalah kabupaten','Kabupaten',-8.50497,116.525116);

