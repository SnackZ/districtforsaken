<?
define('DB_HOST', 'snackz.lima-db.de');
define('DB_NAME', 'db_369904_1');
define('DB_USER', 'USER369904');
define('DB_PASS', 'Ly2fhPrGg');

class Datenbank {
	
	private function getPdo() {
		if (!$GLOBALS['pdo']) {
			$GLOBALS['pdo'] = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
		}
		return $GLOBALS['pdo'];
	}
	
	
	
	function getKommentare() {
		$sql = "SELECT u.username, k.datum, k.kommentar"
		." FROM kommentare k, user u"
		." WHERE k.user=u.id"
		." ORDER BY datum DESC"
		;
		$q = $this->getPdo()->prepare($sql);
		if (!$q->execute()) {
			echo 'fehler bei '.$sql;
			exit;
		}
		$result = $q->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	
	// user, pw übergeben, userid und username im Erfolgfall bekommen
	function checkLogin($user, $pw) {
		$sql = "SELECT * FROM user WHERE username='".$user."'";
		$q = $this->getPdo()->prepare($sql);
		if (!$q->execute()) {
			echo 'fehler bei '.$sql;
			exit;
		}
		
		$bOk = false;
		$row = $q->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			return false;
		}
		
		// Zeile gefunden
		if ($pw == $row['passwort']) {
			$result = array(
				'userid' => $row['id'],
				'username' => $row['username']
			);
			return $result;
		} else {
			return false;
		}
	}
	
	
	function createUser($name, $pw, $email) {
		$sql = "INSERT INTO user(username, passwort, email)"
			." VALUES(:username, :passwort, :email)"
		;
		$q = $this->getPdo()->prepare($sql);
		$param = array(
			':username' => $name,
			':passwort' => $pw,
			':email' => $email
		);
		if (!$q->execute($param)) {
			echo 'fehler bei '.$sql;
			exit;
		}
	}
	
	function createCommentary($commentary) {
		$sql = "INSERT INTO kommentare(user, datum, kommentar)"
			." VALUES(:user, :datum, :kommentar)"
		;
		$q = $this->getPdo()->prepare($sql);
		$param = array(
			':user' => $_SESSION['userid'],
			':datum' => date('Y-m-d H:i:s'),
			':kommentar' => $commentary
		);
		if (!$q->execute($param)) {
			echo 'fehler bei '.$sql;
			exit;
		}
	}
	
}
