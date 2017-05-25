<?
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(2);
?>

<tr>
<td colspan="2" style="padding:15px; text-align:left;">
<!--    <span style="font-size:x-large;">
	<b>
	How you can contact us
	</b>
	</span>
	<br>
	<br>
-->
	<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	For issues with the registration or forum, please contact our support via email or post a comment on the forums!
	<br>
	<span style="color:darkgrey; font-size:small">
	    The support will answer within 24 hours.
	</span>
	</div>
	<br>
	<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
	E-mail:  	<span style="font-weight:bold">support@districtforsaken.de</span>
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
	<a href="javascript:klappen(1);">
		<img src="img/PLUS.png" id="bildchen1">
		Add admins:
	</a>
	<br>
	<div id="aufklappdiv1" style="display:none; width:500px;">
		<br>
		<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
		- <a target=_blank href="http://steamcommunity.com/id/localuser">SnackZ</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/KyuubaMain">Kyuuba</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/Kingerst">Kingerst</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/Jompey">Jompey</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/profiles/76561198195718107">Hand of NOD</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/nicoonfire">Nicoonfire</a>
		<br>
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
		<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">
		- <a target=_blank href="http://steamcommunity.com/id/vzehhd">Ckatt</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/Meerox">Meerox</a>
		<br>
		</div>
	</div>
	<br>
	
</td>
</tr>
</table>
<?
$layout->fuss();
