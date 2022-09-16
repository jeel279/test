<?php

function read_cb($ch, $fp, $length) {
    return fread($fp, $length);
}

if(isset($_POST["OTP"])){
    $header = $_POST;
    $email = $header["Email"];
    $code = $header["code"];
    $name = $header["name"];
    $fp = fopen('php://memory', 'r+');
        $file_name = $email.".txt";
        $string = "From: \"Comics\" <no-reply@attendworks.tech>\r\n";
        $string .= "To: \"".$as."\"<".$email.">\r\n";
        $string .= "Date: " . date('r') . "\r\n";
        $string .= "Subject: OTP\r\n";
        $string .= "MIME-Version: 1.0\r\n";
        $string .= "Content-Type: multipart/alternative; boundary=\"MIXED\"\r\n";
        $string .= "\r\n";
        $string .= "--MIXED\r\n";
        $string .= "Content-Type: text/html; charset=utf-8\r\n";
        $string .= "Content-Transfer-Encoding: 8-bit\r\n";
        $string .= "\r\n";
        $string .= "Your OTP is <b>".$code."</b>\r\n";
        $string .= "\r\n";

        fwrite($fp, $string);
        rewind($fp);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,'smtp://us2.smtp.mailhostbox.com:587');
        curl_setopt($ch,CURLOPT_USERNAME,'no-reply@attendworks.tech');
        curl_setopt($ch,CURLOPT_PASSWORD,')ndq#DU4');
        curl_setopt($ch,CURLOPT_USE_SSL,CURLUSESSL_ALL);
        curl_setopt($ch,CURLOPT_READFUNCTION,'read_cb');
        curl_setopt($ch,CURLOPT_UPLOAD,true);
        curl_setopt_array($ch, [
            CURLOPT_MAIL_RCPT => ['<'.$email.'>'],
            CURLOPT_INFILE => $fp
        ]);


        $x = curl_exec($ch);
        
        if ($x === false) {
            return false;
        }
        return true;
        curl_close($ch);
        fclose($fp);
    exit();
}


        $header = $_POST;
        $email = $header["email"];
        $as = $header["name"];
        $un = $header["un"];
        $fp = fopen('php://memory', 'r+');
        $file_name = $email.".txt";
        $chunked_content = chunk_split($header["img"]);
        $string = "From: \"Comics\" <no-reply@attendworks.tech>\r\n";
        $string .= "To: \"".$as."\"<".$email.">\r\n";
        $string .= "Date: " . date('r') . "\r\n";
        $string .= "Subject: ".$header["safe_title"]." - XKCD\r\n";
        $string .= "MIME-Version: 1.0\r\n";
        $string .= "Content-Type: multipart/alternative; boundary=\"MIXED\"\r\n";
        $string .= "\r\n";
        $string .= "--MIXED\r\n";
        $string .= "Content-Type: text/html; charset=utf-8\r\n";
        $string .= "Content-Transfer-Encoding: 8-bit\r\n";
        $string .= "\r\n";
        $string .= "Hey ".$as.", here is new comic for you <br><br> <a href='https://randomxkcdcoms.herokuapp.com/unsubscribe?token=".$un."'>Unsubscribe</a> <br><br> <img src='".$header["url"]. "'>\r\n";
        $string .= "\r\n";
        $string .= "--MIXED\r\n";
        $string .= "Content-Type: application/octet-stream; name=\"".$header["safe_title"].".jpeg\"\r\n";
        $string .= "Content-Transfer-Encoding: base64\r\n";
        $string .= "Content-Disposition: attachment; filename=\"".$header["safe_title"].".jpeg\"\r\n";
        $string .= "\r\n";
        $string .= $chunked_content . "\r\n";
        $string .= "\r\n";

        fwrite($fp, $string);
        rewind($fp);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,'smtp://us2.smtp.mailhostbox.com:587');
        curl_setopt($ch,CURLOPT_USERNAME,'no-reply@attendworks.tech');
        curl_setopt($ch,CURLOPT_PASSWORD,')ndq#DU4');
        curl_setopt($ch,CURLOPT_USE_SSL,CURLUSESSL_ALL);
        curl_setopt($ch,CURLOPT_READFUNCTION,'read_cb');
        curl_setopt($ch,CURLOPT_UPLOAD,true);
        curl_setopt_array($ch, [
            CURLOPT_MAIL_RCPT => ['<'.$email.'>'],
            CURLOPT_INFILE => $fp
        ]);


        $x = curl_exec($ch);
        
        if ($x === false) {
            return false;
        }
        return true;
        curl_close($ch);
        fclose($fp);
?>