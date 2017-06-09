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
<b>Welcome to the Beast Community!</b>
</span>
<br>
<br>
<div class="textDiv">
<table>
<tr>
<td style="text-align:center;">
<?
    $image =  basename("img/Beast_Community_Logo.png"); // you can here put the image path dynamically 
    //echo $image;

if (isset($_SESSION['userid']) && $_SESSION['userid']) {
    ?>
    <a class="nohover" download="<? echo $image; ?>" href="img/beastlogo1.png" title="Beast Logo title">
    <img alt="logo" src="img/beastlogo120px.png" style="border:1px solid black; border-radius:3px;"> 
    <br>
    <span style="font-size:small; color:darkgrey;">500 by 500 pixel</span>
    <br>
    <input type="submit" value="download image" style="font-weight:normal;" class="inputSubmit">
    </a>
    <?
} else {
    ?>
    <img alt="logo" src="img/beastlogo120px.png" style="border:1px solid black; border-radius:3px;"> 
    <br>
    <span style="font-size:small; color:darkgrey;">Please log in to <br> <span style="color:red">download image</span></span>
    <?
}
?>
</td>

<td style="width:10px;">
</td>

<td>
We are a little community of gamer that play primarily CS:GO and PUBG. We own some steam community groups, What’s App group and a Discord server.
<br>
<br>
In addition, we own a cs:go server for surfing and more. You can find the join-link with IP at the menu 'Surf-Server'.
<br>
<br>
If you are interested in joining our community, just send to one of the qualified Admins a friendship request.
You can find all information at Contact.
</td>

</tr>
</table>
</div>

</div>



<br>
<span style="font-size:x-large">
<b>Latest news</b>
</span>
<br>
<br>
<div class="textDiv">
<?
$news = $db->getNews();
foreach ($news as $number => $row) {
    echo '<div class="innerDiv">';
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
<div class="textDiv">
Join Discord:
<br>
<br>
- <a target="_blank" href="https://discord.gg/5xzR6xk">https://discord.gg/5xzR6xk</a>
</div>
<br>
<div class="textDiv">
	Beast Community at steam:
	<br>
	<br>
	- <a target="_blank" href="http://steamcommunity.com/groups/BeastSurfCommunity">Beast Combat Surf Community steam group</a><br>
</div>
<!--
	<br>
<div class="textDiv">
	Subscribe our community at YouTube:
	<br>
	<br>
	- <a target="_blank" href="https://www.youtube.com/channel/UCYmbmz2irdhEJNEseuWZsQQ">YouTube Channel</a>
</div>
-->
<?
$layout->fuss();
