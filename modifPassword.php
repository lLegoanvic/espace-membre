    <?php
    include "include/header.html";
    include 'include/navbar.php';
    $class = '';
    $id = $_SESSION['id'];
    if (isset($_POST['modification'], $_SESSION['id'])) {   // on vérifie que l'utilisateur est connecté.
        require_once 'include/startBdd.php';
        $req = $bdd->prepare("SELECT * FROM espacemembre.membres WHERE id = :id");
        $req->bindvalue(':id', $id);
        $req->execute();
        $result = $req->fetch();
        if ($result) {  // on vérifie que l'id de l'utilisateur est dans la bdd
            $password = htmlspecialchars($_POST['passwordOld']);
            $passwordIsOk = password_verify($password, $result["password"]);
            if ($passwordIsOk) {    // on vérifie que l'ancien mot de passe est bon, puis un vérifie que les champs soient remplis correctement
                if (empty(htmlspecialchars($_POST['password']))
                    || htmlspecialchars($_POST['password']) !== htmlspecialchars($_POST['passwordConfirm'])
                    || preg_match('/(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[@$!%?&])[A-Za-z\d@$!%?&]{8,}/', htmlspecialchars($_POST['password']))) {
                    $message = "Entrez un mot de passe de plus de 8 caractères, avec au moins une majuscule, une minuscule et un caractère spécial !";
                    $error = true;
                } else {    // on update le nouveau mdp dans la bdd
                    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
                    $requete = $bdd->prepare('UPDATE  espacemembre.membres SET password = :password WHERE id = :id');
                    $requete->bindValue(':password', $password);
                    $requete->bindValue(':id', $id);
                    $requete->execute();
                    $message = "Votre mot de passe a bien été modifié ! " . '<a href="profil.php">Retour au profil</a>';
                    $error = false;
                    if (isset($_COOKIE['user'])) {  //on re crée un cookie user si il existait déjà, avec le nouveau mdp
                        $email = $_SESSION['email'];
                        $password = htmlspecialchars($_POST['password']);
                        $cookieValue = $email . ':' . $password;
                        setcookie("user", base64_encode($cookieValue), time() + 24 * 3600);
                        }
                }
            } else {
                $message = "Votre ancien mot de passe est incorrect.";
                $error = true;
            }
            $class = $error ? 'alert-danger' : 'alert-success';
        }
    }
    ?>
    <div id="login">
        <h3 class="text-center text-white pt-5">Modification</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12 modifpassword">
                        <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                        <form id="login-form" class="form" action="" method="post">
                            <div class="form-group">
                                <label for="passwordOld" class="text-body">Ancien mot de passe:</label><br>
                                <input type="password" name="passwordOld" id="passwordOld" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-body">Nouveau mot de passe:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="passwordConfirm" class="text-body">Confirmer nouveau mot de
                                    passe:</label><br>
                                <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control">
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
