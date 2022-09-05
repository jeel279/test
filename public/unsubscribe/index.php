<?php
    require_once "../../db.php";
    include '../../keyset.conf';
    $db = new db();
    if(!isset($_GET["token"])) exit();
    $token = $db->sanitize($token);
    $db->query("DELETE FROM mailing_list WHERE identifier='".$token."'");
    if($res){
        echo "Unsubscribed";
    }else{
        header("Location : .");
    }

?>