<?php

session_start();

$raSession = $_SESSION['raLog'];

require "../vendor/autoload.php";

use App\EnvioGestor\EnvioGestor;
use App\Funcoes\Funcoes;


// $nomeMaterial = filter_var($_POST['nomeMaterial'], FILTER_SANITIZE_ADD_SLASHES);
$descricao = filter_var($_POST['descricao'], FILTER_SANITIZE_ADD_SLASHES);
$curso = filter_var($_POST['curso'], FILTER_SANITIZE_ADD_SLASHES);
$file = $_FILES['file'];
$error = $file['error'];
// echo "<pre>";
// print_r($file);
// echo "<pre>";

$descricaoFormatada = strlen(Funcoes::sanitizeString($descricao));


$descricao = Funcoes::sanitizeString($descricao);

if ($descricaoFormatada > 100) {
    $ex = base64_encode("<span class='btn btn-danger'>Descrição excedeu o limite de 100 caracteres</span>");
    header("Location: ../gestor/envio.php?msg=$ex");
    die();
}



//Validação se o arquivo q esta att e menor que 4MB
if ($file['size'][0] > 4000000) {
    $ex = base64_encode("<span class='btn btn-danger'>Excedeu o limite de 4MB</span>");
    header("Location: ../gestor/envio.php?msg=$ex");
    die();
}

if (empty($descricao) || empty($curso) || $error[0] != 0) {
    $_SESSION['envioInvalidoGestor'] = true;
    header('Location: ../gestor/envio.php');
    die();
} else {


    if ($curso != 'CURSOS') {
        $objeto = new EnvioGestor;
        // $objeto->nomeMaterial = $nomeMaterial;
        $objeto->descricao = $descricao;
        $objeto->curso = $curso;
        $objeto->file = $file;
        $objeto->data = date("Y-m-d");
        $objeto->raGestor = $raSession;
        $objeto->cadastrar();
    } else {
        $_SESSION['envioInvalidoGestor'] = true;
        header('Location: ../gestor/envio.php');
    }
}
