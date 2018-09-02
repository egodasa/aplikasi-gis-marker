<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['tipe_user']);
unset($_SESSION['id_user']);
header('Location: index.php');
?>      
