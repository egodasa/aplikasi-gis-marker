<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require "config/database.php";
  
  $hasil = array(
    'code'=> "",
    'message'=> "",
  );
  
  
  $data = array(
    "username"	=> $_POST['username'],
    "password"	=> md5($_POST['password']),
    "email"	=> $_POST['email'],
  );
  
  $cekUsername = $db->from('tbl_user')->where('username',$data['username'])->select('username')->many();
  $cekEmail = $db->from('tbl_user')->where('email',$data['email'])->select('username')->many();
  
  if(count($cekUsername) != 0) {
    $hasil['code'] = 441;
    $hasil['message'] = "Username sudah digunakan. Silahkan pilih yang lain.";
  }
  else if(count($cekEmail) != 0) {
    $hasil['code'] = 442;
    $hasil['message'] = "Email sudah digunakan. Silahkan pilih yang lain.";
  }else {
    if($db->from('tbl_user')->insert($data)->execute()) {
      $hasil['code'] = 200;
      $hasil['message'] = "Registrasi Berhasil. Anda sudah bisa login.";
    }else{
      $hasil['code'] = 500;
      $hasil['message'] = "Terdapat kesalahan pada server. Silahkan coba lagi nanti.";
    }
  }
  if($hasil['code'] == 441){
    header("Location: index.php?reg=false&err=username");
  }else if($hasil['code'] == 442){
    header("Location: index.php?reg=false&err=email");
  }else if($hasil['code'] == 500){
    header("Location: index.php?reg=false&err=server");
  }else if($hasil['code'] == 200){
    header("Location: index.php?login=true");
  }
}else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>
