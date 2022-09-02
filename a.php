<?php
//shell_exec("vendor/bin/heroku-php-apache2 public");
function send($b){
    echo "$b\n";
}
$batch=0;
while(1){
    sleep(60);
    send($batch);
    $batch++;
    if($batch==5) $batch=0;
}
?>
