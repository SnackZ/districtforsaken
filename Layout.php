<?
require_once 'Datenbank.php';

class Layout {
	
	function kopf($clanNr) {
		session_start();
		
		// Input auffangen
		$user = $_GET['user'];
		$passwort = $_GET['pw'];
		$bLogout = isset($_GET['logout']);
		// echo "user = $user, Passwort = $passwort, logout = $logout\n";
		$loginMessage = '';
		
		// logout?
		if ($bLogout) {
			$_SESSION['userid'] = '';
			$loginMessage = 'Du wurdest auf eigenen Wunsch rausgeschmissen, Arschloch.';
		}
		
		// login?
		if ($user && $passwort) {
			$db = new Datenbank();
			$aUser = $db->checkLogin($user, $passwort);
			if ($aUser) {
				$_SESSION['userid'] = $aUser['userid'];
				$_SESSION['username'] = $aUser['username'];
				$loginMessage = 'Login erfolgreich, viel Spaß';
			} else {
				$_SESSION['userid'] = '';
				$loginMessage = 'Login fehlgeschlagen. Idiot.';
			}
		}
		
		?><!DOCTYPE HTML>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="de"><head>
		<meta charset="UTF-8">
		<title>District Forsaken</title>
		<link rel="icon" href="img/forsaken_clan32p.jpg" sizes="32x32" />
		<link rel="stylesheet" href="img/styles.css" type="text/css" media="all" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		</head>
		
		<body>
		<br>
		<table class="centertable">
		<tr>
		<td style="width:180px;font-size:0;padding:0;">
		<img src="img/forsaken_clan2.jpg" style="width:180px; height:180px;border:none;margin:0;">
		</td>
		
		<td style="width:100% - 180px; background-color:yellow; color:black; text-align:center; padding:0;margin:0;">
		<div style="font-size:xx-large; font-weight:bold;">
		District Forsaken
		</div>
		<br>
		<div style="font-weight:bold; color:darkgrey;">
		<a href="ts3server://62.104.20.100?port=10068">Click to join TeamSpeak3!</a>
		</div>
		<br>
		<span style="color:brown">
		<?
		$navEntries = array(
			1 => 'Home', 
			2 => 'Contact', 
			3 => 'ForsakenH00k',
			4 => 'Comments',
			5 => 'Register'
			);
		foreach ($navEntries as $key => $navEntry) {
			if ($clanNr == $key) {
				echo '<span style="font-weight:bold; color:black;">';
				echo $navEntry;
				echo '</span>'."\n";
			} else {
				echo '<a href="clan'.$key.'.php">'.$navEntry.'</a>'."\n";
			}
			if ($key < 5) {
				echo ' &nbsp; &nbsp; <span class="strich"> | &nbsp; </span>&nbsp; ';
			}
		}
		?>
		</span>
		</td>
		</tr>
		<?
		if ($clanNr < 5) {
			?>
			<tr>
			<td colspan="2" style="padding:15px; text-align:left;">
			<form method="get" action="clan<?= $clanNr ?>.php">
			<?
			if ($loginMessage) {
				echo '<span style="color:red">'.$loginMessage.'</span><br>'."\n";
			}
			if ($_SESSION['userid']) {
				echo 'Angemeldet als '.$_SESSION['username'];
				?>
				<input type="submit" value="Log out" name="logout">
				<?
			} else {
				?>
				Log in as <nobr>user <input type="text" name="user"></nobr>
				<nobr>password <input type="text" name="pw"></nobr>
				<input type="submit" value="Log in">
				<?
			}
			?>
			</form>
			</td>
			</tr>
			<?
		}
		?>
		<tr>
		<td colspan="2" style="padding:15px; text-align:left;">
		<?
	}
	
	
	function fuss() {
		?>
		</td>
		</tr>
		</table>
		
		<script src="img/features.js" type="text/javascript"></script>
		</body>
		</html>
		<?
	}
	
}
