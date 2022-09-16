<?php
require_once "../../db.php";
require_once "conf.php";
$db = new db();
$b = (int)(time() / 60) % 5;
$sql = "SELECT email,name,mail_sent,identifier FROM mailing_list WHERE on_hold=0 AND batch=" . (int)$b . "";

$arr = array();
$i = 0;

$mail = new sendMail();
$mail->gen();
if ($res = $db->query($sql))
{
    if (mysqli_num_rows($res) > 0)
    {
        while ($row = mysqli_fetch_array($res))
        {
            array_push($arr, array(
                "email" => $row["email"],
                "name" => $row["name"],
                "un" => $row["identifier"]
            ));
        }
        $mail->enque($arr);
        $mail->start();
        mysqli_free_result($res);
    }
    else
    {
        echo "No matching records are found.";
        exit();
    }
}
else
{
    echo "ERROR: Could not able to execute $sql. ";
    exit();
}
$resA = $db->query("UPDATE mailing_list SET mail_sent=mail_sent+1 WHERE batch=" . $b . "");
if ($resA)
{
    echo $b;
    exit();
}
echo "UPDATE FAILED";

?>
