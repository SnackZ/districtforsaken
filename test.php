<?
define('DB_HOST', 'snackz.lima-db.de');
define('DB_NAME', 'db_369904_1');
define('DB_USER', 'USER369904');
define('DB_PASS', 'Ly2fhPrGg');


$sql = "SELECT id, passwort"
    ." FROM user"
;
$users = getAll($sql, array());

foreach ($users as $user) {
    $newPw = md5($user['passwort']);
    $sqlUpdate = "UPDATE user"
        ." SET passwort=:newpw"
        ." WHERE id=:id"
    ;
    execute($sqlUpdate, array(':newpw' => $newPw, ':id' => $user['id']));
}

echo 'fertig';
exit;

// functions für test.php

function getPdo() {
    if (!isset($GLOBALS['pdo']) || !$GLOBALS['pdo']) {
        $GLOBALS['pdo'] = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }
    return $GLOBALS['pdo'];
}

function getAll($sql, $params) {
    $q = getPdo()->prepare($sql);
    if (!$q->execute($params)) {
        echo 'fehler bei '.$sql;
        exit;
    }
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function execute($sql, $params) {
    $q = getPdo()->prepare($sql);
    if (!$q->execute($params)) {
        echo 'fehler bei '.$sql;
        exit;
    }
}
