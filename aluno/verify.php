<?php

session_start();

//Validação de usuario ativo na sessão
if (!$_SESSION['KeyUser']) {
    header("Location: https://intra.ubm.br:8080/web/app/edu/PortalEducacional/#/");
    exit();
}

require '../vendor/autoload.php';

//Variavel que recebe a sessão usuario
$keyLog = $_SESSION['KeyUser'];


//Chama a class da api
use App\Api\Api;

//CHAMADA DA API TOTVS
Api::UserAPI($keyLog);

$hora_atual = time();

$tempo_online = $hora_atual - $_SESSION['hora_acesso'];

$tempo_online < 1200 ?: include("logout.php");
