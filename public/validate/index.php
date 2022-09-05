<?php
	/*if(isset($_POST["email"]) && isset($_POST["name"])){
		
	}*/

        
    session_start();

    require_once ('../../db.php');
    function errorE($a){
        $ms = array(1=>"Subscribed",2=>$_SESSION["expiry"] - time(),3=>"Wrong OTP. ". 5 - $_SESSION["TRY"]." attempts left",200=>"OTP Sent.",420=>"Better Luck Next Time",400=>"Invalid Email",401=>"User Already Exists",501=>"Server Error Occurred");
        $arr = array();
        $arr["code"] = $a;
        $arr["msg"] = $ms[$a];
        return json_encode($arr);
    }

    if(isset($_POST["verify"])){
        if($_SESSION["TRY"]>=5){
            exit();
        }
   
        if((int)$_POST["otp"]==(int)$_SESSION["OTP"]){
            $db = new db();
            $t=time();
                $tm = $t;
                $t = (int)($t/60) % 5;
                $name = $_SESSION["NAME"];
                $email = $_SESSION["EMAIL"];
                $hash = hash('sha256',$email.$m);
                $sql = "INSERT INTO mailing_list (name,email,batch,created,identifier) VALUES ('$name','$email',$t,$tm,'$hash')";
            $result = $db->query($sql);
            if($result)
                echo errorE(1);
            else
                echo errorE(501);
            exit();
        }
        $_SESSION["TRY"]++;
        echo errorE(3);
        exit();
    }

    if(!isset($_POST["submit"]) || isset($_POST["verify"])) exit();

    $db = new db();


    $userid = $_POST['email'];
    $name = $_POST['name'];

    $sanitized_userid = $db->sanitize($userid);
    $sanitized_name = $db->sanitize($name);

    $email = $sanitized_userid;


    if(isset($_SESSION["expiry"]) && $userid==$_SESSION["EMAIL"]){
        if($_SESSION["expiry"] >= time()){
           echo errorE(2);
           exit();
        }
    }

    
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo errorE(400);
  exit();
}

$sql = "SELECT * FROM mailing_list WHERE email = '" 
    . $email . "'";
      
$result = $db->query($sql);
      
$num = mysqli_fetch_array($result);

if($result->num_rows == 1) {
    echo errorE(401);
    exit();
}
else {

    $ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

$_SESSION["OTP"] = rand(100000,999999);
$_SESSION["EMAIL"] = $sanitized_userid;
$_SESSION["NAME"] = $sanitized_name;
$_SESSION["TRY"] = 0;

// curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"personalizations\": [{\"to\": [{\"email\": \"jeel4402@gmail.com\"}]}],\"from\": {\"email\": \"noreply@attendworks.tech\"},\"subject\": \"Sending with SendGrid is Fun\",\"content\": [{\"type\": \"text/plain\", \"value\": \"and easy to do anywhere, even with cURL\"}],\"attachments\": [{\"content\": \"" . $b64 . "\", \"type\": \"image/jpeg\", \"filename\": \"attachment.jpeg\"}]}");
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"personalizations\": [{\"to\": [{\"email\": \"".$sanitized_userid."\"}]}],\"from\": {\"email\": \"noreply@attendworks.tech\",\"name\": \"Random XKCD Veriication\"},\"subject\": \"OTP\",\"content\": [{\"type\": \"text/plain\", \"value\": \"OTP is ". $_SESSION["OTP"] ."\"}]}");

$headers = array();
$headers[] = 'Authorization: Bearer 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';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo errorE(501);
    exit();
}

$_SESSION["expiry"] = time() + 5;
$_SESSION["visited"] = true;
echo errorE(200);

}
  
?>