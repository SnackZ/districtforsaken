<?
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$layout = new Layout();

$layout->kopf(4);

$db = new Datenbank();

$commentary = isset($_GET['comment']) ? $_GET['comment'] : '';
$wurzelId = isset($_GET['wrz']) ? (int) $_GET['wrz'] : 0;
$parentId = isset($_GET['parent']) ? (int) $_GET['parent'] : 0;

if ($wurzelId) {
    if ($commentary && $parentId) {
        $db->createCommentary($commentary, $parentId);
    }
    ?>
    <div style="text-align:right;">
    <a href="clan4.php">Back to all threads</a>
    </div>
    <?
    $aBeitrag = $db->getBeitrag($wurzelId);
    echo '<br>';
    echo $aBeitrag['kommentar'];
    echo '<br>';
    echo '<br>';
    echo '<span style="color:darkgrey;">';
    echo '<span style="font-weight:bold">'.$aBeitrag['username'].'</span>';
    echo ', ';
    $datum = $layout->dateWithoutSeconds($aBeitrag['datum']);
    echo $datum;
    echo '</span>';
    echo '<br>';
    echo '<a href="javascript:reply(\''.$aBeitrag['kommentar'].'\','.$wurzelId.');">reply</a>'."\n";
    echo '<img src="img/p1gelb.png" width="100%" height="1px">';
    
    // Children anzeigen
    $aChildren = $db->getChildren($wurzelId);
    displayChildren($aChildren, 20, $layout);
    
} else {
    $aKommentare = $db->getWurzeln();
     ?>
    <br>
    <?
    foreach ($aKommentare as $key => $komm) {
        $kommText = $layout->replaceLineFeeds($komm['kommentar']);
        echo '<a style="color:yellow" href="clan4.php?wrz='.$komm['id'].'">'.$kommText.'</a>'."\n";
        echo '<div style="font-size:small; color:darkgrey;">';
        echo '<span style="font-weight:bold">'.$komm['username'].'</span>';
        echo ', '."\n";
        $datum = $layout->dateWithoutSeconds($komm['datum']);
        echo $datum;
        echo '</div>';
        echo '<br><br>'."\n";
        
    }
}   
if ($_SESSION['userid']) {
    echo '<div id="answerArea" style="display:none">';
    echo '<div id="parentComment">';
    echo 'You are replying to: <br>';
    echo '';
    echo '</div>';
    echo '<form method="get" action="clan4.php" style="text-align:center;">';
    echo '<input type="hidden" id="hiddenParent" name="parent" value="">';
    echo '<input type="hidden" id="hiddenWurzel" name="wrz" value="'.$wurzelId.'">';
    echo '<textarea name="comment" style="width:600px; height:100px"></textarea>';
    echo '<br>';
    echo '<input type="submit" value="Enter comment">';
    echo '</form>';
    echo '</div>';
}    



$layout->fuss();
exit;

/*=====================================================================
 * Funktionen nur für diese Seite
 *====================================================================*/
 function displayChildren($aChildren, $margin, $layout) {
    foreach ($aChildren as $child) {
        echo '<div style="margin-left:'.$margin.'px;">'."\n";
        echo '<span style="font-size:small; color:darkgrey;">';
        $datum = $layout->dateWithoutSeconds($child['datum']);
        echo $datum;
        echo ', '."\n";
        echo '<b>'.$child['username'].'</b>'."\n";
        echo ': '."\n";
        echo '</span>';
        echo '<br>';
        $kommBr = $layout->replaceLineFeeds($child['kommentar']);
        echo $kommBr."\n";
        echo '<br>'."\n";
        echo '<a href="javascript:reply(\''.$child['kommentar'].'\','.$child['id'].');">reply</a>'."\n";
        echo '<img src="img/p1gelb.png" width="100%" height="1px">';
        echo '</div>'."\n";
        displayChildren($child['children'], $margin + 20, $layout)."\n";
    }
}
 