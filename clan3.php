<?
ini_set('error_log', 'error.log');
session_start();
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(3);
?>
<br>
<span style="font-size:x-large">
<b>
Our CS:GO surf server
</b>
<br>
</span>
<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	IP: 37.114.40.73:27015
	<br>
	<a href="steam://connect/37.114.40.73:27015">Click to join Beast Combat Surf Community cs:go server!</a>
</div>
<br>
<span style="font-size:x-large">
<b>
Beast Surf Community server Owner
</b>
</span>
<br>
<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
- <a target=_blank href="http://steamcommunity.com/id/Kingerst">Kingerst </a><span style="font-size:small; color:darkgrey;">[DE] [ENG]</span>
<br>
- <a target=_blank href="http://steamcommunity.com/id/Meerox">Meerox </a><span style="font-size:small; color:darkgrey;">[ENG]</span>
</div><br>
<span style="font-size:x-large">
<b>
Beast Surf Community server Admins
</b>
</span>
<br>
<br>
<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
- <a target=_blank href="http://steamcommunity.com/id/_Surprise_">Surprise </a><span style="font-size:small; color:darkgrey;">[DE] [ENG]</span>
<br>
- <a target=_blank href="http://steamcommunity.com/id/yarink">Yarink </a><span style="font-size:small; color:darkgrey;">[Dutch] [ENG]</span>
</div>
<br>
<span style="font-size:x-large">
<b>
Server Rules
</b>
</span>
<br>
<br>
	<a href="javascript:klappen(1);">
		<img src="img/PLUS.png" id="bildchen1">
		No Hacking or using of any 3rd party software!
	</a>
	<br>
	<div id="aufklappdiv1" style="display:none; width:500px;">
        <br>
        <div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
        - Cheater will be banned from our server instantly!
        <br>
        - The ban from our server is unremoveable!
        <br>
        </div>
    </div>
    <br>
	<a href="javascript:klappen(2);">
		<img src="img/PLUS.png" id="bildchen2">
		English only in voicechat!
	</a>
	<br>
	<div id="aufklappdiv2" style="display:none; width:100%">
	<br>
	<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
		- Please stay polite towards any user on our server!
		<br>
		- If you are talking obtrusively any other language, you will be kicked!
		<br>
		- Ensuing to a second kick you will be banned!
		<br>
	</div>
	</div>
	<br>
	<a href="javascript:klappen(3);">
		<img src="img/PLUS.png" id="bildchen3">
		About camping prohibitions!
	</a>
	<br>
	<div id="aufklappdiv3" style="display:none; width:100%">
	<br>
	<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
		- No telecamping at all!
		<br>
		- No jailcamping!
		<br>
	</div>
	</div>
	<br>
	<a href="javascript:klappen(4);">
		<img src="img/PLUS.png" id="bildchen4">
		Be courteous towards any admins of the server!
	</a>
	<br>
	<div id="aufklappdiv4" style="display:none; width:100%">
	<br>
	<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
		- Being annoying ensues a ban from our server!
		<br>
		- The same rules are valid for any else, not only the admins!
		<br>
	</div>
	</div>
	<br>

<?
$layout->fuss();
