<?php

session_start();

require_once("../vendor/autoload.php");

use App\Login\Login;

//Variaveis que recebem o post da tela login
$ra = filter_var($_POST['inputMatricula'], FILTER_SANITIZE_ADD_SLASHES);
$cpf = filter_var($_POST['inputCpf'], FILTER_SANITIZE_ADD_SLASHES);

//Validação se os campos são vazios
if (empty($ra) || empty($cpf)) {
    $_SESSION['userInvalido'] = true;
    header("Location: ../login.php");
} else {
    Login::Login($ra, $cpf);
}
