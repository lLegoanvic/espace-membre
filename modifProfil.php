    <?php
    include "include/header.html";
    include 'include/navbar.php';
    $class = '';
    if (isset($_POST['modification'], $_SESSION['id'])) {    // on s'assure d'etre connecté, puis on vérifie que les champs soient correctement remplis
        if (!(!empty(htmlspecialchars($_POST['username'])) || preg_match('/[a-zA-Z0-9]/', htmlspecialchars($_POST['username'])))) {
            $message = "Votre nom d'utilisateur doit être une chaine de caratères (alphanumerique) !";
            $error = true;
        } else {    // on vérifie que le nouveau nom n'est pas déjà dans la bdd
            require_once 'include/startBdd.php';
            $req = $bdd->prepare('SELECT * FROM espacemembre.membres WHERE username = :username');
            $req->bindvalue(':username', htmlspecialchars($_POST['username']));
            $req->execute();
            $result = $req->fetch();

            if ($result) {
                $message = "Le username que vous avez choisi existe déjà !";
                $error = true;
            } else {    // on change le nom d'utilisateur
                $requete = $bdd->prepare('UPDATE  espacemembre.membres SET username = :username WHERE id=:id');
                $requete->bindValue(':username', htmlspecialchars($_POST['username']));
                $requete->bindValue(':id', $_SESSION['id']);
                $requete->execute();
                $message = "Votre nom d'utilisateur a bien été modifié !" . " <a href='index.php'>Retour à l'index</a>";
                $error = false;
            }
        }
        $class = $error ? 'alert-danger' : 'alert-success';
    }

    ?>
    <div id="login">
        <h3 class="text-center text-white pt-5">Modification</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12 modifprofil">
                        <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                        <form id="login-form" class="form" action="" method="post">
                            <div class="form-group">
                                <label for="username" class="text-body">Nom d'utilisateur:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
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
