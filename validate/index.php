<?php
	/*if(isset($_POST["email"]) && isset($_POST["name"])){
		
	}*/
    session_start();
    if(isset($_SESSION["visited"])){
        echo json_encode("{'code':420,'detail':'Better Luck Next Time'}");
        exit();
    }
    include 'dbconn.php';
$userid = $_POST['email'];
$name = $_POST['name'];

$sanitized_userid = 
    mysqli_real_escape_string($db, $userid);

    $sanitized_name = 
    mysqli_real_escape_string($db, $name);

    
$email = $sanitized_userid;
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode("{'code':400,'detail':'Invalid Email'}");
  exit();
}

$sql = "SELECT * FROM mailing_list WHERE email = '" 
    . $sanitized_userid . "'";
      
$result = mysqli_query($db, $sql) 
    or die(mysqli_error($db));
      
$num = mysqli_fetch_array($result);

if($result->num_rows == 1) {
    echo json_encode("{'code':401,'detail':'User Already Exists'}");
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

// curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"personalizations\": [{\"to\": [{\"email\": \"jeel4402@gmail.com\"}]}],\"from\": {\"email\": \"noreply@attendworks.tech\"},\"subject\": \"Sending with SendGrid is Fun\",\"content\": [{\"type\": \"text/plain\", \"value\": \"and easy to do anywhere, even with cURL\"}],\"attachments\": [{\"content\": \"" . $b64 . "\", \"type\": \"image/jpeg\", \"filename\": \"attachment.jpeg\"}]}");
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"personalizations\": [{\"to\": [{\"email\": \"".$sanitized_userid."\"}]}],\"from\": {\"email\": \"noreply@attendworks.tech\",\"name\": \"Random XKCD Veriication\"},\"subject\": \"OTP\",\"content\": [{\"type\": \"text/plain\", \"value\": \"OTP is ". $_SESSION["OTP"] ."\"}]}");

$headers = array();
$headers[] = 'Authorization: Bearer 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';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode("{'code':501,'detail':'A Server error occurred.'}");
    exit();
}

$_SESSION["visited"] = true;
echo json_encode("{'code':200,'detail':'OTP has been sent'}");

}
  
?>