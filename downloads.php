<?
ini_set('error_log', 'error.log');
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$db = new Datenbank();
$layout = new Layout();

$layout->kopf(1);

// var_dump($_SESSION);

$accessMessage = '<div class="textDiv">Not logged in?<br><span style="font-size:small; color:darkgrey;">It seems like you do not have permissions to view this content.
</span></div><br><div class="textDiv">
You want to get access to our services?<br>
<span style="font-size:small; color:darkgrey;">For being allowed to enter the respective contents of our website, you have to be logged in.<br>
If you do not have an account you could log in with, please sign up <a href="clan5.php">here</a>!</span></div>';

if (isset($_SESSION['userid']) && $_SESSION['userid']) {
    $accessMessage = '<div class="textDiv">Your rank is not sufficiently - <span style="color:red">access denied</span> !<br>
    <span style="font-size:small; color:darkgrey;">This content can only be accessed with the required permissions.<br>
    If you do not know, what this page is for, you should not be here.</span></div><br>
    <a class="nohover" href="clan4.php"><input type="submit" value="Go back to forum" class="inputSubmit"></a>';
    $downloadRank = $db->getDownloadRank($_SESSION['userid']);
    // var_dump($downloadRank);
    if ($downloadRank['download'] == 1) {
        $accessMessage = '<div class="textDiv">Please make sure, you save these files at the folder of your iniuria external 3rd party drive<br>
        <span style="font-size:small; color:darkgrey;">external - name of iniuria - Iniuria CSGO Full Beta - Config</span></div>';
        $legitWithoutWalls =  basename("downloadPerm/Legit_without_wall.conf");
        $legitWallsActive = basename("downloadPerm/Legit_wall_active.conf");
        echo '<br>';
        echo '<div class="textDiv">';
        echo 'Both of these configurations have been used with Iniuria CS:GO Full<br><span style="font-size:small; color:darkgrey;">';
        echo 'I, SnackZ, scripting this website and for my friends known as a legithacker with Iniuria and even more cheats, have been using these configurations for legit hacking
         on the matchmaking rank elo Global Elite for more than 260 matchmaking wins - at all over 500 matches.<br><span style="color:red;">Note:</span> This configs
          should not be used if you want to improve your aim way more than it would stay natural!<br>
        The aimbot is set up at FOV 1 on almost all of the weapons, on both of the configurations.<br>That is why these configs will never be <span style="color:yellow;">
        overwatch</span> detected! Personally I started hacking as DMG - and became Global Elite with these settings easily!<br><span style="color:white;">
        Just download and check them out!</span></span>';
        echo '</div><br><div class="textDiv">';
        echo '<a class="nohover" download="'.$legitWithoutWalls.'" href="downloadPerm/Legit_without_wall.conf" title="Iniuria Legit Conf">';
        echo '<span style="font-size:small; color:darkgrey;">';
        echo '<input type="submit" value="download conf" class="inputSubmit" style="font-size:small; font-weight:medium;">';
        echo ' &nbsp <span style="color:red">Legit_without_wall</span> ~ made by SnackZ';
        echo '</span></a>'; 
        echo '<a class="nohover" download="'.$legitWallsActive.'" href="downloadPerm/Legit_wall_active.conf" title="Iniuria Legit Conf">';
        echo '<br><span style="font-size:small; color:darkgrey;"><br>';
        echo '<input type="submit" value="download conf" class="inputSubmit" style="font-size:small; font-weight:medium;">';
        echo ' &nbsp <span style="color:red">Legit_wall_active</span> ~ made by SnackZ</span></a></div>';
    }
}

$data = array(
    'accessMessage' => $accessMessage,
    );

?>
<br>

<?= $data['accessMessage']; ?>






<?
$layout->fuss();