<?php
function sendActivation($email){

}
function send($email,$no){

$ch = curl_init('https://c.xkcd.com/random/comic/');
 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 1);

$response = curl_exec($ch);

curl_close($ch);

$k = explode("\r\nlocation: ",$response);
$k = explode("\r\n",$k[1]);
$k = $k[0];
$k=str_replace("http","https",$k);
echo $k;
$k = $k . "info.0.json";
$ch = curl_init($k);
 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
 


curl_close($ch);
$d = json_decode($response,true);


$b64 = base64_encode(file_get_contents($d["img"]));

$data = array();
$body = array();
$data["personalizations"][0] = array("to"=>array(array("email"=>"jeel4403@gmail.com")));
$data["from"] = array("email"=>"noreply@attendworks.tech","name"=>"Random XKCD");
$data["subject"] = "Sample Mail";
$data["content"][0] = array("type"=>"text/plain","value"=>"SAMPLE A");
$data["attachments"][0] = array("content"=>"$b64","type"=>"image/jpeg","filename" => "attachment.jpeg");
// array_push($data,$body);
// var_dump($data);

echo json_encode($data);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"personalizations\": [{\"to\": [{\"email\": \"jeel4402@gmail.com\"}]}],\"from\": {\"email\": \"noreply@attendworks.tech\"},\"subject\": \"Sending with SendGrid is Fun\",\"content\": [{\"type\": \"text/plain\", \"value\": \"and easy to do anywhere, even with cURL\"}],\"attachments\": [{\"content\": \"" . $b64 . "\", \"type\": \"image/jpeg\", \"filename\": \"attachment.jpeg\"}]}");

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
echo "Sent";
}
?>