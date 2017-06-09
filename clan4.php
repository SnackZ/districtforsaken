<?
ini_set('error_log', 'error.log');
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(4);

$db = new Datenbank();
// var_dump($_POST);
if (isset($_SESSION['userid']) && $_SESSION['userid']) {
    
    $commentary = isset($_POST['comment']) ? $_POST['comment'] : '';
    $wurzelId = isset($_GET['wrz']) ? (int) $_GET['wrz'] : 0;
    $parentId = isset($_POST['parent']) ? (int) $_POST['parent'] : 0;
    $kommId = isset($_POST['kommId']) ? (int) $_POST['kommId'] : 0;
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    
    if (($_SESSION['rank'] == 2 || $_SESSION['rank'] == 3) && $kommId) {
        $db->deleteKomm($kommId);
    }
    
    if ($wurzelId && !$parentId) {
        $parentId = $wurzelId;
    }
    if ($commentary && $parentId) {
        $db->createCommentary($commentary, $parentId);
    }
    
    ?>
    <style>
    input[type=text]:focus {
        border:1px solid green;
        width:300px;
    }
    </style>
    <div style="text-align:right;">
    <form method="post">
    <span style="color:darkgrey;">Search:</span>
    <input type="text" name="search" class="inputText">
    <input type="submit" value="browse" class="inputSubmit">
    </form>
    </div>

    <?
    if ($search) { 
        $aRows = $db->dbSearch($search);
        echo '<br>';
        echo '<div class="textDiv">';
        echo 'You are searching for: "<span style="color:red;">'.$search.'</span>"';
        echo '</div>';
        echo '<br>';
        foreach ($aRows as $row) {
            $threadId = $db->getThreadId($row['id']);
            $text = str_ireplace($search, '<span style="color:red;">'.$search.'</span>', $row['kommentar']);
            $datum = $layout->dateWithoutSeconds($row['datum']);
            $text = $layout->replaceLineFeeds($text);
            echo '<div class="textDiv">';
            echo '<div style="border:1px solid black; border-radius:3px;"><table><tr>';
            echo '<td>';
            echo '<img src="userdata/'.$row['userid'].'.jpg" style="border:1px solid black; border-radius:2px; vertical-align:middle; width:50px; height:50px;"> ';
            echo '</td><td>';
            echo '<a href="profil.php?profil='.$row['userid'].'">';
            echo '<span style="font-size:x-large; color:white; font-weight:bold;">';
            echo $row['username'];
            echo '</span>';
            echo '</a>';
            echo '<span style="color:darkgrey;">';
            echo ' wrote on '.$datum.'</span><br>';
            $showRank = $layout->getRankDisplay($row['rank']);
            echo $showRank;
            echo '</td></tr></table></div>';
            echo '<br>';
            echo $text;
            echo '<br>';
            echo '<a style="font-size:small;" href="clan4.php?wrz='.$threadId.'">read whole thread</a>';
            echo '</div>';
            echo '<br>';
        }
        echo '<a href="clan4.php">Back to our forum</a>';
    } else {
        if ($wurzelId) {
            $wurzeln = $db->getWurzeln();
            if (in_array($wurzelId, $wurzeln)) {
                // Ansicht 2 VIP bereich
                if ($wurzelId == 2) {
                    if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 3) {
                        echo '<table style="width:100%;"><tr><td style="font-size:x-large; font-weight:bold; width:50%;">';
                        echo 'Access <span style="color:lime;">VIP</span> only';
                        echo '</b>';
                        echo '</span>';
                        echo '</td>';
                        echo '<td style="width:50%; text-align:right;">';
                        echo '<a class="nohover" href="clan4.php"><input type="submit" value="back to all topics" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                        echo '</td>';
                        echo '</tr>';
                        echo '</table>';
                        echo '<br>';                        
                        echo '<a class="nohover" href="javascript:displaythread(\'\', '.$wurzelId.');">';
                        echo '<div style="color:red;" class="textDiv">';
                        echo 'Create a new thread!';
                        echo '</div>';
                        echo '</a>';
                        echo '<br>';
                        echo '<div id="threadArea" style="display:none">';
                        echo '<form method="post" action="clan4.php?wrz='.$wurzelId.'" style="text-align:center;">';
                        echo '<textarea name="comment" class="textArea">';
                        echo '</textarea>';
                        echo '<br>';
                        echo '<input type="submit" value="Create thread" class="inputSubmit">';
                        echo '</form>';
                        echo '</div>';
                        echo '<br>';                        
                        $aKommentare = $db->getFirstChildren($wurzelId);
                        displayWurzeln($aKommentare, $layout, true);
                    } else {
                        echo '<br><div style="color:red;" class="textDiv">';
                        echo 'Access denied';
                        echo '</div><br><div class="textDiv">';
                        echo 'You do not have the permissions to view this content of our website.<br><span style="font-size:small; color:darkgrey;">';
                        echo 'This content can only be accessed by forum <span style="color:lime;">moderator</span> ranked user.<br>';
                        echo 'If there are any issues you have questions about or anything else, please post another thread or contact support!';
                        echo '</span></div><br>';
                        echo '<a class="nohover" href="clan4.php"><input type="submit" value="back to all topics" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                    }
                } else {
                    // ansicht 2 wurzeln der ebene 1 bzw. threads
                    echo '<table style="width:100%">';
                    echo '<tr>';
                    echo '<td style="width:50%; text-align:left;">';
                    echo '<span style="font-size:x-large;">';
                    echo '<b>';
                    
                    $topicContents = array(
                        1 => 'NEWS',
                        31 => 'Beast Combat Surf Community server',
                        32 => 'Application forms',
                        19 => 'Introduce yourself',
                        18 => 'CS:GO',
                        23 => 'PUBG',
                        17 => 'Other topics'
                        );
                    
                    foreach ($topicContents as $key => $topicContent) {
                        if ($wurzelId == $key) {
                            echo $topicContents[$key];
                        }
                    }
                    echo '</b>';
                    echo '</span>';
                    echo '</td>';
                    echo '<td style="width:50%; text-align:right;">';
                    echo '<a class="nohover" href="clan4.php"><input type="submit" value="back to all topics" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                    echo '</td>';
                    echo '</tr>';
                    echo '</table>';
                    echo '<br>';
                    if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 3 || $wurzelId != 1) {
                        echo '<a class="nohover" href="javascript:displaythread(\'\', '.$wurzelId.');">';
                        echo '<div style="color:red;" class="textDiv">';
                        echo 'Create a new thread!';
                        echo '</div>';
                        echo '</a>';
                        echo '<br>';
                        echo '<div id="threadArea" style="display:none">';
                        echo '<form method="post" action="clan4.php?wrz='.$wurzelId.'" style="text-align:center;">';
                        echo '<textarea name="comment" class="textArea">';
                        echo '</textarea>';
                        echo '<br>';
                        echo '<input type="submit" value="Create thread" class="inputSubmit">';
                        echo '</form>';
                        echo '</div>';
                        echo '<br>';
                    } 
                    $aKommentare = $db->getFirstChildren($wurzelId);
                    displayWurzeln($aKommentare, $layout, true);
                }
    
                
            } else {
                // ansicht 3 baum mit reply möglichkeit
                /*
                ?>
                <br>
                <div style="text-align:right;">
                <a href="clan4.php">Back to all topics</a>
                </div>
                <?
                */
                $aBeitrag = $db->getBeitrag($wurzelId);
                // $wurzeln = $db->getWurzeln();
                if ($aBeitrag['parent'] != 2) {
                    $totalPosts = $db->getUserPosts($aBeitrag['userid']);
                    $totalThreads = $db->getUserThreads($aBeitrag['userid']);
                    echo '<table style="width:100%">';
                    echo '<tr>';
                    echo '<td style="text-align:left; width:50%">';
                    echo '<a class="nohover" href="clan4.php?wrz='.$aBeitrag['parent'].'"><input type="submit" value="back to threads" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                    echo '</td>';
                    echo '<td style="text-align:right; width:50%">';
                    echo '<a class="nohover" href="clan4.php"><input type="submit" value="back to all topics" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                    echo '</td>';
                    echo '</tr>';
                    echo '</table>';
                    echo "<br>\n";
                    echo '<div class="textDiv">';
                    echo '<div style="border:1px solid black; border-radius:3px;"><table style="width:100%;">';
                    echo '<tr>';
                    echo '<td style="width:50px;">';
                    $kommBr = $layout->replaceLineFeeds($aBeitrag['kommentar']);
                    echo '<img src="userdata/'.$aBeitrag['userid'].'.jpg" style="border:1px solid black; border-radius:2px; vertical-align:middle; width:50px; height:50px;"> ';
                    echo '</td><td style="width:150px;">';
                    echo '<span style="color:darkgrey;">';
                    echo '<a href="profil.php?profil='.$aBeitrag['userid'].'">';
                    echo '<span style="font-weight:bold; color:white; font-size:x-large;">'.$aBeitrag['username'].'</span>';
                    echo '</a>';
                    $datum = $layout->dateWithoutSeconds($aBeitrag['datum']);
                    $showRank = $layout->getRankDisplay($aBeitrag['rank']);
                    echo '<br>';
                    echo $showRank;
                    echo '</span>';
                    echo '</td><td style="color:darkgrey; font-size:small; text-align:left;">';
                    echo 'Total posts: '.$totalThreads['anzahl'].'<br>Overall posts: '.$totalPosts['anzahl'].'<br>Origin: '.$aBeitrag['country'];
                    echo '</td><td style="color:darkgrey; text-align:right; width:175px;">';
                    echo $datum;
                    echo '</td></tr>';
                    echo '</table></div>';
                    echo '<br>';
                    echo '<div id="komm'.$aBeitrag['id'].'">'.$kommBr.'</div>';
                    echo '<a class="nohover" href="javascript:reply('.$aBeitrag['id'].');">
                    <input type="submit" value="reply" class="inputSubmit" style="font-weight:normal; margin-top:5px;">
                    </a>'."\n";
                    echo '</div>';
                    echo '<div id="threadArea" style="display:none" class="textArea">';
                    echo '<form method="post" action="clan4.php?wrz='.$wurzelId.'" style="text-align:center;">';
                    echo '<textarea name="comment" class="textArea">';
                    echo '</textarea>';
                    echo '<br>';
                    echo '<input type="submit" value="Create thread" class="inputSubmit">';
                    echo '</form>';
                    echo '</div>';
                    echo '<br>';
                    
                    // Children anzeigen
                    $aChildren = $db->getChildren($wurzelId);
                    displayChildren($aChildren, 20, $layout);
                } else {
                    if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 3) {
                        $totalPosts = $db->getUserPosts($aBeitrag['userid']);
                        $totalThreads = $db->getUserThreads($aBeitrag['userid']);
                        echo '<table style="width:100%">';
                        echo '<tr>';
                        echo '<td style="text-align:left; width:50%">';
                        echo '<a class="nohover" href="clan4.php?wrz='.$aBeitrag['parent'].'"><input type="submit" value="back to threads" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                        echo '</td>';
                        echo '<td style="text-align:right; width:50%">';
                        echo '<a class="nohover" href="clan4.php"><input type="submit" value="back to all topics" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                        echo '</td>';
                        echo '</tr>';
                        echo '</table>';
                        echo "<br>\n";
                        echo '<div class="textDiv">';
                        echo '<div style="border:1px solid black; border-radius:3px;"><table style="width:100%;">';
                        echo '<tr>';
                        echo '<td style="width:50px;">';
                        $kommBr = $layout->replaceLineFeeds($aBeitrag['kommentar']);
                        echo '<img src="userdata/'.$aBeitrag['userid'].'.jpg" style="border:1px solid black; border-radius:2px; vertical-align:middle; width:50px; height:50px;"> ';
                        echo '</td><td style="width:150px;">';
                        echo '<span style="color:darkgrey;">';
                        echo '<a href="profil.php?profil='.$aBeitrag['userid'].'">';
                        echo '<span style="font-weight:bold; color:white; font-size:x-large;">'.$aBeitrag['username'].'</span>';
                        echo '</a>';
                        $datum = $layout->dateWithoutSeconds($aBeitrag['datum']);
                        $showRank = $layout->getRankDisplay($aBeitrag['rank']);
                        echo '<br>';
                        echo $showRank;
                        echo '</span>';
                        echo '</td><td style="color:darkgrey; font-size:small; text-align:left;">';
                        echo 'Total posts: '.$totalThreads['anzahl'].'<br>Overall posts: '.$totalPosts['anzahl'].'<br>Origin: '.$aBeitrag['country'];
                        echo '</td><td style="color:darkgrey; text-align:right; width:175px;">';
                        echo $datum;
                        echo '</td></tr>';
                        echo '</table></div>';
                        echo '<br>';
                        echo '<div id="komm'.$aBeitrag['id'].'">'.$kommBr.'</div>';
                        echo '<a class="nohover" href="javascript:reply('.$aBeitrag['id'].');">
                        <input type="submit" value="reply" class="inputSubmit" style="font-weight:normal; margin-top:5px;">
                        </a>'."\n";
                        echo '</div>';
                        echo '<div id="threadArea" style="display:none" class="textArea">';
                        echo '<form method="post" action="clan4.php?wrz='.$wurzelId.'" style="text-align:center;">';
                        echo '<textarea name="comment" class="textArea">';
                        echo '</textarea>';
                        echo '<br>';
                        echo '<input type="submit" value="Create thread" class="inputSubmit">';
                        echo '</form>';
                        echo '</div>';
                        echo '<br>';
                        
                        // Children anzeigen
                        $aChildren = $db->getChildren($wurzelId);
                        displayChildren($aChildren, 20, $layout);
                    } else {
                        echo '<br><div style="color:red;" class="textDiv">';
                         echo 'Access denied';
                         echo '</div><br><div class="textDiv">';
                         echo 'You do not have the permissions to view this content of our website.<br><span style="font-size:small; color:darkgrey;">';
                         echo 'This content can only be accessed by forum <span style="color:lime;">moderator</span> ranked user.<br>';
                         echo 'If there are any issues you have questions about or anything else, please post another thread or contact support!';
                         echo '</span></div><br>';
                         echo '<a class="nohover" href="clan4.php"><input type="submit" value="back to all topics" class="inputSubmit" style="font-weight:normal; font-size:medium; margin-top:5px;"></a>';
                     }
                }
            }
        } else {
            // ansicht 1, Wurzeln mit parent = 0
            $latestComms = $db->getLatestComms();
            
            $aKommentare = $db->getFirstChildren(0);
            ?>
            <span style="font-size:x-large;">
            <b>
            All primary topics
            </b>
            </span>
            <br>
            <?
            displayWurzeln($aKommentare, $layout, false);
        }   
        if (isset($_SESSION['userid']) && $_SESSION['userid']) {
            echo '<div id="answerArea" style="display:none">';
            echo '<div id="parentComment">';
            echo 'You are replying to: <br>';
            echo '';
            echo '</div>';
            echo '<form method="post" action="clan4.php?wrz='.$wurzelId.'" style="text-align:center;">';
            echo '<input type="hidden" id="hiddenParent" name="parent" value="">';
            echo '<input type="hidden" id="hiddenWurzel" name="wrz" value="'.$wurzelId.'">';
            echo '<textarea name="comment" class="textArea">';
            echo '</textarea>';
            echo '<br>';
            echo '<input type="submit" value="Enter comment" class="inputSubmit">';
            echo '</form>';
            echo '</div>';
        }   
        ?>
        <form method="post" id="kommDelete">
        <input type="hidden" id="kommId" name="kommId">
        </form>
        <?
    }
} else {
    echo '<br>';
    echo '<div class="textDiv">';
    echo 'You have to be logged in for accessing our forums.';
    echo '<span style="color:darkgrey; font-size:small">';
    echo '<br>';
    echo 'If you are not signed up yet, you can register a new account at "Login - Register" at the top menu.';
    echo '<br>';
    echo 'By using our forums, you can leave comments about our community improvement overmore about any cs:go events.';
    echo '<br>';
    echo 'Furthermore, you can post your application as administrator at our combat surf server.';
    echo '</span>';
    echo '</div>';
    echo '<br>';
    echo '<div class="textDiv">';
    echo 'Wanna apply as admin on our CS:GO server?';
    echo '<span style="color:darkgrey; font-size:small">';
    echo '<br>';
    echo 'You can find the application form on our forum at the "application" topic.';
    echo '<br>';
    echo 'Please sign up for accessing the forum.';
    echo '</span>';
    echo '</div>';
    echo '<br>';
    echo '<div class="textDiv">';
    echo 'Problems with the registration?';
    echo '<span style="color:darkgrey; font-size:small">';
    echo '<br>';
    echo 'Please make sure, having indicated your correct email address.';
    echo '<br>';
    echo 'Please check your spam folder.';
    echo '<br>';
    echo 'If you have not received an email despite a correct registration, please contact the support.';
    echo '</span>';
    echo '</div>';
    
}
$layout->fuss();
exit;

