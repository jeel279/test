<?php
class db{
	private $db;
	function  __construct(){
		$this->$db = mysqli_connect("remotemysql.com","OdMx7Rr5zp","SzxjV183tX","OdMx7Rr5zp");

		if (mysqli_connect_errno()) { 
			$this->$db = false;
			//return "Failed to connect to MySQL: " . mysqli_connect_error();
			exit();
		}
	}
	function sanitize($a){
		return mysqli_real_escape_string($this->$db, $a);
	}
	function query($sql){
		$result = mysqli_query($this->$db, $sql) 
                or die(mysqli_error($this->$db));
		return $result;
	}
	function close(){
		mysqli_close($this->$db);
	}
}
?>
