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
	
	private function execute($sql, $params) {
	    $q = $this->getPdo()->prepare($sql);
		if (!$q->execute($params)) {
			echo 'fehler bei '.$sql;
			exit;
		}
	}
	
	
	/**
	 * username not yet taken?
	 * @param string $username
	 * @return bool true => username noch frei
	 *              false => username schon belegt
	 */
	public function checkUsername($username) {
        $sql = "SELECT username"
        ." FROM user"
		." WHERE username=:username"
		;
		$params = array(':username' => $username);
		$row = $this->getRow($sql, $params);
		// var_dump($row);
		$bResult = !isset($row['username']);
		return $bResult;
	}
	
	
	
	// Posts für searchtool
	public function dbSearch($search) {
	    $inClause = $this->getWurzeln('sql');
	    $sql = "SELECT k.kommentar, k.datum, k.user, k.id, u.username, u.moderator"
	        ." FROM kommentare k"
	        ." LEFT JOIN user u ON k.user=u.id"
	        ." WHERE k.kommentar LIKE '%$search%'"
	        ." AND k.id NOT ".$inClause
	        ." AND k.deleted=0"
	    ;
	    $aRows = $this->getAll($sql, array());
	    return $aRows;
	}
	
	// News bzw. Threads für index.php von Thread topic NEWS
    public function getNews() {
        $sql = "SELECT kommentar, datum"
            ." FROM kommentare"
            ." WHERE parent=1"
            ." AND deleted=0"
            ." ORDER BY datum DESC"
            ." LIMIT 3"
        ;
        $news = $this->getAll($sql, array());
        return $news;
    }    
    
    
    // ansicht 1 wurzel ids
    public function getWurzeln($lang = '') {
        $wurzeln = array(1, 17, 18, 19, 23, 31, 32);
        if ($lang == 'sql') {
            return 'IN ('.implode(',', $wurzeln).')';
        } else {
            return $wurzeln;
        }
    }
    
    
    // Chef kommentar ID bei klickbaren latest threads
    
    public function getThreadId($kommId) {
        $wurzeln = $this->getWurzeln();
        if (in_array($kommId, $wurzeln)) {
            return $kommId;
        }
        $sql = "SELECT parent"
        ." FROM kommentare"
        ." WHERE id=:id"
        ;
        $params = array(':id' => $kommId);
		$row = $this->getRow($sql, $params);
		if (in_array($row['parent'], $wurzeln) || !$row['parent']) {
		    return $kommId;
		} else {
		    return $this->getThreadId($row['parent']);
		}
    }
    
    
    // Latest Threads bei Forum anzeigen
    
    public function getLatestComms() {
        $sql = "SELECT k.kommentar, k.datum, k.id, k.parent, u.username, u.moderator"
            ." FROM kommentare k"
            ." LEFT JOIN user u ON u.id = k.user"
            ." WHERE k.deleted=0"
            ." ORDER BY datum DESC"
            ." LIMIT 3"
        ;
        $latestThreads = $this->getAll($sql, array());
        return $latestThreads;
    }
    
    
	
	/*
	 * Forum bzw. clan4.php
	 */
	 
	
	// getBeitrag = oberster beitrag bei Children-Baum
    public function getBeitrag($beitragId) {
        $sql = "SELECT u.username, k.id, k.datum, k.kommentar, k.parent, u.moderator"
        ." FROM kommentare k, user u"
		." WHERE k.user=u.id"
		." AND k.id=:beitragid"
		;
		$params = array(':beitragid' => $beitragId);
		$getRow = $this->getRow($sql, $params);
		return $getRow;
    }
    
    
    // children unter top-thread ansicht 3 -> löschbar
    
    public function getChildren($beitragId) {
        $sql = "SELECT u.username, k.id, k.datum, k.kommentar, u.moderator"
        ." FROM kommentare k"
        ." LEFT JOIN user u ON k.user=u.id"
        ." WHERE k.parent=:beitragId"
        ." AND k.deleted=0"
        ." ORDER BY datum DESC"
        ;
        $params = array(':beitragId' => $beitragId);
        $getAll = $this->getAll($sql, $params);
        foreach ($getAll as $key => $child) {
            $getAll[$key]['children'] = $this->getChildren($child['id']);
        }
        return $getAll;
    }
    
    
    // threads unter ansicht 2 -> löschbar
    
	public function getFirstChildren($parent) {
		$sql = "SELECT u.username, k.id, k.datum, k.kommentar, u.moderator"
		." FROM kommentare k"
		." LEFT JOIN user u ON k.user=u.id"
		." WHERE k.parent=:parent"
        ." AND k.deleted=0"
		." ORDER BY datum DESC"
		;
		$params = array(':parent' => $parent);
		$rows = $this->getAll($sql, $params);
		
		// Anzahl der children dazuspielen
		$sqlAnzahl = "SELECT count(*) anzahl FROM kommentare"
		    ." WHERE parent=:id AND deleted=0"
		;
		foreach ($rows as $nr => $row) {
		    $rowAnz = $this->getRow($sqlAnzahl, array(':id' => $row['id']));
		    $rows[$nr]['anzahlChildren'] = $rowAnz['anzahl'];
		}
		return $rows;
	}
	
	/** 
	 * Check von name und passwort
	 * @param string $user: name des users gecheckt
	 * @param string $pw: passwort des users gecheckt
	 * @return false, falls keine übereinstimmung in datenbank
	 *         array mit userdaten falls login möglich
	 */
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
				'username' => $row['username'],
				'moderator' => $row['moderator']
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
		$params = array(
		    ':id' => $id
		    );
		$this->execute($sql, $params);
		return true;
    }
    
	public function createUser($name, $pw, $email, $code) {
		$sql = "INSERT INTO user(username, passwort, email, code, confirmed)"
			." VALUES(:username, :passwort, :email, :code, 0)"
		;
		$param = array(
			':username' => $name,
			':passwort' => $pw,
			':email' => $email,
			':code' => $code
		);
		$this->execute($sql, $param);
	}
	
	public function deleteKomm($kommId) {
	    $sql = "UPDATE kommentare SET deleted=1 WHERE id=:id";
	    $params = array(
	        ':id' => $kommId
	        );
	    $this->execute($sql, $params);
	}
	
	public function createCommentary($commentary, $parentId) {
		$sql = "INSERT INTO kommentare(parent, user, datum, kommentar)"
			." VALUES(:parent, :user, :datum, :kommentar)"
		;
		$param = array(
		    ':parent' => $parentId,
			':user' => $_SESSION['userid'],
			':datum' => date('Y-m-d H:i:s'),
			':kommentar' => $commentary
		);
		$this->execute($sql, $param);
	}
	
}
