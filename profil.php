<?
ini_set('error_log', 'error.log');
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(6);

$db = new Datenbank();

$profilId = isset($_GET['profil']) ? (int) $_GET['profil'] : 0;
$searchProfil = isset($_POST['search']) ? $_POST['search'] : '';

if ($searchProfil) {
	$results = $db->profilSearch($searchProfil);
	foreach ($results as $key => $result) {
		$file = 'userdata/'.$result['id'].'.jpg';
		if (file_exists($file)) {
			$results[$key]['file'] = $file;
		} else {
			$results[$key]['file'] = 'userdata/0.jpg';
		}
	}
} else {
	$results = false;
}

$bShowProfile = false;

if ($profilId) {
	$bShowProfile = true;
} else {
	if (!(isset($_SESSION['userid']) && $_SESSION['userid'])) {
		$bShowProfile = false;
	} else {
		$bShowProfile = true;
		$profilId = $_SESSION['userid'];
	}
}

$country = isset($_POST['country']) ? $_POST['country'] : '';
if ($country) {
    $db->updateCountry($_SESSION['userid'], $country);
}

$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
if ($gender) {
    $db->updateGender($_SESSION['userid'], $gender);
}

$birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
if ($birthdate) {
    $db->updateBirthdate($_SESSION['userid'], $birthdate);
}

$steamLink = isset($_POST['steamLink']) ? $_POST['steamLink'] : '';
if ($steamLink) {
	if (substr($steamLink, 0, 4) != 'http') {
		$steamLink = 'http://'.$steamLink;
	}
	$db->updateSteamLink($_SESSION['userid'], $steamLink);
}

$beschreibung = isset($_POST['beschreibung']) ? $_POST['beschreibung'] : '';
if ($beschreibung && (isset($_SESSION['userid']) && $_SESSION['userid'])) {
	$db->updateProfilBeschreibung($_SESSION['userid'], $beschreibung);
}

$profil = $db->getUserZeile($profilId);

$totalNews = $db->getUserNEWS($profil['id']);

$totalPosts = $db->getUserPosts($profil['id']);

$totalThreads = $db->getUserThreads($profil['id']);

$profilRank = 1;

if (isset($profilId) && $profilId) {
	$profilRank = $layout->getRankDisplay($profil['rank']);
}

$warningMessage = '';
if (isset($_FILES['avatar']) && $tmpName = $_FILES['avatar']['tmp_name']) {
	$bOk = true;
	if (substr($_FILES['avatar']['name'], -4) != '.jpg') {
		$warningMessage = 'Please upload JPG datas only!';
		$bOk = false;
	}
	if ($bOk && $_FILES['avatar']['size'] > 50000) {
		$warningMessage = 'Please upload maximum file size of 50 KB!';
		$bOk = false;
	}
	if ($bOk) {
		move_uploaded_file($tmpName, 'userdata/'.$_SESSION['userid'].'.jpg');
	}
}

if (isset($_SESSION['userid']) && $profilId  == $_SESSION['userid']) {
	$file = 'userdata/'.$_SESSION['userid'].'.jpg';
	if (!is_file($file)) {
		$file = 'userdata/0.jpg';
	}
} else {
	$file = 'userdata/'.$profilId.'.jpg';
	if (!is_file($file)) {
		$file = 'userdata/0.jpg';
	}
}

$bEditForms = (isset($_SESSION['userid']) && $_SESSION['userid'] == $profilId);

// profilUsername in $data => eigenes profil oder anderer user

$newBeschreibung = $layout->replaceLineFeeds($profil['beschreibung']);

$joinDate = date('M jS Y', strtotime($profil['joindate']));

$data = array(
	'warningMessage' => $warningMessage,
	'file' => $file,
	'profilUsername' => $profil['username'],
	'steamLink' => $profil['link'],
	'userId' => $profil['id'],
	'joinDate' => $joinDate,
	'lastLogin' => $layout->dateWithoutSeconds($profil['lastlogin']),
	'userRank' => $profilRank,
	'bEditForms' => $bEditForms,
	'searchResults' => $results,
	'loggedIn' => $bShowProfile,
	'beschreibung' => $newBeschreibung,
	'editBeschreibung' => $profil['beschreibung'],
	'gender' => $profil['gender'],
	'birthdate' => $profil['birthdate'],
	'country' => $profil['country'],
	'totalPosts' => $totalPosts['anzahl'],
	'totalThreads' => $totalThreads['anzahl'],
	'totalNews' => $totalNews['anzahl'],
);

?>
<div style="font-size:medium;" class="textDiv">
Search for a username or a user ID ...
<br><br>
<table style="width:100$"><tr><td>
<div style="text-align:left;">
<form method="post">
<span style="color:darkgrey;">Search:</span>
<input type="text" name="search" class="inputText">
<input type="submit" value="browse" class="inputSubmit">
</form>
</div>
</td>
<?

