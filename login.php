<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require "config/database.php";
	$data = json_decode(file_get_contents('php://input'), true);
	$login = $db->from('tbl_user')->where(array('username' => $data['username'], 'password' => md5($data['password'])))->select()->one();
	$hasil = array(
    'status'=> false,
    'message'=> ""
  );
  if(empty($login)){
		$hasil['message'] = "Username atau Password salah!"; 
	}else {
		$hasil['status'] = true;
		$hasil['data'] = array(
        'username'  => $login['username'],
        'id_user'  => $login['id_user'],
        'tipe_user'  => $login['tipe_user']
    );
    $_SESSION['username'] = $login['username'];
    $_SESSION['id_user'] = $login['id_user'];
    $_SESSION['tipe_user'] = $login['tipe_user'];
	}
  echo json_encode($hasil);
}
?>      
