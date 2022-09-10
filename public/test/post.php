<?php
function read_cb($ch, $fp, $length) {
    return fread($fp, $length);
}
$email = $_POST["email"];
$fp = fopen('php://memory', 'r+');
$file_name = "a.txt";
$chunked_content = chunk_split(base64_encode(file_get_contents("a.jpeg")));
$string = "From: \"Comics\" <no-reply@attendworks.tech>\r\n";
$string .= "To: \"Person \"<".$email.">\r\n";
$string .= "Date: " . date('r') . "\r\n";
$string .= "Subject: Test\r\n";
$string .= "MIME-Version: 1.0\r\n";
$string .= "Content-Type: multipart/mixed; boundary=\"MIXED\"\r\n";
$string .= "\r\n";
$string .= "--MIXED\r\n";
$string .= "Content-Type: text/plain; charset=utf-8\r\n";
$string .= "Content-Transfer-Encoding: 7bit\r\n";
$string .= "\r\n";
$string .= "Hello there!\r\n";
$string .= "\r\n";
$string .= "--MIXED\r\n";
$string .= "Content-Type: application/octet-stream; name=\"k.jpeg\"\r\n";
$string .= "Content-Transfer-Encoding: base64\r\n";
$string .= "Content-Disposition: attachment; filename=\"k.jpeg\"\r\n";
$string .= "\r\n";
$string .= $chunked_content . "\r\n";
$string .= "\r\n";


fwrite($fp, $string);
rewind($fp);


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
    echo curl_error($ch);
    exit();
}

curl_close($ch);
fclose($fp);

echo "sent to $email";
exit();
?>