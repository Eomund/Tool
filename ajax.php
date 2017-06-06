<?php
require_once('includes/db.php');

if(!isset($_SESSION)){
	session_start();
}

if(!isset($_SESSION['user'])){
	die();
}
switch($_GET['action']){
	case 'cards':
		$data = DB::getCards();
		echo json_encode($data);
		break;
	case 'card':
		$data = DB::getCard($_GET['id']);
		echo json_encode($data);
		break;
	case 'places':
		$data = DB::getPlaces();
		echo json_encode($data);
		break;
	case 'save':
		$update = DB::insertIfDifferent($_GET);
		$data = Array();
		$data['cards']= DB::getCards();
		$data['update'] = $update;
		echo json_encode($data);
		break;
	case 'old':
		$data = DB::getAllVersions($_GET['id']);
		echo json_encode($data);
		break;
	case 'revert':
		DB::revertTo($_GET['id']);
		$data = DB::getCard($_GET['id']);
		echo json_encode($data);
		break;
	case 'message':
		DB::addMessage($_GET['message']);
		echo  json_encode($_GET['message']);
		break;
	case 'getmessages':
		$data = DB::getMessages($_GET['last']);
		echo json_encode($data);
		break;
}




?>
