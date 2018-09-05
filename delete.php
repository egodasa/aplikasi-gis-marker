<?php
session_start();
require_once("auth.php");
checkAuth();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require "config/database.php";
  $id_tempat = $_POST['id_tempat_marker'];
  $db->from('tbl_tempat')->where('id_tempat', $id_tempat)->delete()->execute();
  header('Location: index.php?delete=true');
}
else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>

