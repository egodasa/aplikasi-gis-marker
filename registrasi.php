<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require "config/database.php";
  
  $hasil = array(
    'status'=> "",
    'message'=> "",
  );
  $data = json_decode(file_get_contents('php://input'), true);
  $data['password'] = md5($data['password']);
  
  $cekUsername = $db->from('tbl_user')->where('username',$data['username'])->select('username')->many();
  $cekEmail = $db->from('tbl_user')->where('email',$data['email'])->select('username')->many();
  
  if(count($cekUsername) != 0) {
    $hasil['status'] = false;
    $hasil['message'] = "Username sudah digunakan. Silahkan pilih yang lain.";
  }
  else if(count($cekEmail) != 0) {
    $hasil['status'] = false;
    $hasil['message'] = "Email sudah digunakan. Silahkan pilih yang lain.";
  }else {
    if($db->from('tbl_user')->insert($data)->execute()) {
      $hasil['status'] = true;
      $hasil['message'] = "Registrasi Berhasil. Anda sudah bisa login.";
    }else{
      $hasil['status'] = false;
      $hasil['message'] = "Terdapat kesalahan pada server. Silahkan coba lagi nanti.";
    }
  }
  echo json_encode($hasil);
}else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>
