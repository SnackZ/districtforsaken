<?
define('DB_HOST', 'snackz.lima-db.de');
define('DB_NAME', 'db_369904_1');
define('DB_USER', 'USER369904');
define('DB_PASS', 'Ly2fhPrGg');
/*
define('DB_HOST', 'localhost');
define('DB_NAME', 'districtforsaken');
define('DB_USER', 'root');
define('DB_PASS', 'apfel32baum');
*/
define('BASE_URL', 'https://www.beast-community.com/');


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
	
	
	// passwort vergessen -> email checken ob vorhanden in datenbank
	public function forgotCheckEmail($email) {
	    $sql = "SELECT email"
	        ." FROM user"
	        ." WHERE email=:email"
	    ;
	    $params = array(':email' => $email);
	    $row = $this->getRow($sql, $params);
	    $bEmailExist = !isset($row['email']);
	    return $row;
	}
	
	// ob email bei registration bereits ienmal verwendet wurde
	public function checkEmail($email) {
	    $sql = "SELECT email"
	    ." FROM user"
	    ." WHERE email=:email"
	    ;
	    $params = array(':email' => $email);
	    $row = $this->getRow($sql, $params);
	    $bResult = !isset($row['email']);
	    return $bResult;
	}
	
	
	// download ranking ob er auf download.php kann
	public function getDownloadRank($id) {
	    $sql = "SELECT download"
	    ." FROM user"
	    ." WHERE id=:id"
	    ;
	    $params = array(':id' => $id);
	    $row = $this->getRow($sql, $params);
	    return $row;
	}
	
	// profil infos alle spalten AUßER passwort
	public function getUserZeile($id) {
		$sql = "SELECT id, username, email, joindate, lastlogin, code, confirmed, rank, link, beschreibung, gender, birthdate, country"
		." FROM user"
		." WHERE id=:id"
		;
		$params = array(':id' => $id);
		$row = $this->getRow($sql, $params);
		return $row;
	}
	
	// SELECT COUNT(Anzahl) AS id_count, Anzahl FROM `table` GROUP BY Anzahl
	
	
	// Anzahl der posts von bestimmtem profil
	public function getUserPosts($id) {
	    $sql = "SELECT count(parent) anzahl FROM kommentare"
	    ." WHERE user=:id"
	    ." AND parent NOT IN (1, 2, 17, 18, 19, 23, 31, 32)"
	    ." AND deleted=0"
	    ;
	    $params = array(
	        ':id' => $id,
	    );
	    $row = $this->getRow($sql, $params);
	    return $row;
	}
	
	// Anzahl der threads von bestimmtem profil
	public function getUserThreads($id) {
	    $sql = "SELECT count(parent) anzahl FROM kommentare"
	    ." WHERE user=:id"
	    ." AND parent IN (2, 17, 18, 19, 23, 31, 32)"
	    ." AND deleted=0"
	    ;
	    $params = array(
	        ':id' => $id,
	    );
	    $row = $this->getRow($sql, $params);
	    return $row;
	}
	
	// Anzahl der verfassten NEWS von bestimmten profil
	public function getUserNEWS($id) {
	    $sql = "SELECT count(parent) anzahl FROM kommentare"
	    ." WHERE user=:id"
	    ." AND parent IN (1)"
	    ." AND deleted=0"
	    ;
	    $params = array(
	        ':id' => $id,
	    );
	    $row = $this->getRow($sql, $params);
	    return $row;
	}
	
	// beschreibung bei user tabelle updaten
	public function updateProfilBeschreibung($id, $beschreibung) {
		$sql = "UPDATE user"
		." SET beschreibung=:beschreibung"
		." WHERE id=:id"
		;
		$params = array(
			':beschreibung' => $beschreibung,
			':id' => $id
		);
		$this->execute($sql, $params);
	}
	
	// gender updaten
	public function updateGender($id, $gender) {
	    $sql = "UPDATE user"
	    ." SET gender=:gender"
	    ." WHERE id=:id"
	    ;
	    $params = array(
	        ':gender' => $gender,
	        ':id' => $id
	    );
	    $this->execute($sql, $params);
	}
	
	// country updaten
	public function updateCountry($id, $country) {
	    $sql = "UPDATE user"
	    ." SET country=:country"
	    ." WHERE id=:id"
	    ;
	    $params = array(
	        ':country' => $country,
	        ':id' => $id
	    );
	    $this->execute($sql, $params);
	}	
	
	// alter/geburtsdatum updaten
	public function updateBirthdate($id, $birthdate) {
	    $sql = "UPDATE user"
	    ." SET birthdate=:birthdate"
	    ." WHERE id=:id"
	    ;
	    $params = array(
	        ':birthdate' => $birthdate,
	        ':id' => $id
	    );
	    $this->execute($sql, $params);
	}
	
	// link updaten
	public function updateSteamLink($id, $steamLink) {
		$sql = "UPDATE user"
		." SET link=:steamLink"
		." WHERE id=:id"
		;
		$params = array(
			':steamLink' => $steamLink,
			':id' => $id
		);
		$this->execute($sql, $params);
	}
	
	/*
	// link für eigenes profil bekommen
	public function getSteamLink($id) {
		$sql = "SELECT link"
		." FROM user"
		." WHERE id=:id"
		;
		$row = $this->getRow($sql, array(':id' => $id));
		if (!$row) {
			echo 'No user with ID '.$id.' existing!';
			exit;
		}
		return $row['link'];
	}
	*/
	// username von wem latest thread bei ansicht 1 topics
	public function getLatestThreadVerf($id) {
	    $sql = "SELECT k.datum, k.parent, k.user, k.id, u.username, u.id userid"
	        ." FROM kommentare k"
	        ." LEFT JOIN user u ON k.user=u.id"
	        ." WHERE k.deleted=0"
	        ." AND k.parent=:id"
	        ." ORDER BY datum DESC"
	        ." LIMIT 1"
	    ;
	    $params = array(':id' => $id);
	    $result = $this->getRow($sql, $params);
	    return $result;
	}
	
	// threadanzahl von topics bei ansicht 1 anzeigen
	public function getAnzahlTopicThreads($id) {
	    $sql = "SELECT count(parent) anzahl FROM kommentare"
	        ." WHERE deleted=0"
	        ." AND parent=:id"
	    ;
	    $params = array(':id' => $id);
	    $result = $this->getRow($sql, $params);
	    return $result;
	}
	
	// Profile für searchtool
	public function profilSearch($profilSearch) {
		$sql = "SELECT id, username, rank"
		." FROM user"
		." WHERE username LIKE '%$profilSearch%'"
		." OR id LIKE '%$profilSearch%'"
		." AND confirmed=1"
		;
		$aRows = $this->getAll($sql, array());
		return $aRows;
	}
	
	// Posts für searchtool
	public function dbSearch($search) {
	    $inClause = $this->getWurzeln('sql');
	    $sql = "SELECT k.kommentar, k.datum, k.user, k.id, u.username, u.rank, u.id userid"
	        ." FROM kommentare k"
	        ." LEFT JOIN user u ON k.user=u.id"
	        ." WHERE k.kommentar LIKE '%$search%'"
	        ." AND k.id NOT ".$inClause
	        ." AND k.deleted=0"
	        ." ORDER BY datum DESC"
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
    
    // Neuestes member in der datenbank tabelle 'user'
    public function getNewestMember() {
        $sql = "SELECT username, id"
            ." FROM user"
            ." WHERE confirmed=1"
            ." GROUP BY id"
            ." ORDER BY id DESC"
            ." LIMIT 1"
        ;
        $newestUser = $this->getAll($sql, array());
        return $newestUser;
    }
    
    // ansicht 1 wurzel ids
    public function getWurzeln($lang = '') {
        $wurzeln = array(1, 2, 17, 18, 19, 23, 31, 32);
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
    
    /*
    // parent vom header post bei ansicht 3 rausfinden (thread id)
    public function getVipParent($kommId) {
        $wurzeln = $this->getThreadId($kommId);
        $sql = "SELECT id, parent"
            ." FROM kommentare"
            ." WHERE deleted=0"
            ." AND id=:id"
        ;
        $params = array('id' => $wurzeln);
        $vipPosts = $this->getRow($sql, $params);
        return $vipPosts;
    }
    */
    /*
    // parent von parent (bzw. ergebnis) von oben
    public function getParentsParent($kommId) {
        $aParents = $this->getVipParent($kommId);
        $sql = "SELECT id, parent"
            ." FROM kommentare"
            ." WHERE deleted=0"
            ." AND id=:id"
        ;
        $params = array('id' => $aParents['parent'])
        $result = $this->getRow($sql, $params);
        return $result;
    }
    */
    
    // Latest Threads bei Forum anzeigen
    public function getLatestComms() {
        $sql = "SELECT k.kommentar, k.datum, k.id, k.parent, u.username, u.rank, u.id userid"
            ." FROM kommentare k"
            ." LEFT JOIN user u ON u.id = k.user"
            ." WHERE k.deleted=0"
            ." AND k.parent!=1"
            ." AND k.parent!=2"
            ." ORDER BY datum DESC"
            ." LIMIT 5"
        ;
        $latestThreads = $this->getAll($sql, array());
        /*
        $kommId = $latestThreads['k.id'];
        $aNotShow = $this->getParentsParent($kommId);
        */
        return $latestThreads;
        
    }
    
    
	
	/*
	 * Forum bzw. clan4.php
	 */
	 
	
	// getBeitrag = oberster beitrag bei Children-Baum
    public function getBeitrag($beitragId) {
        $sql = "SELECT u.username, u.id userid, k.id, k.datum, k.kommentar, k.parent, u.rank, u.country country"
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
        $sql = "SELECT k.id, k.datum, k.kommentar, u.username, u.rank, u.id userid, u.country country"
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
		$sql = "SELECT u.username, u.id userid, u.country country, k.id, k.datum, k.kommentar, u.rank"
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
		if (md5($pw) != $row['passwort']) {
		    // Passwort stimmt nicht überein
		    return false;
		}
		
		if ($row['confirmed'] == 1) {
		    // last login setzen, updaten
		    $lastLogin = date("Y-m-d H:i:s");
		    $sqlUpdate = "UPDATE user"
		        ." SET lastlogin=:lastlogin"
		        ." WHERE id=:id"
		    ;
		    $pms = array(
		        ':id' => $row['id'],
		        ':lastlogin' => $lastLogin,
		    );
		    $this->execute($sqlUpdate, $pms);
		    
		    // ergebnis, login succeeded
		    $result = array(
				'userid' => $row['id'],
				'username' => $row['username'],
				'rank' => $row['rank'],
			);
			return $result;
		} else {
		    // Confirmed ungleich 1
		    return false;
		}
	}  
	
	// update user passwort, wenn alles gut gelaufen ist
	public function updatePassword($id, $pw) {
	    $sql = "UPDATE user"
	        ." SET passwort=:pw, pwexpire=:pwexpire"
	        ." WHERE id=:id"
	    ;
	    $params = array(
	        ':id' => $id,
	        ':pw' => md5($pw),
	        ':pwexpire' => date("Y-m-d H:i:s"),
	    );
	    $this->execute($sql, $params);
	    return true;
	}
	
	// bei forgot password changepw.php ob code übereinstimmt => userid
	public function getUserByCode($codeId) {
	    $sql = "SELECT id, pwexpire"
	        ." FROM user"
	        ." WHERE pwcode=:codeid"
	    ;
	    $params = array(':codeid' => $codeId);
	    $row = $this->getRow($sql, $params);
	    return $row;
	}
	
	// updateCode bei user password reset
    public function setNewPasswordCode($userId, $code) {
        $dt = new DateTime();
        $dt->add(new DateInterval('PT2H'));
        $pwExpire = $dt->format("Y-m-d H:i:s");
        $sql = "UPDATE user"
            ." SET pwcode=:code, pwexpire=:pwexpire"
            ." WHERE id=:id"
        ;
        $params = array(
            ':id' => $userId,
            ':code' => $code,
            ':pwexpire' => $pwExpire,
        );
        $this->execute($sql, $params);
		return true;
    }
	
	
	// makecode bei user register
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
		$sql = "UPDATE user SET confirmed=1, rank=1, joindate=sysdate() WHERE id=:id";
		$params = array(
		    ':id' => $id
		    );
		$this->execute($sql, $params);
		return true;
    }
    
    public function getUserByEmail($email) {
        $sql = "SELECT passwort, id"
            ." FROM user"
            ." WHERE email=:email"
        ;
        $params = array(':email' => $email);
        $row = $this->getRow($sql, $params);
        return $row;
    }
    
	public function createUser($name, $pw, $email, $code) {
		$sql = "INSERT INTO user(username, passwort, email, code, confirmed)"
			." VALUES(:username, :passwort, :email, :code, 0)"
		;
		$param = array(
			':username' => $name,
			':passwort' => md5($pw),
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
