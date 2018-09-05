<?php
session_start();
require_once("auth.php");
checkAuth();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require "config/database.php";
  $data = array(
    "judul"	=> $_POST['judul'],
    "kategori"	=> $_POST['kategori'],
    "deskripsi"		=> $_POST['deskripsi']
  );
  $db->from('tbl_tempat')->where("id_tempat", $_POST['id_tempat'])->update($data)->execute();
  header("Location: index.php?edit=true");
}else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>
