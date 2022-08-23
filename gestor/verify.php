<?php

session_start();

//Validação de usuario ativo na sessão
if (!$_SESSION['raLog']) {
    header("Location: ../login.php");
    exit();
}

//Variavel que recebe a sessão usuario
$raLog = $_SESSION['raLog'];

require '../vendor/autoload.php';

//importa a função
use App\SelectUsuario\SelectUsuario;

//Função que retona os dados do usuario logado
SelectUsuario::TRAZCHAPA($raLog);

//Nome do usuario
$nome = $varGestor['NOME'];

//Ra do usuario
$ra = $raLog;
