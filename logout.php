<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $data = json_decode(file_get_contents('php://input'), true);
  if(isset($_SESSION['username']) && isset($_SESSION['username'])){
    if($_SESSION['username'] == $data['username'] && $_SESSION['id_user'] == $data['id_user']){
      unset($_SESSION['username']);
      unset($_SESSION['id_user']);
      echo json_encode(array('status'=>true));
    }
  }else{
    echo json_encode(array('status'=>false));
  }
}
?>      
