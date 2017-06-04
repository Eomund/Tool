<?php
include('details.php');
$dsn = 'mysql:host='.$host.';dbname=' . $dbname;
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
                                                                                                                                    
DB::$dbh = new PDO($dsn, $username, $password, $options);
DB::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

class DB{
	static $dbh;
	
	static function setup(){
		
			
		DB::$dbh->exec("CREATE TABLE IF NOT EXISTS users ( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50) NOT NULL , initials VARCHAR(50) NOT NULL , colour VARCHAR(6) NOT NULL )");
		DB::$dbh->exec("CREATE TABLE IF NOT EXISTS places ( id INT NOT NULL AUTO_INCREMENT , name VARCHAR(50) NOT NULL , active BOOLEAN NOT NULL , PRIMARY KEY (id))");
		DB::$dbh->exec("CREATE TABLE IF NOT EXISTS revisions ( id INT NOT NULL AUTO_INCREMENT , card INT NOT NULL , name VARCHAR(50) NOT NULL , description TEXT NOT NULL , place INT NOT NULL , user INT NOT NULL, edittime TIMESTAMP NOT NULL , PRIMARY KEY (id))");
		DB::$dbh->exec("CREATE TABLE IF NOT EXISTS choice ( id INT NOT NULL AUTO_INCREMENT , revision INT NOT NULL, option1 TEXT NOT NULL, roll1 INT NOT NULL , good1 TEXT NOT NULL , bad1 TEXT NOT NULL, option2 TEXT NOT NULL, roll2 INT NOT NULL , good2 TEXT NOT NULL , bad2 TEXT NOT NULL, PRIMARY KEY (id)) ");
		DB::$dbh->exec("CREATE TABLE IF NOT EXISTS dealwithit ( id INT NOT NULL AUTO_INCREMENT , revision INT NOT NULL, outcome TEXT NOT NULL , PRIMARY KEY (id))  "); 
		DB::$dbh->exec("CREATE TABLE IF NOT EXISTS notes ( id INT NOT NULL AUTO_INCREMENT , type ENUM('card') NOT NULL , user INT NOT NULL , time TIMESTAMP NOT NULL , text TEXT NOT NULL , PRIMARY KEY (id)) "); 
		DB::$dbh->exec("CREATE TABLE IF NOT EXISTS card ( id INT NOT NULL AUTO_INCREMENT , status ENUM('Getting started', 'Needs work', 'Almost there', 'Done'), PRIMARY KEY (id)) ");
		echo "Setup complete!";
		
	} 
	
	static function getUserData($name){
			$query = DB::$dbh->prepare('SELECT * FROM users WHERE name = ?');
			$query->execute(Array($name));
			$data = $query->fetchAll(PDO::FETCH_OBJ);
			if(count($data) == 0){
				return false;		
			}
			return $data[0];
	}
					
	static function getCards(){
		$query = DB::$dbh->prepare("SELECT r.id, name, description, place, edittime, outcome, status FROM (SELECT r.id,name,description,place,edittime,STATUS FROM (SELECT * FROM (SELECT * From revisions ORDER BY edittime DESC) as o GROUP BY card) as r, card WHERE r.card = card.id) as r LEFT JOIN dealwithit ON (r.id = dealwithit.revision)");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		return $data;
	
	}
	
	static function getPlaces(){
		$query = DB::$dbh->prepare("SELECT * From places");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		return $data;
	
	}
	
	static function getCard($id){
		$query = DB::$dbh->prepare("SELECT r.id, r.name, description, place, edittime, option1, option2, roll1, good1, bad1, option2, roll2, good2, bad2, outcome, status, initials, colour FROM (SELECT r.id,r.name,description,place,edittime,status, initials, colour FROM (SELECT * FROM (SELECT * From revisions ORDER BY edittime DESC) as o GROUP BY card) as r, card, users WHERE r.card = card.id AND users.id = user) as r LEFT JOIN choice ON (r.id = choice.revision) LEFT JOIN dealwithit ON (r.id = dealwithit.revision) WHERE r.id = ?");
		$query->execute(Array($id));
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		return $data[0];
	
	}
	
