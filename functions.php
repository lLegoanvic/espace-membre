<?php
function smtpmailer(string $to, string $from, string $subject, string $body, string $type): array // on type pck sinon ça plante
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . $from . '<' . $from . '>' . "\r\n";

    $result = [];
    if (mail($to, $subject, $body, $headers)) {
        switch ($type){
            case "inscription":
                $result['message'] = "Nous vous avons renvoyé un mail pour confirmer votre adresse.";
                break;
            case "connexion":
                $result['message'] = "Vous n'avez pas validé votre adresse mail. Nous vous avons renvoyé un mail pour confirmer votre adresse.";
                break;
            case "forget":
                $result['message'] = "Nous vous avons renvoyé un mail pour réinitialiser votre mot de passe.";
                break;
        }


        $result['error'] = false;
    } else {
        $result['message'] = 'Mail non envoyé';
        $result['error'] = true;
    }
    return $result;
}

function tokenRandomString()
{
    return bin2hex(random_bytes(20));
}

