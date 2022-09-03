<?php
    require_once "dbconn.php";
    $b = (int)$_POST["batch"];
    $sql = "SELECT email,mail_sent FROM mailing_list WHERE onhold=0";



?>