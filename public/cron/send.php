<?php
function sendActivation($email){

}

function random(){
    $ch = curl_init('https://c.xkcd.com/random/comic/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    $k = explode("\r\nlocation: ",$response);
    $k = explode("\r\n",$k[1]);
    $k = $k[0];
    $k=str_replace("http","https",$k);
    $k = $k . "info.0.json";
    $ch = curl_init($k);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $d = json_decode($response,true);
    $b64 = base64_encode(file_get_contents($d["img"]));
    $val = array("safe_title"=>"".$d["safe_title"]."","img"=>"".$b64."","url"=>"".$d["img"]."");
    return $val;
}

include '../../keyset.conf';

function sendmail($email,$as,$no){
    $val = random();
    $data = array();
    $body = array();
    $data["personalizations"][0] = array("to"=>array(array("email"=>"".$email."")));
    $data["from"] = array("email"=>"noreply@attendworks.tech","name"=>"Random XKCD");
    $data["subject"] = $val["safe_title"] ." - XKCD";
    $message = $email;
    $encrypted_text = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($message, '', $nonce, $key);
    
    $data["content"][0] = array("type"=>"text/html","value"=>"Hey ".$as.", here is new comic for you <br><br> <a href='https://randomxkcdcoms.herokuapp.com/unsubscribe?token=".base64_encode($encrypted_text)."'></a> <br><br> <img src='".$val["url"]. "'>");
    $data["attachments"][0] = array("content"=>"".$val["img"]."","type"=>"image/jpeg","filename" => "".$no.".jpeg");
// array_push($data,$body);
// var_dump($data);

    //echo json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"personalizations\": [{\"to\": [{\"email\": \"jeel4402@gmail.com\"}]}],\"from\": {\"email\": \"noreply@attendworks.tech\"},\"subject\": \"Sending with SendGrid is Fun\",\"content\": [{\"type\": \"text/plain\", \"value\": \"and easy to do anywhere, even with cURL\"}],\"attachments\": [{\"content\": \"" . $val["img"] . "\", \"type\": \"image/jpeg\", \"filename\": \"attachment.jpeg\"}]}");
    //echo json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers = array();
    $headers[] = 'Authorization: Bearer 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';
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        exit();
    }
    $db = new db();
    $result = $db->query("UPDATE mailing_list SET mail_sent=".((int)$no + 1)." WHERE email='".$email."'");
    if($result)
        echo "Sent";
    else
        echo "mail sent but count error";
        
}
?>