	static function differentvalues($a, $b){
		foreach($a as $k => $v){
			if(isset($b[$k])){
				if($a[$k] != $b[$k]){
					return true;
				}
			}
		}
		return false;
	}
	
	static function insertIfDifferent($data){
	
		
		//brand new card
		if($data['id'] == ""){
			
			$query = DB::$dbh->prepare("INSERT INTO card (status) VALUES (?);");
			$query->execute(Array($data['status']));
			$card = DB::$dbh->lastInsertId();
			
			
		}else{
			$query = DB::$dbh->prepare("SELECT r.id, r.name, description, place, edittime, option1, option2, roll1, good1, bad1, option2, roll2, good2, bad2, outcome, status, initials, colour FROM (SELECT r.id,r.name,description,place,edittime,status, initials, colour FROM (SELECT * FROM (SELECT * From revisions ORDER BY edittime DESC) as o GROUP BY card) as r, card, users WHERE r.card = card.id AND users.id = user) as r LEFT JOIN choice ON (r.id = choice.revision) LEFT JOIN dealwithit ON (r.id = dealwithit.revision) WHERE r.id = ?");
			$query->execute(Array($data['id']));
			$old = $query->fetchAll(PDO::FETCH_ASSOC);
			
			//This tests to see if we have new data
			if(DB::differentvalues($data, $old[0]) || (isset($old[0]['outcome']) == ($data['type'] == "choice"))){
				$query = DB::$dbh->prepare("SELECT card FROM revisions WHERE id = ?");
				$query->execute(Array($data['id']));
				$card = $query->fetchAll(PDO::FETCH_ASSOC);
				$card = $card[0]['card'];
			}	
		}
		if(isset($card)){
			$query = DB::$dbh->prepare("UPDATE card SET status = ? WHERE id = ?");
			$query->execute(Array($data['status'], $card));
			
			$query = DB::$dbh->prepare("INSERT INTO revisions (card, name, description, place, user, edittime) VALUES(?,?,?,?,?, NOW());");
			$query->execute(Array($card, $data['name'], $data['description'], $data['place'], $_SESSION['user']->id));
			$id = DB::$dbh->lastInsertId();
			
			if($data['type'] == "choice"){
				$query = DB::$dbh->prepare("INSERT INTO choice (revision, option1, roll1, good1, bad1, option2, roll2, good2, bad2) VALUES(?,?,?,?,?,?,?,?,?);");
				$query->execute(Array($id, $data['option1'], $data['roll1'], $data['good1'], $data['bad1'], $data['option2'], $data['roll2'], $data['good2'], $data['bad2']));	
			}else{
				$query = DB::$dbh->prepare("INSERT INTO dealwithit (revision, outcome) VALUES(?,?);");
				$query->execute(Array($id, $data['outcome']));	
			}
		}else{
			$id = $data['id'];	
		}
		
		$query = DB::$dbh->prepare("SELECT initials, colour, edittime FROM revisions, users WHERE revisions.id = ? AND users.id = user");
		$query->execute(Array($id));
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		return $res[0];
			
		
		
		
	}
	
	static function getAllVersions($rid){
		$query = DB::$dbh->prepare("SELECT card FROM revisions WHERE id = ?");
		$query->execute(Array($rid));
		$card = $query->fetchAll(PDO::FETCH_ASSOC);
		$card = $card[0]['card'];
		$query = DB::$dbh->prepare("SELECT * FROM (SELECT revisions.id as rid, revisions.*, places.name as placename, users.initials, users.colour FROM revisions, places, users WHERE places.id = revisions.place AND users.id = revisions.user) as p LEFT JOIN choice ON (p.id = revision) LEFT JOIN dealwithit ON (p.id = dealwithit.revision) WHERE card = ? AND p.id <> ? ORDER BY edittime DESC;");
		
		$query->execute(Array($card, $rid));
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	
	static function revertTo($id){
			$query = DB::$dbh->prepare("UPDATE revisions SET edittime = NOW() WHERE id = ?");
			$query->execute(Array($id));
		
	}
	
}