/*=====================================================================
 * Funktionen nur für diese Seite
 *====================================================================*/
function displayWurzeln($aKommentare, $layout, $bShowUser) {
    $db = new Datenbank();
    $wurzeln = $db->getWurzeln();
    foreach ($aKommentare as $key => $komm) {
        // echo '<div class="textDiv">';
        // echo '<div>';
        $maxLength = 200;
        if (strlen($komm['kommentar']) > $maxLength) {
            $kommText = substr($komm['kommentar'], 0, $maxLength).'... <span style="color:darkgrey; font-size:small;">[more]</span>';
        } else {
            $kommText = $komm['kommentar'];
        }
        if (substr_count($kommText, "\n") >= 3) {
            $p1 = strpos($kommText, "\n");
            $p2 = strpos($kommText, "\n", $p1 + 1);
            $p3 = strpos($kommText, "\n", $p2 + 1);
            $kommText = substr($kommText, 0, $p3).'... <span style="color:darkgrey; font-size:small;">[more]</span> ';
        }
        $kommText = $layout->replaceLineFeeds($kommText);
        
        if ($bShowUser) {
            $totalPosts = $db->getUserPosts($komm['userid']);
            $totalThreads = $db->getUserThreads($komm['userid']);
            echo '<div class="textDiv">';
            echo '<div>';
            echo '<div style="color:darkgrey;">';
            echo '<div style="border:1px solid black; border-radius:3px;">';
            echo '<table style="width:100%"><tr>';
            echo '<td style="width:50px;">';
            echo '<img src="userdata/'.$komm['userid'].'.jpg" style="border:1px solid black; border-radius:2px; vertical-align:middle; width:50px; height:50px;"> ';
            echo '</td>';
            echo '<td style="width:150px;">';
            echo '<span style="color:white; font-size:x-large; font-weight:bold">'.$komm['username'].'</span>';
            $datum = $layout->dateWithoutSeconds($komm['datum']);
            echo '<br>';
            $showRank = $layout->getRankDisplay($komm['rank']);
            echo $showRank;
            echo '</td><td style="font-size:small; text-align:left;">';
            echo 'Created threads: '.$totalThreads['anzahl'].'<br>Overall posts: '.$totalPosts['anzahl'].'<br>Origin: '.$komm['country'];
            echo '</td><td style="text-align:right; width:175px;">';
            echo $datum;
            echo '</td></tr>';
            echo '</table></div>';
            echo '<br>';
            echo '<a class="nohover" href="clan4.php?wrz='.$komm['id'].'">';
            echo '<div>';
            echo $kommText."<br>\n";
            echo '</div>';
            echo '</a>';
            echo '</div>'; 
        } else {
            /*
            if ($komm['id'] == 2) {
                if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 3) {
                    echo '<a class="nohover" href="clan4.php?wrz='.$komm['id'].'"><div class="textDiv">';
                    echo $kommText;
                    echo '</div></a>';
                } else {
                    echo '<div class="textDiv"><span style="color:#d0bb00;">'.$kommText.'</span></div>';
                }
            }      
            */
            if ($komm['id'] > 1) {
                $anzahlThreads = $db->getAnzahlTopicThreads($komm['id']);
                $latestThreadVerfasser = $db->getLatestThreadVerf($komm['id']);
                $datum = $layout->dateWithoutSeconds($latestThreadVerfasser['datum']);
                echo '<div class="textDiv">';
                echo '<div>';
                echo '<a class="nohover" href="clan4.php?wrz='.$komm['id'].'">';
                echo '<div><table style="width:100%"><tr><td style="width:100% - 150px">';
                echo $kommText;
                echo '</td><td style="font-size:small; color:darkgrey; text-align:center; width:100px">';
                echo 'Last thread by<br>'.$latestThreadVerfasser['username'].'<br><span style="font-size:x-small;">'.$datum.'</span>';
                echo '</td><td style="font-size:small; color:darkgrey; text-align:center; width:50px">';
                echo 'Existing threads: <span style="color:white;">'.$anzahlThreads['anzahl'].'</span>';
                echo '</td></tr></table></div>';
                echo '</a>';
            }
        }
        echo '</div>';
        if (!in_array($komm['id'], $wurzeln)) {
            echo '<div>';
            if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 3) {
                if ($komm['anzahlChildren'] == 0) {
                    echo '<a class="nohover" href="javascript:klappen('.$komm['id'].');">';
                    echo '<span style="font-size:small"><input type="submit" value="delete" class="inputSubmit" style="font-weight:normal; font-size:small; margin-top:5px;"></span>';
                    echo '</a>';
                    echo '<div id="aufklappdiv'.$komm['id'].'" style="text-align:center; display:none; margin:auto;">';
                    echo '<br>';
                    echo '<div class="textDiv" style="background:#350000;">';
                    echo '<span style="font-size:small">Do you really want to delete this post?</span>';
                    echo '<br>';
                    echo '<a style="font-size:small" href="javascript:deleteKomm('.$komm['id'].');">confirm</a>'."\n";
                    echo ' &nbsp &nbsp &nbsp - &nbsp &nbsp &nbsp ';
                    echo '<a style="font-size:small" href="javascript:klappen('.$komm['id'].');">cancel</a>'."\n";
                    echo '<br>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<span style="font-size:small">reply existing!</span>';
                }
            }
            echo '</div>';
        }
        echo '</div>';
        echo '<br>'."\n";
    }
}
 
 
function displayChildren($aChildren, $margin, $layout) {
    $db = new Datenbank();
    foreach ($aChildren as $child) {
        $totalPosts = $db->getUserPosts($child['userid']);
        $totalThreads = $db->getUserThreads($child['userid']);
        echo '<div style="margin-left:'.$margin.'px;">'."\n";
        echo '<div class="textDiv">';
        echo '<span style="font-size:medium; color:darkgrey;">';
        echo '<div style="border:1px solid black; border-radius:3px;"><table style="width:100%;">';
        echo '<tr>';
        echo '<td style="width:50px;">';
        echo '<img src="userdata/'.$child['userid'].'.jpg" style="border:1px solid black; border-radius:2px; vertical-align:middle; width:50px; height:50px;"> ';
        echo '</td><td style="width:150px;">';
        echo '<a href="profil.php?profil='.$child['userid'].'"><span style="color:white; font-size:x-large; font-weight:bold;">'.$child['username'].'</span></a>'."\n";
        $datum = $layout->dateWithoutSeconds($child['datum']);
        echo '<br>';
        $showRank = $layout->getRankDisplay($child['rank']);
        echo $showRank;
        echo '</td><td style="font-size:small; text-align:left;">';
        echo 'Created threads: '.$totalThreads['anzahl'].'<br>Overall posts: '.$totalPosts['anzahl'].'<br>Origin: '.$child['country'];
        echo '</td><td style="text-align:right; width:175px;">';
        echo $datum;
        echo '</td></tr>';
        echo '</table></div>';
        echo '</span>';
        echo '<br>';
        $kommBr = $layout->replaceLineFeeds($child['kommentar']);
        echo '<div id="komm'.$child['id'].'">'.$kommBr.'</div>';
        echo '<br>'."\n";
        echo '<a class="nohover" href="javascript:reply('.$child['id'].');"><input type="submit" value="reply" class="inputSubmit" style="font-weight:normal; margin-top:5px;"></a>'."\n";
        echo '<br>'."\n";
        if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 3) {
                if (!count($child['children'])) {
                    echo '<a class="nohover" href="javascript:klappen('.$child['id'].');">';
                    echo '<span style="font-size:small"><input type="submit" value="delete" class="inputSubmit" style="font-weight:normal; font-size:small; margin-top:5px;"></span>';
                    echo '</a>';
                    echo '<div id="aufklappdiv'.$child['id'].'" style="text-align:center; display:none; margin:auto;">';
                    echo '<br>';
                    echo '<div style="box-shadow: 2px 1px 4px #888888; background:#3a0000; border-radius:5px; border:1px solid black; padding:5px;">';
                    echo '<span style="font-size:small">Do you really want to delete this post?</span>';
                    echo '<br>';
                    echo '<a style="font-size:small" href="javascript:deleteKomm('.$child['id'].');">confirm</a>'."\n";
                    echo ' &nbsp &nbsp &nbsp - &nbsp &nbsp &nbsp ';
                    echo '<a style="font-size:small" href="javascript:klappen('.$child['id'].');">cancel</a>'."\n";
                    echo '<br>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<span style="font-size:small">Undeletable</span>';
                }
        }
        echo '</div>'."\n";
        echo '</div>';
        displayChildren($child['children'], $margin + 20, $layout)."\n";
    }
}
 