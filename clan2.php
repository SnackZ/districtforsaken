<?
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(2);
?>
<tr>
<td rowspan="2" colspan="2" style="padding:15px; text-align:left; vertical-align:top;">
<!--    <span style="font-size:x-large;">
	<b>
	How you can contact us
	</b>
	</span>
	<br>
	<br>
-->
	<div class="textDiv">
	For issues with the registration or forum, please contact our support via email or post a comment on the forums!
	<br>
	<span style="color:darkgrey; font-size:small">
	    The support will answer within 24 hours.
	</span>
	</div>
	<br>
	<div class="textDiv">
	Support email:  	<span style="font-weight:bold">support@beast-community.com</span>
	<br>
	<span style="color:darkgrey; font-size:small">
	    The support won't answer to spam mails, neither to repeatedly requests on the forum!
	    <br>
	    Please check out the forum first for finding a solution!
	    <br>
	    You may write german demands via email, we will answer within 24 hours in german.
	    <br>
	</span>
	</div>
	<br>
	<div class="textDiv">
	Impressum
	<br>
	<span style="color:darkgrey; font-size:small">
	    Für diese Seite ist verantwortlich:
	    <br>
	    Roland Schilffarth
	    <br>
	    Email: <b>roland@beast-community.com</b>
	    <br>
	</span>
	</div>
	<br>
	<a href="javascript:klappen(1);">
		<img src="img/PLUS.png" id="bildchen1">
		Add admins:
	</a>
	<br>
	<div id="aufklappdiv1" style="display:none; width:500px;">
		<br>
		<div class="textDiv">
		- <a href="profil.php?profil=23">SnackZ </a><span style="font-size:small; color:darkgrey;">[DE] [ENG]</span>
		<br>
		- <a href="profil.php?profil=99">Kingerst </a><span style="font-size:small; color:darkgrey;">[DE] [ENG]</span>
		<br>
		- <a href="profil.php?profil=117">Meerox </a><span style="font-size:small; color:darkgrey;">[ENG]</span>
		<br>
		<!--
		- <a target=_blank href="http://steamcommunity.com/id/Jompey">Jompey</a>
		<br>
		-->
		</div>
	</div>
	<br>
	<a href="javascript:klappen(2);">
		<img src="img/PLUS.png" id="bildchen2">
		Add designated member:
	</a>
	<br>
	<div id="aufklappdiv2" style="display:none; width:500px;">
		<br>
		<div class="textDiv">
		- <a href="profil.php?profil=83">Kyuuba </a><span style="font-size:small; color:darkgrey;">[DE]</span>
		<br>
		- <a href="profil.php?profil=119">Hand of NOD </a><span style="font-size:small; color:darkgrey;">[DE] [ENG]</span>
		<br>
		- <a href="profil.php?profil=">Nicoonfire </a><span style="font-size:small; color:darkgrey;">[DE]</span>
		<br>
		- <a href="profil.php?profil=">Ckatt </a><span style="font-size:small; color:darkgrey;">[ENG]</span>
		<br>
		- <a href="profil.php?profil=83">Yarink </a><span style="font-size:small; color:darkgrey;">[ENG] [Dutch]</span>
		<br>
		</div>
	</div>
	<br>
	
</td>
</tr>
<?
$layout->fuss();
