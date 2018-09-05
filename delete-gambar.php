<?php
session_start();
require_once("auth.php");
checkAuth();
if(isset($_GET['id_gambar'])){
  require "config/database.php";
  $id = $_GET['id_gambar'];
  $db->from('tbl_gambar_tempat')->where('id_gambar', $id)->delete()->execute();
  unlink("gambar/".$_GET['nm_gambar']);
  header('Location: index.php?delete=true');
}
else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>

