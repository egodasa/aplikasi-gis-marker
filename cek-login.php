<?php
session_start();
if(isset($_SESSION['username']) && isset($_SESSION['username'])){
  echo json_encode(array('status' => true,'data' => array('username'=>$_SESSION['username'], 'id_user'=>$_SESSION['id_user'])));
}else{
  echo json_encode(array('status' => false));
}
?>      
