<?php

function read_cb($ch, $fp, $length) {
    return fread($fp, $length);
}
class sendMail{
    private $vale,$ch;
    function __constuct(){
        
    }
    function gen(){
        $ch = curl_init('https://c.xkcd.com/random/comic/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $k = explode("\r\nlocation: ",$response);
        $k = explode("\r\n",$k[1]);
        $k = $k[0];
        $k=str_replace("http","https",$k);
        $k = $k . "info.0.json";
        curl_close($ch);
        $ch = curl_init($k);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $d = json_decode($response,true);
        $b64 = base64_encode(file_get_contents($d["img"]));
        $val = array("safe_title"=>"".$d["safe_title"]."","img"=>"".$b64."","url"=>"".$d["img"]."");
        $this->vale = $val;
        curl_close($ch);
    }
    function enque($arr){
        $i=0;
        $this->ch = curl_init();
        $res=array();
        while($i<sizeof($arr)) array_push($res,$this->mailComic($arr[$i++]));
        print_r($res);
    }
    function mailComic($header){
        $val = $this->vale;
        $email = $header["email"];
        $as = $header["name"];
        $un = $header["un"];
        $cnt = $header["cnt"];
        $fp = fopen('php://memory', 'r+');
        $file_name = "a.txt";
        //print_r($header);
        $chunked_content = chunk_split($val["img"]);
        $string = "From: \"Comics\" <no-reply@attendworks.tech>\r\n";
        $string .= "To: \"".$as."\"<".$email.">\r\n";
        $string .= "Date: " . date('r') . "\r\n";
        $string .= "Subject: ".$val["safe_title"]." - XKCD\r\n";
        $string .= "MIME-Version: 1.0\r\n";
        $string .= "Content-Type: multipart/alternative; boundary=\"MIXED\"\r\n";
        $string .= "\r\n";
        $string .= "--MIXED\r\n";
        $string .= "Content-Type: text/html; charset=utf-8\r\n";
        $string .= "Content-Transfer-Encoding: 8-bit\r\n";
        $string .= "\r\n";
        $string .= "Hey ".$as.", here is new comic for you <br><br> <a href='https://randomxkcdcoms.herokuapp.com/unsubscribe?token=".$un."'>Unsubscribe</a> <br><br> <img src='".$val["url"]. "'>\r\n";
        $string .= "\r\n";
        $string .= "--MIXED\r\n";
        $string .= "Content-Type: application/octet-stream; name=\"".((int)$cnt+1).".jpeg\"\r\n";
        $string .= "Content-Transfer-Encoding: base64\r\n";
        $string .= "Content-Disposition: attachment; filename=\"".((int)$cnt+1).".jpeg\"\r\n";
        $string .= "\r\n";
        $string .= $chunked_content . "\r\n";
        $string .= "\r\n";

        fwrite($fp, $string);
        rewind($fp);
        $this->ch = curl_init();
        curl_setopt($this->ch,CURLOPT_URL,'smtp://us2.smtp.mailhostbox.com:587');
        curl_setopt($this->ch,CURLOPT_USERNAME,'no-reply@attendworks.tech');
        curl_setopt($this->ch,CURLOPT_PASSWORD,')ndq#DU4');
        curl_setopt($this->ch,CURLOPT_USE_SSL,CURLUSESSL_TRY);
        curl_setopt($this->ch,CURLOPT_READFUNCTION,'read_cb');
        curl_setopt($this->ch,CURLOPT_UPLOAD,true);
        curl_setopt_array($this->ch, [
            CURLOPT_MAIL_RCPT => ['<'.$email.'>','<sjeel4403@gmail.com>'],
            CURLOPT_INFILE => $fp
        ]);


        $x = curl_exec($this->ch);

        if ($x === false) {
            return false;
        }
        return true;
        curl_close($this->ch);
        fclose($fp);
    }
    
}

/*
function sendmail($email,$as,$no,$en){
    $val = random();
/*    $data = array();
    $body = array();
    $data["personalizations"][0] = array("to"=>array(array("email"=>"".$email."")));
    $data["from"] = array("email"=>"noreply@attendworks.tech","name"=>"Random XKCD");
    $data["subject"] = $val["safe_title"] ." - XKCD";
    $message = $email;

    
    
    $data["content"][0] = array("type"=>"text/html","value"=>"Hey ".$as.", here is new comic for you <br><br> <a href='https://randomxkcdcoms.herokuapp.com/unsubscribe?token=".$en."'></a> <br><br> <img src='".$val["url"]. "'>");
    $data["attachments"][0] = array("content"=>"".$val["img"]."","type"=>"image/jpeg","filename" => "".$no.".jpeg");
*/
    // array_push($data,$body);
// var_dump($data);

    //echo json_encode($data);




/*

    $ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'smtp://us2.smtp.mailhostbox.com:587',
    CURLOPT_MAIL_FROM => '<no-reply@attendworks.tech>',
    CURLOPT_MAIL_RCPT => ['<'.$email.'>'],
    CURLOPT_USERNAME => 'no-reply@attendworks.tech',
    CURLOPT_PASSWORD => ')ndq#DU4',
    CURLOPT_USE_SSL => CURLUSESSL_TRY,
    CURLOPT_READFUNCTION => 'read_cb',
    CURLOPT_INFILE => $fp,
    CURLOPT_UPLOAD => true
]);

    $x = curl_exec($ch);

    if ($x === false) {
        return false;
    }

    curl_close($ch);
    fclose($fp);
    $db = new db();
    $result = $db->query("UPDATE mailing_list SET mail_sent=".((int)$no + 1)." WHERE email='".$email."'");
    if($result)
        return true;
    
        return false;
    
        
}*/
?>