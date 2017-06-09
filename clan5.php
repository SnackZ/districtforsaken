<?
ini_set('error_log', 'error.log');
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');
require_once('MyMailer.php'); 
                   
// Input auffangen
$user = isset($_POST['user']) ? $_POST['user'] : '';
$passwort = isset($_POST['pw']) ? $_POST['pw'] : '';
$bLogout = isset($_POST['logout']);
// $forgotEmail = isset($_POST['forgotEmail'] ? $_POST['forgotEmail'] : '';
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
		$_SESSION['rank'] = $aUser['rank'];
		$loginMessage = 'Successfully logged in.';
	} else {                      
		$_SESSION['userid'] = '';
		$loginMessage = 'Login failed.';
	}
}

// forgot password

if (isset($_POST['forgotEmail'])) {
    $email = $_POST['forgotEmail'];
    $bEmail = $db->forgotCheckEmail($email);
    $bOk = true;
	if ($bOk && !$mailer->validateAddress($email)) {
	    $bOk = false;
	    $loginMessage = "The email address $email is not valid";
	}
    if ($bOk && !$bEmail) {
        $loginMessage = 'There could not be found a corresponding email address.';
        $bOk = false;
    }
    if ($bOk) {
        $pw = $db->getPassword($email);
        $stringPw = $pw['passwort'];
        $mailText = "Herewith we send your password for your BEAST community profile.
		
Please excuse that the feature for changing your password completely is not operational right now!

After logging in you will be able to use our forums at https://www.beast-community.com !

Your password: $stringPw

If you did not try to reset your password, another user must have tried to find out information about your profile.
Please contact support instantly for solving the issue!

This email has been generated automatically!
If you have not signed up at our website, ignore this content!"
        ;
        $subject = "Reset your password at the BEAST community";
        echo '<!--';        
		$mailError = $mailer->sendMail($subject, $mailText, $email);
		echo '-->';
        $loginMessage = 'The email verification succeeded, there has been sent an email to you.';
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
	if (!$db->checkEmail($newEmail)) {
	    $bOk = false;
	    $loginMessage = "the chosen email address '$newEmail' is already taken";
	}
	

	// los gehts, alles korrekt
	if ($bOk){
		$code = $db->makeCode();
		$db->createUser($newUser, $newPw, $newEmail, $code);
		// $userId = $db->getUserIdRegister($newUser);
		// $ID = $userId['id'];
		// @copy("userdata/0.jpg", "userdata/$ID.jpg");
		// copy("userdata/0.jpg", "userdata/$ID.jpg");
		$mailText = "Dear $newUser,
		
Please activate your account at the BEAST Community by clicking on the following link:
Click here: https://www.beast-community.com/clan5.php?code=$code

After verification you will be able to use our forums at https://www.beast-community.com !

This email has been generated automatically!
If you have not signed up at our website, ignore this content!"
        ;
        $subject = "Your verification for the BEAST Community";
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
    echo '<div class="textDiv">';
	echo '<div style="text-align:center; color:red">'.$loginMessage.'</div>'."\n";
	echo '</div>';
}

?>
<br>
<!--
<table>
<tr>
<td style="width:950px; text-align:right">
<form method="post">
<input type="submit" value="log out" class="inputSubmit" style="margin-top:5px;">
</form>
<a class="nohover" href="profil.php">
<input type="submit" value="profile settings" class="inputSubmit" style="margin-top:5px;">
</a>

<br><br>

</td>
</tr>
</table>
-->
<table style="margin:auto;">
<?                                                

if (isset($_SESSION['userid']) && $_SESSION['userid']) {
	?>  
	<div class="textDiv" style="text-align:left">
	<div style="text-align:left">
	<form method="post">
	<input type="submit" value="log out" name="logout" class="inputSubmit">
	</form>
	<a class="nohover" href="profil.php?profil=<?= $_SESSION['userid'] ?>">
	<input type="submit" value="profile settings" class="inputSubmit" style="margin-top:5px;">
	</a>
	<?
	echo '<span style="color:red;">Logged in as <b>'.$_SESSION['username'].'</b></span>';
	?>
	</div>
	<br>
	
	<!--
	<br>
	<div style="text-align:center; box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	Note: Please excuse, that the following features are not ready for use right now.
	</div>
	-->
	<?
	$showRank = $layout->getRankDisplay($_SESSION['rank']);
	echo 'Forum ranking:&nbsp'.$showRank;
	$downloadRank = $db->getDownloadRank($_SESSION['userid']);
    if ($downloadRank['download'] == 1) {
        ?>
        <br><br>
        Your account has gained permissions for downloading special contents on this website.<br>
        <span style="color:darkgrey; font-size:small;">Please do <span style="color:red;">not</span> tell anyone of this feature contained in our website.<br>
        This feature is <span style="color:red;">top secret</span> and you are not allowed to tell anyone of this permissions.<br>
        Only designated member do gain the access for this features.</span><br>
        Click <a href="downloads.php">here</a> to enter our private contents. <span style="font-size:small; color:darkgrey;">(<span style="color:#f442d7;">Iniuria</span> configurations)</span>
        <?
    }
	echo '</div>';
	echo '<br>';

	echo '<div class="textDiv">';
	$newestUser = $db->getNewestMember();
	foreach ($newestUser as $number => $row) {
	    echo '<span style="color:darkgrey">';
	    echo 'Welcome our newest verified member: &nbsp';
	    echo '</span>';
	    echo '<a href="profil.php?profil='.$row['id'].'">'.$row['username'].'</a>';
	}
	echo '<br>';
	echo '<span style="color:darkgrey;">Browse other </span>';
	echo '<a href="profil.php?profil='.$_SESSION['userid'].'">';
	echo 'profiles';
	echo '</a>';
	echo '</div>';
	echo '<br>';
	echo '<div class="textDiv">';
	echo '<div style="text-align:left;">';
	echo 'Forum permissions:';
	echo '<div style="font-size:small; color:darkgrey;">';
	echo '[<span style="color:Blue;">User</span>] create any new threads';
	echo '<br>';
	echo '[<span style="color:Blue;">User</span>] reply to any posts';
	echo '<br>';
	echo '[<span style="color:Blue;">User</span>] permissions for downloading the BEAST logo';
	echo '<br>';
	echo '[<span style="color:Lime;">Moderator</span>] create NEWS';
	echo '<br>';
	echo '[<span style="color:Lime;">Moderator</span>] delete NEWS';
	echo '<br>';
	echo '[<span style="color:Lime;">Moderator</span>] delete replies without associated replies';
	echo '<br>';
	echo '[<span style="color:Lime;">Moderator</span>] delete entire threads without associated posts';
	echo '<br>';
	echo '[<span style="color:Lime;">Moderator</span>] permissions for downloading the customized logos';
	echo '<br>';
	echo '[<span style="color:Lime;">Moderator</span>] Access the VIP forum contents';
	echo '<br>';
	echo '[<span style="color:#c300ff;">Administrator</span>] owner, this rank cannot be gained';
	echo '<br>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<br>';
	echo '<div class="textDiv">';
	echo 'How do I apply as moderator?';
	echo '<div style="font-size:small; color:darkgrey;">';
	echo 'You want to become VIP at our server?';
	echo '<br>';
	echo 'You want to become moderator at our forum?';
	echo '<br>';
	echo 'Then have a look at the <a href="clan4.php?wrz=32">application topic</a> - you will find a form you have to fill out and the qualified member will answer you instantly!';
	echo '</div>';
    echo '</div>';
	/*
	echo '<div style="text-align:center; box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
	echo 'Forum ranking: '.$forumRank;
	echo '</div>';
	*/

} else {
	?>

	
	<div style="text-align:center;" class="textDiv">
	<a class="nohover" href="javascript:klappen(1)">
	<div style="text-align:center; font-weight:bold" colspan="2">
	Forgot password?
	</div>
	</a>
	<div id="aufklappdiv1" style="display:none; width:500px;">
	<br>
	<form method="post">
	<span style="font-size:small; color:darkgrey;">
	Please enter the email address that is linked to your profile:
	</span>
	<br>
	<input type="text" value="" name="forgotEmail" style="width:400px;" class="inputText">
	<br>
	<input type="submit" value="send email" class="inputSubmit">
	</form>
	</div>
	</div>
	</form>
	<br>

	<form method="post">
	<div style="text-align:center;" class="textDiv">
	<span style="text-align:center; color:red; font-weight:bold" colspan="2">
	Log in with an existing account
	</span>
	</div>
	<br>
	<tr> 
	<td style="text-align:right; color:darkgrey;">
	Log in as user:
	</td>
	<td>
	<input type="text" name="user" class="inputText">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:right; color:darkgrey;">
	password:
	</td>
	<td>
	<input type="password" name="pw" class="inputText">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:center;" colspan="2">
	<input type="submit" value="Log in" class="inputSubmit" style="margin-top:5px;">
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
	<div style="text-align:center;" class="textDiv">
	<span style="text-align:center; color:red; font-weight:bold" colspan="2">
	Register a new account
	</span>
	</div>
	<br>
	<tr>
	<td style="text-align:right; color:darkgrey;">
	your username:
	</td>
	<td>
	<input type="text" name="username" class="inputText">
	</td>
	</tr>
	
	<tr>   
	<td style="text-align:right; color:darkgrey;">
	password:
	</td>
	<td>
	<input type="password" name="password" class="inputText">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:right; color:darkgrey;">
	confirm password:
	</td>
	<td>
	<input type="password" name="confirm" class="inputText">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:right; color:darkgrey;">
	e-mail:
	</td>
	<td>
	<input type="text" name="email" class="inputText">
	</td>
	</tr>
	
	<tr>
	<td style="text-align:center;" colspan="2">
	<input type="submit" value="Register" class="inputSubmit" style="margin-top:5px;">
	</td>
	</tr> 
	</form>
	<?
}
?>
</table>
	<?

$layout->fuss();
