<?php
session_start();
require_once("config/database.php");
  $cari = "";
    if(isset($_GET['filter']) && isset($_GET['cari'])){
      switch($_GET['filter']){
        case "judul":
          $cari = " AND a.judul LIKE '%".$_GET['cari']."%'";
          break;
        case "deskripsi":
          $cari = " AND a.deskripsi LIKE '%".$_GET['cari']."%'";
          break;
        case "kategori":
          $cari = " AND a.kategori LIKE '%".$_GET['cari']."%'";
          break;
        case "username":
          $cari = " AND b.username LIKE '%".$_GET['cari']."%'";
          break;
      }
    }
  //$sql = "SELECT a.*,b.username, (((acos(sin((".$latitude."*pi()/180))*sin((a.koordinat_lat*pi()/180))+cos((".$latitude."*pi()/180))*cos((a.koordinat_lat*pi()/180)) * cos(((".$longitude."- a.koordinat_lng)*pi()/180))))*180/pi())*60*1.1515*1.609344) as jarak from tbl_tempat a join tbl_user b on a.id_user = b.id_user WHERE (((acos(sin((".$latitude."*pi()/180))*sin((a.koordinat_lat*pi()/180))+cos((".$latitude."*pi()/180))*cos((a.koordinat_lat*pi()/180)) * cos(((".$longitude."- a.koordinat_lng)*pi()/180))))*180/pi())*60*1.1515*1.609344) <= ".$radius_lingkaran.$id_user.$cari;
  $sql = "SELECT a.*,b.username from tbl_tempat a join tbl_user b on a.id_user = b.id_user WHERE 1".$cari;
  $hasil = $db->sql($sql)->many();
  $banyak = count($hasil); 
  for($x = 0; $x < $banyak; $x++){
    $hasil[$x]['gambar'] = $db->from('tbl_gambar_tempat')->where('id_tempat', $hasil[$x]['id_tempat'])->select("id_gambar, nm_gambar")->many();
  }
  echo json_encode($hasil);
?>
