<?php
function checkAuth(){
  if(!isset($_SESSION['username']) && !isset($_SESSION['username']) && !isset($_SESSION['tipe_user'])){
    header("Location: index.php");
  }
}
?>
