<?php

//Load library database
require_once("db.php");

// Instance database yaitu variabel $db
$db = new Sparrow();
$db->show_sql = true;
$db->setDb(getenv("DATABASE_URL"));
?>
