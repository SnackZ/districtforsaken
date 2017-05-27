<?
ini_set('error_log', 'error.log');
// ini_set('display_errors', 1);
session_start();
require_once('Layout.php');
require_once('MyMailer.php'); 
                   
// Input auffangen
$user = isset($_POST['user']) ? $_POST['user'] : '';
$passwort = isset($_POST['pw']) ? $_POST['pw'] : '';
$bLogout = isset($_POST['logout']);
// echo "user = $user, Passwort = $passwort, logout = $logout\n";
$loginMessage = '';

$db = new Datenbank();
$mailer = new MyMailer();

// confirm
$confirmCode = isset($_GET['code']) ? $_GET['code'] : '';
if ($confirmCode) {
    $bChecked = $db->checkCode($confirmCode);
    if ($bChecked) {
        $loginMessage = 'Your account has been activated successfully! Please log in now!';
    } else {
        $loginMessage = 'Verification failed!';
    }
}


// logout?
if ($bLogout) {
	$_SESSION['userid'] = '';
	$loginMessage = 'Successfully logged out.';
}

// login?
if ($user && $passwort) {
	$aUser = $db->checkLogin($user, $passwort);
	if ($aUser) {
		$_SESSION['userid'] = $aUser['userid'];
		$_SESSION['username'] = $aUser['username'];
		$_SESSION['moderator'] = $aUser['moderator'];
		$loginMessage = 'Successfully logged in.';
	} else {                      
		$_SESSION['userid'] = '';
		$loginMessage = 'Login failed.';
	}
}

// register?

// Input auffangen

if (isset($_POST['username']) || isset($_POST['password']) || isset($_POST['email'])) { 
    
    $newUser = $_POST['username'];
    $newPw = $_POST['password'];
    $newEmail = $_POST['email'];
    $confirmPw = $_POST['confirm'];
    
	$bOk = true;
	// check userangaben
	if (!$newUser){
		$bOk = false;
		$loginMessage = "Please specify a username";
	}
	if (!$db->checkUsername($newUser)) {
	    $bOk = false;
	    $loginMessage = "'$newUser' is already taken";
	}
	if ($bOk && !$newPw){
		$bOk = false;
		$loginMessage = "Please specify a password";
	}  
	if ($bOk && ($confirmPw != $newPw)){
		$bOk = false;
		$loginMessage = "Password confirmation failed";
	}  
	if ($bOk && !$newEmail){
		$bOk = false;
		$loginMessage = "Please indicate a valid email address";
	}
	if ($bOk && !$mailer->validateAddress($newEmail)) {
	    $bOk = false;
	    $loginMessage = "The chosen email address '$newEmail' is not valid";
	}
	
	// los gehts, alles korrekt
	if ($bOk){
		$code = $db->makeCode();
		$db->createUser($newUser, $newPw, $newEmail, $code);
		$mailText = "Dear $newUser,
		
Please activate your account at District Forsaken by clicking on the following link:
Click here: https://districtforsaken.de/clan5.php?code=".$code."

After verification you will be able to use our forums at https://districtforsaken.de !

This email has been generated automatically!
If you havn't signed up at our website, ignore this content!"
        ;
        $subject = "Your verification for District Forsaken";
        echo '<!--';
		$mailError = $mailer->sendMail($subject, $mailText, $newEmail);
		echo '-->';
		// $mailError = mail($newEmail, $subject, $mailText);
		if ($mailError) {
		    $loginMessage = 'There could not be sent an email to you. Please contact support!';
		} else {
		    $loginMessage = 'User "'.$newUser.'" has been signed up. 
		    Please check your emails for the account verification.'
		    ;
		}
	}
}


$layout = new Layout();

$layout->kopf(5);

if ($loginMessage) {
    echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
	echo '<div style="text-align:center; color:red">'.$loginMessage.'</div>'."\n";
	echo '</div>';
}

?>                 
<br>
<table style="margin:auto;">
<?                                                

if (isset($_SESSION['userid']) && $_SESSION['userid']) {
	?>  
	<form method="post">
	<div style="text-align:center; box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	<?
	echo '<span style="color:red;">Logged in as '.$_SESSION['username'].'</span>';
	?>  
	&nbsp
	<input type="submit" value="Log out" name="logout" style="background:darkgrey;">
	</div>
	</form>
	<!--
	<br>
	<div style="text-align:center; box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	Note: Please excuse, that the following features are not ready for use right now.
	</div>
	-->
	<br>
	<?
	if (isset($_SESSION['moderator']) && $_SESSION['moderator']) {
	    $forumRank = '<span style="color:Lime;">Moderator</span>';
	} else {
	    $forumRank = '<span style="color:Blue;">User</span>';
	}
	echo '<div style="text-align:center; box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
	echo 'Forum ranking: '.$forumRank;
	echo '</div>';

} else {
	?>
	<form method="post">
	<div style="box-shadow: 2px 1px 4px #888888; text-align:center; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	<span style="text-align:center; color:red; font-weight:bold" colspan="2">
	Log in with an existing account
	</span>
	</div>
	<br>
	<tr> 
	<td style="text-align:right;">
	Log in as user:
	</td>
	<td>
	<input type="text" name="user" style="border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:right;">
	password:
	</td>
	<td>
	<input type="password" name="pw" style="border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:center;" colspan="2">
	<input type="submit" value="Log in" style="background:darkgrey;">
	</td>
	</tr> 
	
	</form>
	
	<tr>
	<td>         
	&nbsp;
	</td>
	</tr>
	</table>
	
	<!--
	<div style="box-shadow: 2px 1px 4px #888888; text-align:center; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	Note: Please excuse, that the email confirmation is not operational right now. You have to contact support or an admin for activating your registered account.
	</div>
	<br>
	-->
	
	<table style="margin:auto;">
	<form method="post">
	<div style="box-shadow: 2px 1px 4px #888888; text-align:center; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	<span style="text-align:center; color:red; font-weight:bold" colspan="2">
	Register a new account
	</span>
	</div>
	<br>
	<tr>
	<td style="text-align:right;">
	your username:
	</td>
	<td>
	<input type="text" name="username" style="border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">
	</td>
	</tr>
	
	<tr>   
	<td style="text-align:right;">
	password:
	</td>
	<td>
	<input type="password" name="password" style="border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:right;">
	confirm password:
	</td>
	<td>
	<input type="password" name="confirm" style="border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:right;">
	e-mail:
	</td>
	<td>
	<input type="text" name="email" style="border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:center;" colspan="2">
	<input type="submit" value="Register" style="background:darkgrey">
	</td>
	</tr> 
	</form>
	<?
}
?>
</table>
	<br>
	<?

$layout->fuss();