if ($data['searchResults'] !== false) {
	if ($data['searchResults']) {
	    ?>
	    <td style="text-align:right">
	    <?
	    if (isset($_SESSION['userid']) && $_SESSION['userid']) {
	        ?>
	        <input type="submit" value="back to overview / log out" class="inputSubmit">
	        <?
	    } else {
	        ?>
	        <input type="submit" value="back to registration / log in" class="inputSubmit">
	        <?
	    }
	    ?>	    
	    </td>
	    <?
		foreach ($data['searchResults'] as $result) {
			?>
			</tr>
			<tr style="width:100%"><td colspan="2" style="width:1000px;">
			<br>
			<a class="nohover" href="profil.php?profil=<?= $result['id'] ?>">
			<div class="textDiv">
			<table>
			<tr>
			<td>
			<img style="border:1px solid black; border-radius:2px; width:50px; height:50px; vertical-align:middle;" src="<?= $result['file'] ?>">
			</td>
			<td>
			<span style="color:white; font-size:x-large; font-weight:bold;">
			<?= $result['username'] ?>
			</span>
			<br>
			<?= $rankResult = $layout->getRankDisplay($result['rank']); ?>
			</td>
			</tr>
			</table>
			</div>
			</a>
			</td>
			<?
		}
	} else {
		echo '<br>';
		echo '<span style="color:red">No search results could be found!</span>';
	}
}
	
?>
</tr>
</table>
</div>
<?

