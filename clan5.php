<?
session_start();
require_once('Layout.php');
require_once('MyMailer.php'); 
/* var_dump($_GET);  */  
                   
// Input auffangen
$user = $_GET['user'];
$passwort = $_GET['pw'];
$bLogout = isset($_GET['logout']);
// echo "user = $user, Passwort = $passwort, logout = $logout\n";
$loginMessage = '';

$db = new Datenbank();
$mailer = new MyMailer();

// confirm
$confirmCode = $_GET['code'];
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
		$loginMessage = 'Successfully logged in.';
	} else {                      
		$_SESSION['userid'] = '';
		$loginMessage = 'Login failed.';
	}
}

// register?

// Input auffangen

if (isset($_GET['username']) || isset($_GET['password']) || isset($_GET['email'])) { 
    
    $newUser = $_GET['username'];
    $newPw = $_GET['password'];
    $newEmail = $_GET['email'];
               
	$bOk = true;
	// check userangaben
	if (!$newUser){
		$bOk = false;
		$loginMessage = "Please specify a username";
	}
	if ($bOk && !$newPw){
		$bOk = false;
		$loginMessage = "Please specify a password";
	}                                  
	if ($bOk && !$newEmail){
		$bOk = false;
		$loginMessage = "Please indicate a valid email address";
	}
	if ($bOk && !$mailer->validateAddress($newEmail)) {
	    $bOk  = false;
	    $loginMessage = "The chosen email address '$newEmail' is not valid";
	}
	
	// los gehts, alles korrekt
	if ($bOk){
		$code = $db->makeCode();
		$db->createUser($newUser, $newPw, $newEmail, $code);
		$mailText = "Dear $newUser,
		
Please activate your account at District Forsaken by clicking on the following link:
Click here: http://localhost/districtforsaken/clan5.php?code=".$code."

After verification you will be able to use our forums at districtforsaken.de !

This email has been generated automatically!
If you havn't signed up at our website, ignore this content!"
        ;  
		$mailOk = $mailer->sendMail("Your verification for District Forsaken", $mailText, $newEmail);
		$loginMessage = 'User "'.$newUser.'" has been signed up. 
		    Please check your emails for the account verification.'
        ;
	}
}


$layout = new Layout();

$layout->kopf(5);

if ($loginMessage) {
	echo '<div style="text-align:center; color:red">'.$loginMessage.'</div>'."\n";
}

?>                 
<br>
<table style="margin:auto;">
<?                                                

if ($_SESSION['userid']) {
	?>  
	<form>
	<tr>                                            
	<td>
	<?
	echo '<span style="color:red;">Logged in as '.$_SESSION['username'].'</span>';
	?>  
	<input type="submit" value="Log out" name="logout">
	</td>
	</tr>
	</form>
	<?                                    
} else {
	?>
	<form>
	
	<tr> 
	<td style="text-align:center; color:red; font-weight:bold" colspan="2">
	Log in with an existing account
	</td>
	</tr>
	
	<tr> 
	<td style="text-align:right;">
	Log in as user 
	</td>
	<td>
	<input type="text" name="user">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:right;">
	password 
	</td>
	<td>
	<input type="password" name="pw">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:center;" colspan="2">
	<input type="submit" value="Log in">
	</td>
	</tr> 
	</form>
	
	<tr>
	<td>         
	&nbsp;
	</td>
	</tr>
	   
	<form>
	
	<tr> 
	<td style="text-align:center; color:red; font-weight:bold" colspan="2">
	Register a new account
	</td>
	</tr>
	
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
	<input type="password" name="password">
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
	
	<tr>
	<td style="text-align:center;" colspan="2">
	<input type="submit" value="Register">
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
