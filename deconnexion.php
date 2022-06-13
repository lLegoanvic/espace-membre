<?php
include 'include/navbar.php';
include "include/header.html";
$class = '';
$error = true;
if(isset($_SESSION['id'])) {
    session_unset();
    session_destroy();
    $message = "vous avez bien été déconnecté !" . " <a href='index.php'>Retour à l'index</a>";
    $error = false;
    $class = $error ? 'alert-danger' : 'alert-success';
}
?>
<body>
<div id="login">
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12 connexion">
                    <?php if (isset($message)) echo ' <div class="alert ' . $class . '" role="alert">' . $message . '</div> ' ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>