<?
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

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
We are a little community of gamer that play primarily CS:GO and PUBG. We own some steam community groups, What’s App group and a TeamSpeak3 server.
<br>
<br>
In addition, we own a cs:go server for surfing and more. You can find the join-link with IP at the menu 'Surf-Server'.
<br>
<br>
If you are interested in joining our community, just send to one of the qualified Admins a friendship request.
You can find all information at Contact.
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
