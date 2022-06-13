<?php
include "include/header.html";
include 'include/navbar.php';
if (isset($_SESSION['id'])) {
    ?>
    <div id="login">
        <h3 class="text-center text-white pt-5">Profil</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-4">
                    <div id="login-box" class="d-flex justify-content-center profil">
                        <div class="container padding-top">
                            <div class="d-flex justify-content-center">
                                Nom d'utilisateur:
                                <?= $_SESSION['username'] ?>
                            </div>
                            <div class="d-flex justify-content-center">
                                Email:
                                <?= $_SESSION['email'] ?>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="modifProfil.php">Modifier mon nom d'utilisateur</a>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="modifPassword.php">Changer mon mot de passe</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>


    <?php
}else {
    header('location: index.php');
}


?>

</body>
</html>
