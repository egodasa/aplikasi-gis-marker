<?php

//Load library database
require_once("db.php");

$username = "ijmyzlwbtnvjlp";
$password = "5a7b14c8dfcb7d5f9417ea0296d6c3d1a51ec5e26a087a08f0a43dbcebe757ce";
$server = "ec2-184-73-174-171.compute-1.amazonaws.com";
$database = "d775vshpuv9cs0";

// Instance database yaitu variabel $db
$db = new Sparrow();
$db->show_sql = true;
$db->setDb("pgsql://".$username.":".$password."@".$server."/".$database);
?>
