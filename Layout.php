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
		?><!DOCTYPE HTML>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="de"><head>
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
		<td style="width:180px;font-size:0;padding:0;">
		<img src="img/forsaken_clan2.jpg" style="width:180px; height:180px;border:none;margin:0;">
		</td>
		
		<td style="width:100% - 180px; background-color:yellow; color:black; text-align:center; padding:0;margin:0;">
		<div style="font-size:xx-large; font-weight:bold;">
		District Forsaken
		</div>
		<br>
		<div style="font-weight:bold; color:darkgrey;">
		<a href="ts3server://62.104.20.100?port=10068">Click to join TeamSpeak3!</a>
		</div>
		<br>
		<span style="color:brown">
		<?
		$navEntries = array(
			1 => 'Home', 
			2 => 'Contact', 
			4 => 'Forum'
			);
		foreach ($navEntries as $key => $navEntry) {
			if ($clanNr == $key) {
				echo '<span style="font-weight:bold; color:black;">';
				echo $navEntry;
				echo '</span>'."\n";
			} else {
			    if ($key == 1) {
			        echo '<a href="index.php">'.$navEntry.'</a>'."\n";
			    } else {
			        echo '<a href="clan'.$key.'.php">'.$navEntry.'</a>'."\n";
			    }
			}
			echo ' &nbsp; &nbsp; <span class="strich"> | &nbsp; </span>&nbsp; ';
		}
		// Letzten $nav Eintrag
		if ($_SESSION['userid']) {
			$lastEntry = 'Logout '.$_SESSION['username'];
		} else {
			$lastEntry = 'Login - Register';
		}
		if ($clanNr == 5) {
		    echo '<span style="font-weight:bold; color:black;">'.$lastEntry.'</span>'."\n";
		} else {
		    echo '<a href="clan5.php">'.$lastEntry.'</a>'."\n";
		}
		?>
		</span>
		</td>
		</tr>
		
		<tr>
		<td colspan="2" style="padding:15px; text-align:left;">
		<?
		
		if ($clanNr < 5) {
            if (!$_SESSION['userid']) {
                echo '<b>Please log in or sign up</b>';
                echo '<br>';
            }
        }
	}
	
	
	function fuss() {
		?>
		</td>
		</tr>
		</table>
		<script src="img/features.js" type="text/javascript"></script>
		</body>
		</html>
		<?
	}
	
	
}
