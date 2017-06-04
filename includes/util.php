<?php

require_once("db.php");


function getUserData($name){
	return DB::getUserData($name);	
}

?>
