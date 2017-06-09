<?
require_once 'Datenbank.php';

$db = new Datenbank();


class Layout {
    
    public function replaceLineFeeds($str) {
        $str = str_replace("\n", "<br>", $str);
        return $str;
    }
    
    public function dateWithoutSeconds($sDate) {
        $sDate = date('M jS Y H:i', strtotime($sDate));
        return $sDate;
    }
    
    public function getRankDisplay($rank) {
    	switch ($rank) {
    	case 3:
    		$showRank = '<span style="color:#c300ff">Administrator</span>';
    		break;
    		
    	case 2:
    		$showRank = '<span style="color:lime">Moderator</span>';
    		break;
    		
    	case 1:
    		$showRank = '<span style="color:blue">User</span>';
    		break;
    		
    	default:
    		echo '<span style="color:red;">This account has not been activated yet</span>';
    		exit(1);
    	}
    	return $showRank;
    }
    
	function kopf($clanNr) {
	    /*
	    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'];
	    if (!$https) {
	        header("Location:https://www.beast-community.com/", true, 301);
	    }
	    */
	    ?>
		
		<!DOCTYPE HTML>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="de">
		<head>
		<meta charset="UTF-8">
		<title>Beast Community</title>
		<link rel="icon" href="img/beastlogo1-32px.png" sizes="32x32" />
		<link rel="stylesheet" href="img/styles.css" type="text/css" media="all" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		
		<style>
/* Style The Dropdown Button */
.dropbtn {
    background-color: #242222;
    color:#d0bb00;
    height:30px;
    width:180px;
    font-size:large;
    font-weight:bold;
    border:none;
    cursor:pointer;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
    display: none;
    position: absolute;
    border:1px solid black;
    border-radius:5px 5px 5px 5px;
    background-color: #f9f9f9;
    min-width: 180px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
    color: black;
    padding: 5px 16px;
    text-decoration: none;
    display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {
    border:1px solid black;
    border-radius: 5px;
    background-color: #363636;
    color:lightgrey;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    display: block;
    width:180px;
    background-color:grey;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
    border:1px solid black;
    border-radius:5px 5px 5px 5px;
    background-color: dimgrey;
    width:180px;
}
</style>
		
		</head>
		
		<body>
		

		
		
		<br>
		
		
		<table class="centertable" style="table-layout:fixed;">
		<tr class="heightStyle" style="height:30px;">
		<td rowspan="3" style="border-right:thick double grey; width:300px; vertical-align:top; padding-right:5px; ">
		
		
		<?
		
		$db = new Datenbank();
		$layout = new Layout();
		
		$latestComms = $db->getLatestComms();
		
		?>
		<br>
		<span style="color:white; font-weight:bold; font-size:x-large;">
		Recent posts
		</span>
		<br>
		<br>
		<?
		
		foreach ($latestComms as $row) {
		    $wrzId = $db->getThreadId($row['id']);
		    echo '<a class="nohover" href="clan4.php?wrz='.$wrzId.'">';
		    echo '<div class="textDiv2" style="margin:5px;">';
		    $kommText = $layout->replaceLineFeeds($row['kommentar']);
		    $maxLength = 100;
		    if (strlen($kommText) > $maxLength) {
		        $kommText = substr($kommText, 0, $maxLength).'... <span style="font-size:small;">[more]</span>';
		    }
		    if (substr_count($kommText, "<br>") >= 2) {
		        $p1 = strpos($kommText, "<br>");
		        $p2 = strpos($kommText, "<br>", $p1 + 1);
		        $p3 = strpos($kommText, "<br>", $p2 + 1);
		        $kommText = substr($kommText, 0, $p2).'... <span style="font-size:small;">[more]</span>';
		    }
		    
		    $datum = $layout->dateWithoutSeconds($row['datum']);
		    echo '<div class="recentDiv">';
		    echo '<table style="width:300px;">';
		    echo '<tr>';
		    echo '<td>';
		    echo '<img src="userdata/'.$row['userid'].'.jpg" style="border:1px solid black; border-radius:2px; vertical-align:middle; width:50px; height:50px;">';
		    echo '</td><td style="text-align:left; width:250px;">';
		    echo '<a href="profil.php?profil='.$row['userid'].'"><span style="color:white; font-size:large; font-weight:bold;">';
		    echo $row['username'];
		    echo '</span></a>';
		    echo ', ';
		    echo '<span style="font-size:small;">'.$datum;
		    $showRank = $layout->getRankDisplay($row['rank']);
		    echo '<br>';
		    echo $showRank.'</span>';
		    echo '</td>';
		    echo '</tr>';
		    echo '</table>';
		    echo '</div>';
		    echo '<br>';
		    echo $kommText;
		    echo '</div>';
		    echo '</a>';
		    echo '<br>';
		}
		?>
		
		
		
		
		
		
		</td>		
		
		<!-- <td style="width:30px; height:30px; font-size:0;padding:0;">
		<img src="img/beastlogo1.png" style="border-radius:5px 0px 0px 0px; width:30px; height:30px;border:none;margin:0;">
		</td> -->
		

		
		<td style="vertical-align:top; border-radius:0px 15px 0px 0px; width:100%; height:30px; background:black; color:black; text-align:center; padding:0;margin:0;">
		<span style="color:brown">
		<?
		$navEntries = array(
			1 => 'Home', 
			2 => 'Contact',
			3 => 'Surf-Server',
			4 => 'Forum',
		);
		if (isset($_SESSION['userid']) && $_SESSION['userid']) {
			$navEntries[5] = 'Profile';
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
		<table class="tableStyle" style="margin:auto; border-radius:15px 15px 0px 0px;">
		<tr class="heightStyle">
		<?
		

		foreach ($navEntries as $key => $navEntry) {
		    if ($clanNr == $key) {
				$bgcolor = '#242222';
			} else {
			    $bgcolor = 'black';
                ;
			}
			if ($key == 1) {
			    ?>
			    <td style="width:25%; background-color:#242222;">
			    <div class="dropdown">
			    <button class="dropbtn">Home</button>
			    <div class="dropdown-content">
			    <a href="index.php">Home</a>
			    <a href="clan2.php">Contact</a>
			    </div>
			    </div> 
			    </td>
			    <?
			}
			if ($key == 3) {
			    ?>
			    <td style="width:25%; background-color:#242222;">
			    <div class="dropdown">
			    <button class="dropbtn">Server</button>
			    <div class="dropdown-content">
			    <a href="clan3.php">Combat Surf</a>
			    <a href="clan2drop2.php">Server rules</a>
			    <a href="clan2drop3.php">Map pool</a>
			    </div>
			    </div> 
			    </td>
			    <?
			}
			if ($key == 4) {
			    ?>
			    <td style="width:25%; background-color:#242222;">
			    <div class="dropdown">
			    <button class="dropbtn"><?= $navEntry ?></button>
			    <div class="dropdown-content">
			    <a href="clan4.php">Forum</a>
			    <a href="clan4.php?wrz=1">NEWS</a>
			    </div>
			    </div> 
			    </td>
			    <?
			}
			if ($key == 5) {
			    ?>
			    <td style="width:25%; background-color:#242222;">
			    <div class="dropdown">
			    <button class="dropbtn"><?= $navEntry ?></button>
			    <div class="dropdown-content">
			    <a href="clan5.php">
			    <?
			    if (isset($_SESSION['userid']) && $_SESSION['userid']) {
			        echo 'Overview';
			    } else {
			        echo 'Login / Register';
			    }
			    ?>
			    </a>
			    <a href="profil.php?profil=<?= $_SESSION['userid'] ?>">
			    <?
			    if (isset($_SESSION['userid']) && $_SESSION['userid']) {
			        echo 'Set up your profile';
			    } else {
			        echo 'Browse profiles';
			    }
			    ?>
			    </a>
			    </div>
			    </div> 
			    </td>
			    <?
			}
			
		}
		?>
		
		
		<?
		
		/*
		$onMouse = 'onmouseover="javascript:menuin('.$key.');" 
                onmouseout="javascript:menuout('.$key.');"'
                ;
                
                echo '<td style="background-color:'.$bgcolor.'; width:150px; height:30px;"' 
                .$onMouse
                .' id="menuTd'.$key.'">'
		
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
		*/
		?>
		
		</tr>
		
		</table>
		</span>
		</td>
		</tr>
		
		<tr>
		<td colspan="2" style="padding:15px; text-align:left; vertical-align:top;">
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
		<br>
		
		<a style="font-size:x-small" href="index.php">Home</a>
		<a style="font-size:x-small" href="clan4.php">Forum</a>
		<a style="font-size:x-small" href="clan2.php">Impressum</a>
		</td>
		</tr>
		</table>
		
		<script src="img/features.js" type="text/javascript"></script>
		</body>
		</html>
		<?
	}
	
	
}
