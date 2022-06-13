<?php
include "include/header.html";
include 'include/navbar.php';
const GUSER = 'biggoodbassist@gmail.com'; // adresse d'expedition

require 'functions.php';
$token = tokenRandomString();
$class = ''; //defini la couleur des alertes

if (isset($_POST['inscription'])) { //on verifie que les champs soient correctement remplis
    if (!(!empty(htmlspecialchars($_POST['username'])) || preg_match('/[a-zA-Z0-9]/', htmlspecialchars($_POST['username'])))) {
        $message = "Votre nom d'utilisateur doit être une chaine de caratères (alphanumerique) !";
        $error = true;
    } elseif (empty(htmlspecialchars($_POST['email'])) || !filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $message = "Entrez une adresse mail valide !";
        $error = true;
    } elseif (empty(htmlspecialchars($_POST['password']))
        || htmlspecialchars($_POST['password']) !== htmlspecialchars($_POST['passwordConfirm'])
        || preg_match('/(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[@$!%?&])[A-Za-z\d@$!%?&]{8,}/', htmlspecialchars($_POST['password']))) {
        $message = "Entrez un mot de passe de plus de 8 caractères, avec au moins une majuscule, une minuscule et un caractère spécial !";
        $error = true;

    } else {    // on vérifie que l'username ou l'email n'est pas déjà dans la bdd
        require_once 'include/startBdd.php';
        $req = $bdd->prepare('SELECT * FROM espacemembre.membres WHERE username = :username');
        $req->bindvalue(':username', htmlspecialchars($_POST['username']));
        $req->execute();
        $result = $req->fetch();

        $req1 = $bdd->prepare('SELECT * FROM espacemembre.membres WHERE email = :email');
        $req1->bindvalue(':email', htmlspecialchars($_POST['email']));
        $req1->execute();
        $result1 = $req1->fetch();


        if ($result) {
            $message = "Le username que vous avez choisi existe déjà !";
            $error = true;
        } elseif ($result1) {
            $message = "L'email que vous avez choisi est déjà utilisé !";
            $error = true;
        } else {    // on ajoute l'utilisateur à la bdd et on lui envoie un mail de confirmation

            $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
            $requete = $bdd->prepare('INSERT INTO membres(username, email, password, token) VALUES(:username, :email, :password, :token)');
            $requete->bindValue(':username', htmlspecialchars($_POST['username']));
            $requete->bindValue(':email', htmlspecialchars($_POST['email']));
            $requete->bindValue(':password', $password);
            $requete->bindValue(':token', $token);
            $requete->execute();

            $to = htmlspecialchars($_POST['email']);
            $body = 'Pour valider votre adresse email, merci de cliquer sur le lien suivant : <a href="http://127.0.0.1/espacemembre/verification.php?email=' . $to . '&token=' . $token . '">Confirmation</a>';
            $smtpResult = smtpmailer($to, GUSER, 'confirmation email', $body, 'inscription');
            $message = $smtpResult['message'];
            $error = $smtpResult['error'];
        }
    }
    $class = $error ? 'alert-danger' : 'alert-success';
}

?>

<body>
<div id="login">
    <h3 class="text-center text-white pt-5">Inscription</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12 inscription">
                    <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                    <form id="login-form" class="form" action="" method="post">
                        <div class="form-group">
                            <label for="username" class="text-body">Nom d'utilisateur:</label><br>
                            <input type="text" name="username" id="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email" class="text-body">Adresse email:</label><br>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-body">mot de passe:</label><br>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="passwordConfirm" class="text-body">Confirmer mot de passe:</label><br>
                            <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="submit" name="inscription" class="btn btn-primary btn-md" value="S'inscrire">
                            <a href="connexion.php" class="btn btn-primary btn-md">Se connecter</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
