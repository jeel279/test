<?php

function read_cb($ch, $fp, $length) {
    return fread($fp, $length);
}
class sendMail{
    private $vale,$ch,$queue=array();
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
    function curlSet($pref){
        $ch = curl_init();
        $username = "cron";
        $password = "stalemate";
        curl_setopt($ch, CURLOPT_URL, "https://randomxkcdcoms.herokuapp.com/cron/send.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch,CURLOPT_POSTFIELDS,array("email"=>"".$pref["email"]."","safe_title"=>$this->vale["safe_title"],"img"=>"".$this->vale["img"]."","url"=>"".$this->vale["url"]."","name"=>"".$pref["name"]."","un"=>"".$pref["un"].""));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $ch;
    }
    function enque($arr){
        $i=0;
        $this->ch = curl_init();
        while($i<sizeof($arr)){
            array_push($this->queue,$this->curlSet($arr[$i++]));
        }
    }
    function start(){
        $res = array();
        $k = 2;
        for($i=0;$i<sizeof($this->queue);$i+=$k){
            $j=$i;
            $mh = curl_multi_init();
            while($j<sizeof($this->queue) && $j<$i+$k) curl_multi_add_handle($mh, $this->queue[$j++]);
            do { curl_multi_exec($mh, $active); } while ($active);
            $j=$i;
            while($j<sizeof($this->queue) && $j<$i+$k) curl_multi_remove_handle($mh, $this->queue[$j++]);    
            curl_multi_close($mh); 
            $j=$i;
            while($j<sizeof($this->queue) && $j<$i+$k) array_push($res,curl_multi_getcontent($this->queue[$j++]));
        }
        return $res;
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