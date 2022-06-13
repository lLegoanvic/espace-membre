<?php
include 'include/navbar.php';
include "include/header.html";
if ($_GET) {    // on récupere le mail et le token de l'url
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    }
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    }

    if (!empty($email) && !empty($token)) {
        require 'include/startBdd.php';
        $requete = $bdd->prepare('SELECT * FROM espacemembre.membres WHERE email=:email AND token=:token');
        $requete->bindvalue(':email', $email);
        $requete->bindvalue(':token', $token);
        $requete->execute();
        $nombre = $requete->rowCount();

        if ($nombre === 1) {    // on vérifie que l'email et le token correspondent à un user dans la bdd
            if (isset($_POST['modification'])) {    // on verifie que les champs soient correctement remplis
                if (empty(htmlspecialchars($_POST['password']))
                    || htmlspecialchars($_POST['password']) !== htmlspecialchars($_POST['passwordConfirm'])
                    || preg_match('/(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[@$!%?&])[A-Za-z\d@$!%?&]{8,}/', htmlspecialchars($_POST['password']))) {
                    $message = "Entrez un mot de passe de plus de 8 caractères, avec au moins une majuscule, une minuscule et un caractère spécial !";
                    $error = true;
                } else {    // on update le nouveau mdp
                    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
                    $update = $bdd->prepare('UPDATE espacemembre.membres SET password=:password, token=:token WHERE email=:email');
                    $update->bindValue(':password', $password);
                    $update->bindValue(':token', 'valide');
                    $update->bindValue(':email', $email);
                    $update->execute();
                    $message = "Votre mot de passe a bien été modifié ! " . "<a href='index.php'>Retour à l'index</a>";
                    $error = false;
                }
                $class = $error ? 'alert-danger' : 'alert-success';
            }


            ?>
            <div id="login">
                <h3 class="text-center text-white pt-5">Réinitialisation</h3>
                <div class="container">
                    <div id="login-row" class="row justify-content-center align-items-center">
                        <div id="login-column" class="col-md-6">
                            <div id="login-box" class="col-md-12 passwordreinit">
                                <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                                <form id="login-form" class="form" action="" method="post">
                                    <div class="form-group">
                                        <label for="password" class="text-body">Nouveau mot de passe:</label><br>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="passwordConfirm" class="text-body">Confirmer nouveau mot de
                                            passe:</label><br>
                                        <input type="password" name="passwordConfirm" id="passwordConfirm"
                                               class="form-control">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="submit" name="modification" class="btn btn-primary btn-md"
                                               value="Modifier">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </body>
            </html>
            <?php
        }
    }
}
?>
