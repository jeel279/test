<?php
    require_once "../../db.php";
    include '../../keyset.conf';
    $db = new db();
    echo hash('sha256',$_GET["token"]);
/*    if(!isset($_GET["token"])) exit();
    $email = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(base64_decode($_GET["token"]),'',$nonce,$key);
    $email = $db->sanitize($email);
    $db->query("DELETE FROM mailing_list WHERE email='".$email."'");
    if($res){
        echo "Unsubscribed";
    }else{
        header("Location : .");
    }*/

?>