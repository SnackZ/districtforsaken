<?
// Input auffangen
$user = $_GET['user'];
$passwort = $_GET['pw'];

?>
Username = "<?= $user ?>"
<br>
Passwort = "<?= $passwort ?>"
<br>

<?
define('DB_HOST', 'snackz.lima-db.de');
define('DB_NAME', 'db_369904_1');
define('DB_USER', 'USER369904');
define('DB_PASS', 'Ly2fhPrGg');


$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$sql = "SELECT * FROM user";
$q = $pdo->prepare($sql);
if (!$q->execute()) {
	echo 'fehler bei '.$sql;
	exit;
}

$bOk = false;
while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
	echo 'Zeile in DB: '.$row['username'].', passwort ist "'.$row['passwort'].'"';
	echo '<br>';
	if ($user == $row['username']) {
		if ($passwort == $row['passwort']) {
			echo 'OK, you can pass! (f-ing ass...)';
			$bOk = true;
			break;
		} else {
			echo 'User vorhanden, aber pw nicht korrekt';
			break;
		}
	} else {
		echo 'Keine Übereinstimmung, weder bei user noch bei pw';
	}
	echo '<br>';
}

if ($bOk) {
	echo 'Viel Spaß!';
} else {
	echo 'F off, and better be quick!';
}
echo '<br>';
