<?php
require_once "../../db.php";
$db = new db();
if (!isset($_GET["token"])) {
    exit();
}
$token = $db->sanitize($_GET["token"]);
$resA = $db->query(
    "SELECT * FROM mailing_list WHERE identifier='" . $token . "'"
);
if ((int) $resA->num_rows > 0) {
    $res = $db->query(
        "DELETE FROM mailing_list WHERE identifier='" . $token . "'"
    );
    if ($res) {
        echo "Unsubscribed";
    }
} else {
    echo "Bad Token : $token";
}

?>