if ($data['loggedIn']) {
	if ($data['warningMessage']) {
	    echo '<br>';
		echo '<div style="color:red;" class="textDiv">';
		echo $data['warningMessage'];
		echo '</div>';
	}
	
	?>
	<br>
	<a class="nohover" href="clan5.php">
	<?
	if (isset($_SESSION['userid']) && $_SESSION['userid']) {
	?>
	<input type="submit" value="back to overview / log out" class="inputSubmit">
	<?
	} else {
	?>
	<input type="submit" value="back to registration / log in" class="inputSubmit">
	<?
	}
	?>
	</a>
	<br><br>
	
	<div class="textDiv">
	<table>
	<tr>
	<td>
	<img style="border:1px solid black; border-radius:3px; vertical-align:bottom; width:50px; height:50px;" src="<?= $data['file'] ?>">
	</td>
	<td>
	<span style="font-size:x-large; font-weight:bold;">
	<?= $data['profilUsername'] ?>
	</span>
	<span style="font-size:small; color:darkgrey;">User ID:
	<?= $data['userId'] ?></span>
	<br>
	<?= $data['userRank'] ?>
	</td>
	</tr>
	</table>
	</div>
	
	<?
	if ($data['bEditForms']) {
		?>
		<br>
		<div class="textDiv">
		Upload new profile picture: <span style="font-size:small; color:red">[JPG only and 50 KB maximum]</span>
		<br><span style="line-height:30px; font-size:small; color:darkgrey;">Picture looks best with 50 by 50 pixel</span><br>
		<form method="post" style="font-size:x-small;" enctype="multipart/form-data">
		<input style="vertical-align:middle; font-size:small; padding:-5px; height:25px; width:275px;" type="file" class="inputSubmit" name="avatar">
		<input type="submit" value="upload" class="inputSubmit">
		</form>
		<br>
		<span style="color:red">Note:</span> <span style="color:darkgrey;">You must refresh your browser tab / please type <span style="color:red; font-weight:bold;">'ctrl + f5'</span> for the profile picture being updated after upload!</span>
		</div>
		<?
	}
	?>
	
	<span style="font-size:small; color:darkgrey;">
	</span>
	</div>
	<br>
	<div class="textDiv" style="font-size:small; color:darkgrey;">
	<span style="font-size:x-large; font-weight:bold; color:white;">
	User's Information
	</span>
	<br><br><div class="textDiv3">

	Joined: 
	<span style="color:white; font-weight:bold;">
	<?= $data['joinDate'] ?>
	</span>
	<br>

	Last login: 
	<span style="color:white; font-weight:bold;">
	<?= $data['lastLogin'] ?>
	</span>
	<br>

	Total NEWS: 
	<span style="color:white; font-weight:bold;">
	<?= $data['totalNews'] ?>
	</span>
	<br>
	
	Total threads: 
	<span style="color:white; font-weight:bold;">
	<?= $data['totalThreads'] ?>
	</span>
	<br>
	
	Total posts: 
	<span style="color:white; font-weight:bold;">
	<?= $data['totalPosts'] ?>
	</span>
	</div>
	<div class="textDiv3">
	Country:
	<?

	if ($data['bEditForms']) {
	    ?>
	    <span style="color:white; font-weight:bold;">
	    <?= $data['country'] ?>
	    </span>
	    <form method="post">
	    <select name="country" style="background:black; color:white; border:1px solid #d0bb00; border-radius:2px; ">
	    <option value="Australia">Australia</option>
	    <option value="Austria">Austria</option>
	    <option value="Belgium">Belgium</option>
	    <option value="Canada">Canada</option>
	    <option value="China">China</option>
	    <option value="Denmark">Denmark</option>
	    <option value="France">France</option>
	    <option value="Germany">Germany</option>
	    <option value="Greece">Greece</option>
	    <option value="Iceland">Iceland</option>
	    <option value="India">India</option>
	    <option value="Italy">Italy</option>
	    <option value="Japan">Japan</option>
	    <option value="Netherlands">Netherlands</option>
	    <option value="Poland">Poland</option>
	    <option value="Portugal">Portugal</option>
	    <option value="Russia">Russia</option>
	    <option value="Spain">Spain</option>
	    <option value="Sweden">Sweden</option>
	    <option value="UK">UK</option>
	    <option value="US">US</option>
	    </select>
	    <input type="submit" value="update" class="inputSubmit">
	    </form>
	    <?
	} else {
	    echo '<span style="color:white; font-weight:bold;">'.$data['country'].'</span>';
	}
	?>
	</div>
	
	<div class="textDiv3">
	Age:
	<?
	if ($data['bEditForms']) {
	    ?>
	    <span style="color:white; font-weight:bold;">
	    <?= $data['birthdate'] ?>
	    </span>
	    <form method="post">
	    <select name="birthdate" style="background:black; color:white; border:1px solid #d0bb00; border-radius:2px; ">
	    <option value="Younger than 10">Younger than 10</option>
	    <option value="10">10</option>
	    <option value="11">11</option>
	    <option value="12">12</option>
	    <option value="13">13</option>
	    <option value="14">14</option>
	    <option value="15">15</option>
	    <option value="16">16</option>
	    <option value="17">17</option>
	    <option value="18">18</option>
	    <option value="19">19</option>
	    <option value="20">20</option>
	    <option value="21">21</option>
	    <option value="22">22</option>
	    <option value="23">23</option>
	    <option value="24">24</option>
	    <option value="25">25</option>
	    <option value="26">26</option>
	    <option value="27">27</option>
	    <option value="28">28</option>
	    <option value="29">29</option>
	    <option value="30">30</option>
	    <option value="Older than 30">Older than 30</option>
	    </select>
	    <input type="submit" value="update" class="inputSubmit">
	    </form>
	    <?
	} else {
	    echo '<span style="color:white; font-weight:bold;">'.$data['birthdate'].'</span>';
	}
	?>
	</div>
	
	<div class="textDiv3">
	Gender:
	<?
	if ($data['bEditForms']) {
	    ?>
	    <span style="color:white; font-weight:bold;">
	    <?= $data['gender'] ?>
	    </span>
	    <form method="post">
	    <select name="gender" style="background:black; color:white; border:1px solid #d0bb00; border-radius:2px; ">
	    <option value="Unknown">Unknown</option>
	    <option value="Female">Female</option>
	    <option value="Male">Male</option>
	    <option value="Inhuman">Inhuman</option>
	    </select>
	    <input type="submit" value="update" class="inputSubmit">
	    </form>
	    <?
	} else {
	    echo '<span style="color:white; font-weight:bold;">'.$data['gender'].'</span>';
	}
	?>
	</div>
	
	<?
	if ($data['bEditForms']) {
		?>
		<div class="textDiv3">
		Your steamcommunity profile link
		<button style="" class="inputSubmit" onclick="klappen(1);">
		update
		</button>
		<?
	} else {
		?>
		<div class="textDiv3">
		<?= $profil['username'] ?>'s steamcommunity profile link
		<?
	}
	?>
	<br>
	<span style="font-size:medium;">
	<?
	if ($data['steamLink']) {
		echo '<a href="'.$data['steamLink'].'" target="_blank">'.$data['steamLink'].'</a>';
	} else {
		echo 'No link has been set up yet!';
	}
	?>
	</span>
	<br>
	<div id="aufklappdiv1" style="display:none; width:500px;">
	<br>
	<form method="post">
	<input type="text" value="<?= $data['steamLink'] ?>" name="steamLink" style="width:400px;" class="inputText">
	<input type="submit" value="submit" class="inputSubmit">
	</form>
	</div>
	
	</div>
	
	</div>
	
	<br>
	<div class="textDiv">
	<?
	if ($data['bEditForms']) {
		?>
		<div class="innerDiv" style="vertical-align:middle;">
		Your customzied profile description / brain flow
		<button style="margin-bottom:5px;" class="inputSubmit" onclick="klappen(2);">
		update
		</button>
		</div>
		<?
	} else {
		?>
		<div class="innerDiv">
		<?= $profil['username'] ?>'s brain flow
		</div>
		<?
	}
	?> <br> <?
	if ($data['beschreibung']) {
		echo $data['beschreibung'];
	} else {
		$data['beschreibung'] = 'No information given!';
		echo $data['beschreibung'];
	}
	?>
	<br>
	<div id="aufklappdiv2" style="display:none; text-align:center;">
	<br>
	<form method="post">
	<textarea class="textArea" name="beschreibung"><?= $data['editBeschreibung'] ?></textarea>
	<br>
	<input type="submit" value="submit" class="inputSubmit">
	</form>
	</div>
	<?
}

$layout->fuss();
exit;
