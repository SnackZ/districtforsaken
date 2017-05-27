<?
ini_set('error_log', 'error.log');
// ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$db = new Datenbank();
$layout = new Layout();

$layout->kopf(1);
?>
<br>
<span style="font-size:x-large">
<b>Welcome to the District Forsaken!</b>
</span>
<br>
<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
We are a little community of gamer that play primarily CS:GO and PUBG. We own some steam community groups, What’s App group and a Discord server.
<br>
<br>
In addition, we own a cs:go server for surfing and more. You can find the join-link with IP at the menu 'Surf-Server'.
<br>
<br>
If you are interested in joining our community, just send to one of the qualified Admins a friendship request.
You can find all information at Contact.
</div>
<br>
<span style="font-size:x-large">
<b>Latest news</b>
</span>
<span>
- <a href="clan4.php">view all news</a> -
</span>
<br>
<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
<?
$news = $db->getNews();
foreach ($news as $number => $row) {
    echo '<div style="color:#b4b4b4; box-shadow: 2px 1px 4px #888888; background:#1f1f1f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
    $kommText = $layout->replaceLineFeeds($row['kommentar']);
    $maxLength = 100;
    if (strlen($kommText) > $maxLength) {
        $kommText = substr($kommText, 0, $maxLength).'... <a href="clan4.php?wrz=1"><span style="font-size:small;">[more]</span></a>';
    }
    if (substr_count($kommText, "<br>") >= 3) {
        $p1 = strpos($kommText, "<br>");
        $p2 = strpos($kommText, "<br>", $p1 + 1);
        $p3 = strpos($kommText, "<br>", $p2 + 1);
        $kommText = substr($kommText, 0, $p3).'... <a href="clan4.php?wrz=1"><span style="font-size:small;">[more]</span></a> ';
    }
    echo $kommText;
    echo '<br>';
    $datum = $layout->dateWithoutSeconds($row['datum']);
    echo '<span style="font-size:small; color:darkgrey;">'.$datum.'</span>';
    echo '</div>';
    echo "\n";
    if ($number < 2) {
        echo '<br>';
    }
}
?>
</div>
<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
Join Discord:
<br>
<br>
- <a target="_blank" href="https://discord.gg/5xzR6xk">https://discord.gg/5xzR6xk</a>
</div>
<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	Join District Forsaken at steam:
	<br>
	<br>
	- <a target="_blank" href="http://steamcommunity.com/groups/BeastSurfCommunity">Beast Combat Surf Community</a><br>
	<br>
	- <a target="_blank" href="http://steamcommunity.com/groups/districtoftheforsaken">District Forsaken Public</a>
	<br>
	<br>
	- <a target="_blank" href="http://steamcommunity.com/groups/DistrictForsaken">District Forsaken Private</a>
</div>
	<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	Subscribe our community at YouTube:
	<br>
	<br>
	- <a target="_blank" href="https://www.youtube.com/channel/UCYmbmz2irdhEJNEseuWZsQQ">YouTube Channel</a>
</div>
<?
$layout->fuss();
