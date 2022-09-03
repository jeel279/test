<?php
session_start();
    if(isset($_SESSION["OTP"])){
        if(isset($_POST["OTP"])){
            if($_SESSION["OTP"]==$_POST["OTP"]){
                include '../validate/dbconn.php';
                $t=time();
                $tm = $t;
                $t = (int)($t/60) % 5;
                $name = $_SESSION["NAME"];
                $email = $_SESSION["EMAIL"];
                $sql = "INSERT INTO mailing_list (name,email,batch,created) VALUES ('$name','$email',$t,$tm)";
      
                $result = mysqli_query($db, $sql) 
                or die(mysqli_error($db));
                if($result){
                    echo "Subscribed Successfully";
                    exit();
                }else{
                    echo "ERROR";
                    exit();
                }
            }
        }
    }else{
        echo json_encode($_SESSION);
    }
?>
<form action='/verify' method='post'>
<input type='number' name='OTP'>
<input type='submit' value="verify">
</form>