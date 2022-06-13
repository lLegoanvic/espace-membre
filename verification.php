<?php
include 'include/navbar.php';
require 'include/header.html';
require 'include/startBdd.php';
$class = '';
if ($_GET) {// on récupère le mail et le token dans l'url via le lien dans le mail de verification
    $error = true;
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    }
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    }

    if (!empty($email) && !empty($token)) { // on vérifie que le token est le mail soient bien présents dans la bdd
        $requete = $bdd->prepare('SELECT * FROM espacemembre.membres WHERE email=:email AND token=:token');
        $requete->bindvalue(':email', $email);
        $requete->bindvalue(':token', $token);
        $requete->execute();
        $nombre = $requete->rowCount();

        if ($nombre === 1) {    // on valide l'user
            $update = $bdd->prepare('UPDATE espacemembre.membres SET validation=:validation, token=:token WHERE email=:email');
            $update->bindValue(':validation', 1);
            $update->bindValue(':token', 'valide');
            $update->bindValue(':email', $email);

            $resultUpdate = $update->execute();

            if ($resultUpdate) {
                $message = "votre adresse mail est bien confirmée " .  "<a href='index.php'>Retour à l'index</a>"; ;
                $error = false;
            }
        }

    }
    $class = $error ? 'alert-danger' : 'alert-success';
}


?>
<body>
<div id="login">
    <h3 class="text-center text-white pt-5">Verification</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">
                    <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                    <form id="login-form" class="form" action="" method="post">
                        <div class="form-group">
                            <br>
                            <div class="form-group">

                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
