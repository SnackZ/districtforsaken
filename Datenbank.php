<?
define('DB_HOST', 'snackz.lima-db.de');
define('DB_NAME', 'db_369904_1');
define('DB_USER', 'USER369904');
define('DB_PASS', 'Ly2fhPrGg');

class Datenbank {
	
	private function getPdo() {
		if (!isset($GLOBALS['pdo']) || !$GLOBALS['pdo']) {
			$GLOBALS['pdo'] = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
		}
		return $GLOBALS['pdo'];
	}
	private function getAll($sql, $params) {
	    $q = $this->getPdo()->prepare($sql);
		if (!$q->execute($params)) {
			echo 'fehler bei '.$sql;
			exit;
		}
		$result = $q->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	
	
	private function getRow($sql, $params) {
	    $q = $this->getPdo()->prepare($sql);
		if (!$q->execute($params)) {
			echo 'fehler bei '.$sql;
			exit;
		}
		$result = $q->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	
	
	// Forum bzw. clan4.php

    public function getBeitrag($beitragId) {
        $sql = "SELECT u.username, k.id, k.datum, k.kommentar"
        ." FROM kommentare k, user u"
		." WHERE k.user=u.id"
		." AND k.id=:beitragid"
		;
		$params = array(':beitragid' => $beitragId);
		$getRow = $this->getRow($sql, $params);
		return $getRow;
    }
    
    public function getChildren($beitragId) {
        $sql = "SELECT u.username, k.id, k.datum, k.kommentar"
        ." FROM kommentare k, user u"
        ." WHERE k.user=u.id"
        ." AND k.parent=:beitragId"
        ." ORDER BY datum DESC"
        ;
        $params = array(':beitragId' => $beitragId);
        $getAll = $this->getAll($sql, $params);
        foreach ($getAll as $key => $child) {
            $getAll[$key]['children'] = $this->getChildren($child['id']);
        }
        return $getAll;
    }
        
	/*
    public function fakultaet($n) {
        if ($n == 1) {
            return 1;
        }
        return fakultaet($n - 1) * $n;
    }
    */
    
    
	
	
	function getWurzeln() {
		$sql = "SELECT u.username, k.id, k.datum, k.kommentar"
		." FROM kommentare k, user u"
		." WHERE k.user=u.id"
		." AND k.parent=0"
		." ORDER BY datum DESC"
		;
		$getAll = $this->getAll($sql, array());
		return $getAll;
	}
	
	// user, pw übergeben, userid und username im Erfolgfall bekommen
	function checkLogin($user, $pw) {
	    $sql = "SELECT * FROM user WHERE username=:username";
		$params = array(
		    ':username' => $user
		    );
		$row = $this->getRow($sql, $params);
		if (!$row) {
		    // Keine Zeile mit username
			return false;
		}
		if ($pw != $row['passwort']) {
		    // Passwort stimmt nicht überein
		    return false;
		}
		
		if ($row['confirmed'] == 1) {
		    $result = array(
				'userid' => $row['id'],
				'username' => $row['username']
			);
			return $result;
		} else {
		    // Confirmed ungleich 1
		    return false;
		}
	}  
	
	public function makeCode() {
	    $code = '';
	    
	    for ($i=1;$i<=10;$i++) {
	        $zahl = rand(1,2);
	        if ($zahl == 1) {
	            $code .= chr(rand(65, 90));
	        } else {
	            $code .= chr(rand(48, 57));
	        }
	    }
	    return $code;
    }

    public function checkCode($check) {
        $sql = "SELECT * FROM user WHERE code=:code"; 
        $params = array(
            ':code' => $check
            );
        $row = $this->getRow($sql, $params);
		$bOk = false;
		if (!$row) {
			return false;
		}
		$id = $row['id'];
		$sql = "UPDATE user SET confirmed=1 WHERE id=:id";
		$q = $this->getPdo()->prepare($sql);
		$params = array(
		    ':id' => $id
		    );
		if (!$q->execute($params)) {
			echo 'fehler bei '.$sql;
			exit;
		}
		return true;
    }
    
    
    
	public function createUser($name, $pw, $email, $code) {
		$sql = "INSERT INTO user(username, passwort, email, code, confirmed)"
			." VALUES(:username, :passwort, :email, :code, 0)"
		;
		$q = $this->getPdo()->prepare($sql);
		$param = array(
			':username' => $name,
			':passwort' => $pw,
			':email' => $email,
			':code' => $code
		);
		if (!$q->execute($param)) {
			echo 'fehler bei '.$sql;
			exit;
		}
	}
	
	function createCommentary($commentary, $parentId) {
		$sql = "INSERT INTO kommentare(parent, user, datum, kommentar)"
			." VALUES(:parent, :user, :datum, :kommentar)"
		;
		$q = $this->getPdo()->prepare($sql);
		$param = array(
		    ':parent' => $parentId,
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
