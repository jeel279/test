<?php
shell_exec("php -S 0.0.0.0:443");
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
