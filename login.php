<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require "config/database.php";
	$data = $_POST;
	$hasil = $db->from('tbl_user')->where(array('username' => $data['username'], 'password' => md5($data['password'])))->select()->one();
	if(empty($hasil)){
		header('Location: index.php?login=false');
	}else {
		$_SESSION['username'] = $hasil['username'];
		$_SESSION['id_user'] = $hasil['id_user'];
	  header('Location: index.php');
	}
}
?>      
