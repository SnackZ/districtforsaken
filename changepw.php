<?
ini_set('error_log', 'error.log');
ini_set('display_errors', 1);
session_start();
require_once('Layout.php');

$db = new Datenbank();
$layout = new Layout();

$layout->kopf(1);

$resetPw = isset($_POST['resetPw']) ? $_POST['resetPw'] : '';
$confirmPw = isset($_POST['confirmPw']) ? $_POST['confirmPw'] : '';
$userId = isset($_POST['userId']) ? $_POST['userId'] : '';
$code = isset($_GET['code']) ? $_GET['code'] : 0;

var_dump($_POST);

if ($code) {
    $row = $db->getUserByCode($code);
    $userId = $row['id'];
    if ($userId) {
        $dtJetzt = new DateTime();
        $dtExpire = new DateTime($row['pwexpire']);
        if ($dtJetzt <= $dtExpire) {
            ?>
            <br>
            <form method="post" action="changepw.php">
            <table style="width:100%; text-align:center">
            <tr>   
            <td style="text-align:right; color:darkgrey;">
            new password:
            </td>
            <td style="text-align:left">
            <input type="password" name="resetPw" class="inputText">
            </td>
            </tr>
            
            <tr>
            <td style="text-align:right; color:darkgrey;">
            confirm password:
            </td>
            <td style="text-align:left">
            <input type="password" name="confirmPw" class="inputText">
            </td>
            </tr>
            <tr>
            <td colspan="2" style="text-align:center;">
            <input type="submit" class="inputSubmit" value="submit">
            </td>
            </tr>
            </table>
            <input type="hidden" name="userId" value="<?= $userId ?>">
            </form>
            <?
        } else {
            echo 'code has expired. Please resent the email for resetting your password.';
        }
    } else {
        echo 'kein code stimmt in DB überein, user hat code von hand in URL fenster eingegeben';
    }
} else {
    if ($resetPw || $confirmPw) {
        if ($userId) {
            if ($resetPw === $confirmPw) {
                $db->updatePassword($userId, $resetPw);
                echo 'passwort erfolgreich geändert';
            } else {
                echo 'passwords did not match';
            }
        } else {
            echo 'fehler';
        }
    } else {
        echo 'Zugriff verweigert';
    }
}

$layout->fuss();