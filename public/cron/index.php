<?php
    require_once "../../db.php";
    require_once "send.php";
    $db = new db();
    $b = (int)(time()/60) % 5;
    $sql = "SELECT email,name,mail_sent FROM mailing_list WHERE on_hold=0 AND batch=".(int)$b."";
    
    //$result = $db->query($sql);
    if ($res = $db->query($sql)) {
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            sendmail($row["email"],$row["name"],$row["mail_sent"]);
        }
        mysqli_free_result($res);
    }
    else {
        echo "No matching records are found.";
    }
}
else {
    echo "ERROR: Could not able to execute $sql. "
                                .mysqli_error($link);
}
  /*  if(mysqli_num_rows($result)>0){

        $filename = "newfile.txt";
   $file = fopen( $filename, "w" );
   
   if( $file == false ) {
      echo ( "Error in opening new file" );
      exit();
   }
   $str = "";

   while($row = mysqli_fetch_array($result)) {
    $str = $str . " " .json_encode($row);
  }

   fwrite( $file, $str);
   fclose( $file );
}*/


?>