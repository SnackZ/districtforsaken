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
    
    if (isset($_SESSION['moderator']) && $_SESSION['moderator'] && $kommId) {
        $db->deleteKomm($kommId);
    }
    
    if ($wurzelId && !$parentId) {
        $parentId = $wurzelId;
    }
    if ($commentary && $parentId) {
        $db->createCommentary($commentary, $parentId);
    }
    
    ?>
    <div style="text-align:right;">
    <form method="post">
    Search: 
    <input type="text" name="search">
    <input type="submit" value="Go">
    </form>
    </div>
    <br>

    <?
    if($search) { 
        $aRows = $db->dbSearch($search);
        foreach ($aRows as $row) {
            $threadId = $db->getThreadId($row['id']);
            $text = str_replace($search, '<span style="color:red;">'.$search.'</span>', $row['kommentar']);
            $datum = $layout->dateWithoutSeconds($row['datum']);
            echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
            echo '<span style="font-size:small; color:darkgrey;">';
            echo $row['username'];
            echo ' ['.($row['moderator'] ? '<span style="color:lime">Moderator</span>' : '<span style="color:blue">User</span>').']';
            echo ', ';
            echo 'wrote on '.$datum.':<br>';
            echo '</span>';
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
                // ansicht 2 wurzeln der ebene 1 bzw. threads
                echo '<table style="width:100%">';
                echo '<tr>';
                echo '<td style="width:50%; text-align:left;">';
                echo '<span style="font-size:x-large;">';
                echo '<b>';
                echo 'Threads';
                echo '</b>';
                echo '</span>';
                echo '</td>';
                echo '<td style="width:50%; text-align:right;">';
                echo '<a href="clan4.php">Back to all topics</a>';
                echo '</td>';
                echo '</tr>';
                echo '</table>';
                echo '<br>';
                if ((isset($_SESSION['moderator']) && $_SESSION['moderator']) || ($wurzelId != 1)) {
                echo '<a class="nohover" href="javascript:displaythread(\'\', '.$wurzelId.');">';
                echo '<div style="color:red; box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
                echo 'Create a new thread!';
                echo '</div>';
                echo '</a>';
                echo '<br>';
                echo '<div id="threadArea" style="display:none">';
                echo '<form method="post" action="clan4.php?wrz='.$wurzelId.'" style="text-align:center;">';
                echo '<textarea name="comment" style="width:600px; height:100px; border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">';
                echo '</textarea>';
                echo '<br>';
                echo '<input type="submit" value="Create thread" style="background:darkgrey;">';
                echo '</form>';
                echo '</div>';
                echo '<br>';
                } 
                $aKommentare = $db->getFirstChildren($wurzelId);
                displayWurzeln($aKommentare, $layout, true);
    
                
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
                echo '<table style="width:100%">';
                echo '<tr>';
                echo '<td style="text-align:left; width:50%">';
                echo '<a href="clan4.php?wrz='.$aBeitrag['parent'].'">Back to threads</a>';
                echo '</td>';
                echo '<td style="text-align:right; width:50%">';
                echo '<a href="clan4.php">Back to all topics</a>';
                echo '</td>';
                echo '</tr>';
                echo '</table>';
                echo '<br>';
                echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
                $kommBr = $layout->replaceLineFeeds($aBeitrag['kommentar']);
                echo $kommBr."\n";
                echo '<br>';
                echo '<br>';
                echo '<span style="color:darkgrey;">';
                echo '<span style="font-weight:bold">'.$aBeitrag['username'].'</span>';
                echo ' ['.($aBeitrag['moderator'] ? '<span style="color:lime">Moderator</span>' : '<span style="color:blue">User</span>').']';
                echo ', ';
                $datum = $layout->dateWithoutSeconds($aBeitrag['datum']);
                echo $datum;
                echo '</span>';
                echo '<br>';
                echo '<a href="javascript:reply(\''.$aBeitrag['kommentar'].'\','.$wurzelId.');">reply</a>'."\n";
                echo '</div>';
                echo '<br>';
                
                // Children anzeigen
                $aChildren = $db->getChildren($wurzelId);
                displayChildren($aChildren, 20, $layout);
            }
        } else {
            // ansicht 1, Wurzeln mit parent = 0
            $latestComms = $db->getLatestComms();
            ?>
            <span style="font-size:x-large;">
            <b>
            Recent posts
            </b>
            </span>
            <br>
            <br>
            <?
            foreach ($latestComms as $row) {
                $wrzId = $db->getThreadId($row['id']);
                echo '<a class="nohover" href="clan4.php?wrz='.$wrzId.'">';
                echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
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
                echo $kommText;
                echo '<br>';
                $datum = $layout->dateWithoutSeconds($row['datum']);
                echo '<div style="font-size:small; color:darkgrey;">';
                echo $row['username'];
                echo ' ['.($row['moderator'] ? '<span style="color:lime">Moderator</span>' : '<span style="color:blue">User</span>').']';
                echo ', ';
                echo $datum;
                echo '</div>';
                echo '</div>';
                echo '</a>';
                echo '<br>';
            }
            
            
            
            $aKommentare = $db->getFirstChildren(0);
            ?>
            <span style="font-size:x-large;">
            <b>
            All primary topics
            </b>
            </span>
            <br>
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
            echo '<textarea name="comment" style="width:600px; height:100px; border:1px solid darkgrey; border-radius:3px; padding: 4px; background:lightgrey;">';
            echo '</textarea>';
            echo '<br>';
            echo '<input type="submit" value="Enter comment" style="background:darkgrey;">';
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
    echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
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
    echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
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
        echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
        echo '<a class="nohover" href="clan4.php?wrz='.$komm['id'].'">';
        echo '<div>';
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
        echo $kommText."<br>\n";
        if ($bShowUser) {
            echo '<br>';
            echo '<div style="font-size:small; color:darkgrey;">';
            echo '<span style="font-weight:bold">'.$komm['username'].'</span>';
            echo ' ['.($komm['moderator'] ? '<span style="color:lime">Moderator</span>' : '<span style="color:blue">User</span>').']';
            echo ', '."\n";
            $datum = $layout->dateWithoutSeconds($komm['datum']);
            echo $datum;
            echo '</div>'; 
        }
        echo '</div>';
        echo '</a>';
        if (!in_array($komm['id'], $wurzeln)) {
            echo '<div>';
            if (isset($_SESSION['moderator']) && $_SESSION['moderator']) {
                if ($komm['anzahlChildren'] == 0) {
                    echo '<a href="javascript:klappen('.$komm['id'].');">';
                    echo '<span style="font-size:small">delete</span>';
                    echo '</a>';
                    echo '<div id="aufklappdiv'.$komm['id'].'" style="text-align:center; display:none; margin:auto;">';
                    echo '<br>';
                    echo '<div style="box-shadow: 2px 1px 4px #888888; background:#220001; border-radius:5px; border:1px solid black; padding:5px;">';
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
    foreach ($aChildren as $child) {
        echo '<div style="margin-left:'.$margin.'px;">'."\n";
        echo '<div style="box-shadow: 2px 1px 4px #888888; background:#312f2f; border-radius:5px; border:1px solid dimgrey; padding:5px;">';
        echo '<span style="font-size:small; color:darkgrey;">';
        $datum = $layout->dateWithoutSeconds($child['datum']);
        echo $datum;
        echo ', '."\n";
        echo '<b>'.$child['username'].'</b>'."\n";
        echo ' ['.($child['moderator'] ? '<span style="color:lime">Moderator</span>' : '<span style="color:blue">User</span>').']';
        echo ': '."\n";
        echo '</span>';
        echo '<br>';
        $kommBr = $layout->replaceLineFeeds($child['kommentar']);
        echo $kommBr."\n";
        echo '<br>'."\n";
        echo '<a href="javascript:reply(\''.$child['kommentar'].'\','.$child['id'].');">reply</a>'."\n";
        echo '-'."\n";
        if (isset($_SESSION['moderator']) && $_SESSION['moderator']) {
                if (!count($child['children'])) {
                    echo '<a href="javascript:klappen('.$child['id'].');">';
                    echo '<span style="font-size:small">delete</span>';
                    echo '</a>';
                    echo '<div id="aufklappdiv'.$child['id'].'" style="text-align:center; display:none; margin:auto;">';
                    echo '<br>';
                    echo '<div style="box-shadow: 2px 1px 4px #888888; background:#220001; border-radius:5px; border:1px solid black; padding:5px;">';
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
                // echo '<a href="javascript:deleteKomm('.$child['id'].');"><span style="font-size:small">delete</span></a>'."\n";
        }
        echo '</div>'."\n";
        echo '</div>';
        displayChildren($child['children'], $margin + 20, $layout)."\n";
    }
}
 