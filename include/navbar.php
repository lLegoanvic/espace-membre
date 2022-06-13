<?php

include 'include/header.html';
if(!isset($_SESSION['id'])) {
    session_start();
}
?>
    <nav class="navbar navbar-expand-lg bg-light navbar-dark bg-dark">
    <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Acceuil</a>
    <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    </ul>

<?php

if (isset($_SESSION['id'])) {
    echo 'Bonjour' . $_SESSION['username'];
    ?>
    <a class="navbar-brand" href="deconnexion.php">deconnexion</a>
    <a class="navbar-brand" href="profil.php">Profil</a>
    </div>
    </div>
    </nav>

    <?php
} else {
    ?>
    <a class="navbar-brand" href="inscription.php">Inscription</a>
    <a class="navbar-brand" href="connexion.php">Connexion</a>
    </div>
    </div>
    </nav>
    <?php
}


?>