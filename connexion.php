    <?php
    include "include/header.html";
    include 'include/navbar.php';
    const GUSER = 'biggoodbassist@gmail.com';
    $class = '';
    require 'functions.php';


    if (isset($_POST['connexion'])) {   // on vérifie que les champs soient correctement remplis
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        require_once 'include/startBdd.php';
        $requete = $bdd->prepare('SELECT * FROM espacemembre.membres WHERE email=:email');
        $requete->execute(array('email' => $email));
        $result = $requete->fetch();

        if (!$result) {
            $message = 'Merci de rentrer une adresse mail valide';
            $error = true;

        } elseif ($result['validation'] === 0) { // on vérifie que l'email a été validé, sinon on renvoie un mail de confirmation

            $token = tokenRandomString();
            $update = $bdd->prepare('UPDATE espacemembre.membres SET token=:token WHERE email=:email');
            $update->bindValue(':token', $token);
            $update->bindValue(':email', htmlspecialchars($_POST['email']));
            $result = $update->execute();
            $to = htmlspecialchars($_POST['email']);
            $body = 'Pour valider votre adresse email, merci de cliquer sur le lien suivant : <a href="http://127.0.0.1/espacemembre/verification.php?email=' . $to . '&token=' . $token . '">Confirmation</a>';
            $smtpResult = smtpmailer($to, GUSER, 'confirmation email', $body, "connexion");
            $message = $smtpResult['message'];
            $error = $smtpResult['error'];
        } else {    // on vérifie que le password est correct
            $passwordIsOk = password_verify($password, $result["password"]);
            if ($passwordIsOk) {
                session_start();
                $_SESSION['id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['email'] = $email;

                if (isset($_POST['remember-me'])) { // on crée et encode le cookie pour se reconnecter rapidement
                    $cookieValue = $email . ':' . $password;
                    setcookie("user", base64_encode($cookieValue), time() + 24 * 3600);
                    $error = false;


                }
                header('location:index.php');


            } else {
                $message = "Le mot de passe est incorrect !";
                $error = true;
            }


        }


        $class = $error ? 'alert-danger' : 'alert-success';
    }
    $cookieValue = [];
    if (isset($_COOKIE['user'])) {  //Si il existe le cookie user, on récupère les identifiants pour les rentrer dans le formulaire
        $cookieValue = explode(":", base64_decode($_COOKIE["user"]));
    }

    ?>

    <body>
    <div id="login">
        <h3 class="text-center text-white pt-5">Connection</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12 connexion">
                        <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                        <form id="login-form" class="form" action="" method="post">
                            <div class="form-group">
                                <label for="email" class="text-body">Adresse email:</label><br>
                                <input type="email" name="email" id="email" class="form-control"
                                       value="<?php if (isset($_COOKIE['user'])) {
                                           echo $cookieValue[0];
                                       } ?>">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-body">mot de passe:</label><br>
                                <input type="password" name="password" id="password" class="form-control"
                                       value="<?php if (isset($_COOKIE['user'])) {
                                           echo $cookieValue[1];
                                       } ?>">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="remember-me" class="text-body"><span>Se souvenir de moi</span> 
                                    <span>
                                    <input id="remember-me" name="remember-me" class="form-check-input" type="checkbox">
                                </span>
                                </label><br><br>
                                <a href="inscription.php" class="btn btn-primary btn-md">S'inscrire</a>
                                <input type="submit" name="connexion" class="btn btn-primary btn-md"
                                       value="Se connecter">
                                <a href="passwordForget.php">Mot de passe oublié</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
