<?
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(4);

$db = new Datenbank();

$commentary = $_GET['comment'];
if ($commentary) {
	$db->createCommentary($commentary);
}

$aKommentare = $db->getKommentare();

if ($_SESSION['userid']) {
	echo '<form method="get" action="clan4.php" style="text-align:center;">';
	echo '<input type="text" name="comment" style="width:600px;">';
	echo '<br>';
	echo '<input type="submit" value="Enter comment" style="width:300px">';
	echo '</form>';
} else {
	echo '<span style="color:red">Please login to leave a comment!</span>';
}
?>
<br>
<br>



<?
foreach ($aKommentare as $key => $komm) {
	echo $komm['kommentar'];
	echo '<br>';
	echo '<span style="font-weight:bold">'.$komm['username'].'</span>';
	echo ', ';
	echo $komm['datum'];
	echo '<br><br>';

}
?>



<?
$layout->fuss();
