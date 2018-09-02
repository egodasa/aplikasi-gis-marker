<?php

//Load library database
require_once("db.php");

$username = "root";
$password = "";
$server = "localhost";
$database = "events";

// Instance database yaitu variabel $db
$db = new Sparrow();
$db->show_sql = true;
$db->setDb("mysqli://".$username.":".$password."@".$server."/".$database);
?>
