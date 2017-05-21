<?
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(2);
?>

<tr>
<td colspan="2" style="padding:15px; text-align:left;">
	All information how you can contact us
	<br>
	<br>
	E-mail:<p style="font-weight:bold">districtforsaken@gmail.com</p>
	<a href="javascript:klappen(1);">
		<img src="img/PLUS.png" id="bildchen1">
		Add admins:
	</a>
	<br>
	<div id="aufklappdiv1" style="display:none; width:500px;">
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/localuser">SnackZ</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/KyuubaMain">Kyuuba</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/19312">Kingerst</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/Jompey">Jompey</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/profiles/76561198195718107">Hand of NOD</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/nicoonfire">Nicoonfire</a>
		<br>
	</div>
	<br>
	<a href="javascript:klappen(2);">
		<img src="img/PLUS.png" id="bildchen2">
		Add designated member:
	</a>
	<br>
	<div id="aufklappdiv2" style="display:none; width:500px;">
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/vzehhd">Ckatt</a>
		<br>
		- <a target=_blank href="http://steamcommunity.com/id/Meerox">Meerox</a>
		<br>
	</div>
	<br>
	
</td>
</tr>
</table>
<?
$layout->fuss();
