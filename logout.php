<?php

if (!isset($_SESSION)) {
    session_start();
}

session_destroy();

header("Location: login.php");


exit; // Certifique-se de sair após redirecionar
