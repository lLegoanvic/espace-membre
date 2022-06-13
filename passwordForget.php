<?php
include 'include/navbar.php';
include "include/header.html";
require "functions.php";
const GUSER = 'biggoodbassist@gmail.com';
$class = '';
if (isset($_POST['passwordForget'])) {
    $token = tokenRandomString();
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {    // on véfirie que l'email est valide
        $message = "Rentrez une adresse email valide !";
        $error = true;
    } else {
        require('include/startBdd.php');
        $requete = $bdd->prepare('SELECT * FROM espacemembre.membres WHERE email=:email');
        $requete->bindValue(':email', htmlspecialchars($_POST['email']));
        $requete->execute();

        $result = $requete->fetch();
        $nombre = $requete->rowCount();


        if ($nombre !== 1) {    // on vérifie que l'email est dans la bdd
            $message = "l'adresse mail saisie ne correspond à aucun utilisateur dans notre espace membre !";
            $error = true;
        } else {
            if ($result['validation'] !== 1) {  // on vérifie que l'email est valide puis on renvoie un mail avec un lien pour réinitialiser le mot de passe
                $update = $bdd->prepare('UPDATE espacemembre.membres SET token=:token WHERE email=:email');
                $update->bindValue(':token', $token);
                $update->bindValue(':email', htmlspecialchars($_POST['email']));
                $update->execute();

                $to = htmlspecialchars($_POST['email']);
                $body = 'Pour réinitialiser votre mot de passe, merci de cliquer sur le lien suivant : <a href="http://127.0.0.1/espacemembre/passwordReinit.php?email=' . $to . '&token=' . $token . '">Confirmation</a>';
                $smtpResult = smtpmailer($to, GUSER, 'confirmation email', $body, "forget");
                $message = $smtpResult['message'];
                $error = $smtpResult['error'];
            }
        }
    }
    $class = $error ? 'alert-danger' : 'alert-success';
}
?>
<body>
<div id="login">
    <h3 class="text-center text-white pt-5">Mot de passe oublié ?</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12 passwordforget">
                    <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                    <form id="login-form" class="form" action="" method="post">

                        <div class="form-group">
                            <label for="email" class="text-body">Adresse email:</label><br>
                            <input type="email" name="email" id="email" class="form-control">

                            <br>
                            <div class="form-group">
                                <input type="submit" name="passwordForget" class="btn btn-primary btn-md"
                                       value="envoyer">

                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

