<?
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(5);

$bShowForm = true;

// Input auffangen
$newUser = $_GET['username'];
$newPw = $_GET['password'];
$newEmail = $_GET['email'];

if ($newUser || $newPw || $newEmail) {
	$bOk = true;
	// check userangaben
	if (!$newUser){
		$bOk = false;
		$message = "Bitte einen Usernamen wählen";
	}
	if (!$newPw){
		$bOk = false;
		$message = "Bitte ein Passwort wählen";
	}
	if (!$newEmail){
		$bOk = false;
		$message = "Bitte eine gültige Email angeben";
	}
	if ($bOk){
		$db = new Datenbank();
		$db->createUser($newUser, $newPw, $newEmail);
		$message = 'User "'.$newUser.'" ist nun registriert. Sie können sich nun anmelden.';
		$bShowForm = false;
	}
}
echo '<span style="color:red">'.$message.'</span>';
?>
<br>

<?
if ($bShowForm) {
	?>
	<form method="get" action="clan5.php">
	<table style="margin:auto;">
	<tr>
	<td style="text-align:right;">
	username:
	</td>
	<td>
	<input type="text" name="username">
	</td>
	</tr>
	<tr>
	<td style="text-align:right;">
	password:
	</td>
	<td>
	<input type="text" name="password">
	</td>
	</tr>
	<tr>
	<td style="text-align:right;">
	e-mail:
	</td>
	<td>
	<input type="text" name="email">
	</td>
	</tr>
	</table>
	<br>
	
	<div style="text-align:center;">
	<input type="submit" value="Register">
	</div>
	<?
} else {
	?>
	<br>
	<a href="clan1.php">Zurück zum Start</a>
	<?
}


$layout->fuss();
