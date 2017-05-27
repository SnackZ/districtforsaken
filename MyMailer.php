<?
require_once 'PHPMailer/PHPMailerAutoload.php';
class MyMailer {
    
    public function sendMail($mailBetreff, $mailText, $mailAdresse){
        $mail = new PHPMailer;
        $mail->SMTPDebug = 1; // 0=nix, 3=verbose
        $mail->isSMTP();
        $mail->SMTPSecure = "tls";
        $mail->Host = "mail.lima-city.de";
        $mail->Port = 587; // 465 ist veraltet!
        $mail->SMTPAuth = true;
        $mail->Username = "mail@districtforsaken.de";
        $mail->Password = "durmoth123";
        
        $mail->From = "verify@districtforsaken.de";
        $mail->FromName = "District Forsaken";
        
        $mail->addAddress($mailAdresse);
        
        //$mailer->AddBCC('mail@fritz-schmude.de'); // zur Kontrolle
        $mail->Subject = $mailBetreff;
        $mail->Body = $mailText;
        
        /*
        echo 'betreff = "'.$mailBetreff.'"<br>';
        echo 'text = "'.$mailText.'"<br>';        
        echo 'adresse = "'.$mailAdresse.'"<br>';
        */
        
        // abschicken
        if (!$mail->send()) {
            $ret = "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $ret = false;
        }
        
        return $ret;
    }
    
    public function validateAddress($eadr) {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]{2,4}|museum))$/i';
        $regex = '/^[_a-z0-9-\.]+@[_a-z0-9-\.]+\.[a-z]{2,}$/i';
        $b_ok = preg_match($regex, $eadr);
        return $b_ok;
    }
}
