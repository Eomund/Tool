<?php 
require_once('util.php');
if(!isset($_SESSION)){
	session_start();
}

$data = json_decode (file_get_contents ("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=". $_POST["idtoken"]));

if($data->aud == "195292382510-vs6021ptvdkk6gt2k9a9immrcqarji2h.apps.googleusercontent.com"){
	$data = getUserData($data->email);
	if(!$data){
		//Unathorized
		header("HTTP/1.1 401 Unauthorized");
		die();
	}else{
		$_SESSION['user'] = $data;	
		echo $_SESSION['user']->name;
	}
}


?>
