<?php
session_start();
require_once("auth.php");
checkAuth();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $banyak_gambar = count($_FILES['gambar']['name']) - 1;
  
  if($banyak_gambar > 4){
    header("Location: index.php?file=banyak");
  }else{
    foreach($_FILES['gambar']['name'] as $key=>$f){
      if($_FILES['gambar']['size'][$key] > 300000){
        $_SESSION['judul'] = $_POST['judul'];
        $_SESSION['kategori'] = $_POST['kategori'];
        $_SESSION['deskripsi'] = $_POST['deskripsi'];
        $_SESSION['koordinat_lat'] = $_POST['koordinat_lat'];
        $_SESSION['koordinat_lng'] = $_POST['koordinat_lng'];
        header("Location: index.php?file=besar");
      }
    }
    require "config/database.php";
    $data = array(
      "judul"	=> $_POST['judul'],
      "id_user"	=> $_SESSION['id_user'],
      "kategori"	=> $_POST['kategori'],
      "deskripsi"		=> $_POST['deskripsi'],
      "koordinat_lat"		=> $_POST['koordinat_lat'],
      "koordinat_lng"		=> $_POST['koordinat_lng']
    );
    $db->from('tbl_tempat')->insert($data)->execute();
    
    $id = $db->insert_id;
    $nomor = $db->sql("SELECT COUNT(a.id_tempat) AS jumlah FROM tbl_gambar_tempat a JOIN tbl_tempat b on a.id_tempat = b.id_tempat WHERE b.id_user = ".$_SESSION['id_user'])->one()['jumlah'];
  
    $nama_file = $_SESSION['id_user']."_";
    
    foreach($_FILES['gambar']['name'] as $key=>$f){
      if($f != 0 || empty($f) == false){
        $path = "gambar/";
        $path = $path.basename($nama_file.($nomor+1).".jpg");
        move_uploaded_file($_FILES['gambar']['tmp_name'][$key], $path);
        $db->from('tbl_gambar_tempat')->insert(array("id_tempat" => $id, "nm_gambar"	=> $nama_file.($nomor+1).".jpg"))->execute();
        $nomor++;
      }
      if($key == $banyak_gambar){
        unset($_SESSION['judul']);
        unset($_SESSION['kategori']);
        unset($_SESSION['deskripsi']);
        unset($_SESSION['koordinat']);
        header("Location: index.php?file=true");
      }
    }
    
  }
}else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>
