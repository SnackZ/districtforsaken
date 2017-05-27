<?
require_once 'Datenbank.php';

class Layout {
    
    public function replaceLineFeeds($str) {
        $str = str_replace("\n", "<br>", $str);
        return $str;
    }
    
    public function dateWithoutSeconds($sDate) {
        $sDate = date('M jS Y H:i', strtotime($sDate));
        return $sDate;
    }
    
	function kopf($clanNr) {
	    /*
	    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'];
	    if (!$https) {
	        header("Location:https://www.districtforsaken.de/", true, 301);
	    }
	    */
		?><!DOCTYPE HTML>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="de">
		<head>
		<meta charset="UTF-8">
		<title>District Forsaken</title>
		<link rel="icon" href="img/forsaken_clan32p.jpg" sizes="32x32" />
		<link rel="stylesheet" href="img/styles.css" type="text/css" media="all" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		</head>
		
		<body>
		<br>
		<table class="centertable">
		<tr>
		<td style="width:50px;font-size:0;padding:0;">
		<img src="img/forsaken_clan2.jpg" style="border-radius:15px 0px 0px 0px; width:50px; height:50px;border:none;margin:0;">
		</td>
		
		<td style="border-radius:0px 15px 0px 0px; width:100% - 50px; height:50px; background:black; color:black; text-align:center; padding:0;margin:0;">
		<!--
		<div style="font-size:xx-large; font-weight:bold;">
		District Forsaken
		</div>
		<div style="font-weight:bold; color:darkgrey;">
		<a href="ts3server://62.104.20.100?port=10068">Click to join TeamSpeak3!</a>
		</div>
		-->
		<span style="color:brown">
		<?
		$navEntries = array(
			1 => 'Home', 
			2 => 'Contact',
			3 => 'Surf-Server',
			4 => 'Forum'
		);
		if (isset($_SESSION['userid']) && $_SESSION['userid']) {
			$navEntries[5] = 'Logout '.$_SESSION['username'];
		} else {
			$navEntries[5] = 'Login - Register';
		}
		$navUrls = array(
		    1 => 'index.php',
		    2 => 'clan2.php',
		    3 => 'clan3.php',
		    4 => 'clan4.php',
		    5 => 'clan5.php'
		);
		?>
		<table style="margin:auto; width:750px;">
		<tr>
		<?
		foreach ($navEntries as $key => $navEntry) {
			if ($clanNr == $key) {
				$bgcolor = '#242222';
				$onMouse = '';
			} else {
			    $bgcolor = 'black';
			    $onMouse = ' onmouseover="javascript:menuin('.$key.');" 
                onmouseout="javascript:menuout('.$key.');"'
                ;
			}
			
			// jetzt kommt output  
			echo '<td style="background-color:'.$bgcolor.'; width:150px; height:45px;"' 
                .$onMouse
                .' id="menuTd'.$key.'">'
            ;
			echo '<a href="'.$navUrls[$key].'">'.$navEntry.'</a>';
			echo '</td>';
		}   
		?>
		</tr>
		</table>
		</span>
		</td>
		</tr>
		
		<tr>
		<td colspan="2" style="padding:15px; text-align:left;">
		<?
		
		if ($clanNr < 5) {
		    if (!isset($_SESSION['userid']) || !$_SESSION['userid']) {
		        echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
                echo '<b><span style="color:crimson">Please log in or sign up for using our Forum</span></b>';
                echo '<br>';
                echo '</div>';
            }
        }
	}
	
	
	function fuss() {
		?>
		
		<tr>
		<td colspan="2" style="text-align:center;">
		<span style="font-size:x-small">
		- by using our website, you consent to our use of cookies - if you do not agree please contact support -
		</span>
		</td>
		</tr>
		
		<tr>
		<td colspan="2" style="text-align:center;">
		<a style="font-size:x-small" href="index.php">Home</a>
		<a style="font-size:x-small" href="clan4.php">Forum</a>
		<a style="font-size:x-small" href="clan2.php">Impressum</a>
		<a style="font-size:x-small" href="clan2.php">Contact</a>
		</td>
		</tr>
		</table>
		<script src="img/features.js" type="text/javascript"></script>
		</body>
		</html>
		<?
	}
	
	
}
