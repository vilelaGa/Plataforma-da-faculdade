<?php

use App\Api\Api;
use App\EditarAtividade\EditarAtividade;
use App\SelectUsuario\SelectUsuario;
use App\Funcoes\Funcoes;

session_start();

require_once '../vendor/autoload.php';
require_once 'reUpload.php';

$keyLogado = $_SESSION['KeyUser'];

//Traz dados do usuario logado pela api
Api::UserAPI($keyLogado);

$ra = $response->data[0]->RA;
$arq = filter_var($_POST['arq'], FILTER_SANITIZE_ADD_SLASHES);
$registro = filter_var($_POST['registro'], FILTER_SANITIZE_ADD_SLASHES);
// $dataIni = filter_var($_POST['dtI'], FILTER_SANITIZE_ADD_SLASHES);
// $dataFin = filter_var($_POST['dtF'], FILTER_SANITIZE_ADD_SLASHES);
$cargaHor = filter_var($_POST['carga'], FILTER_SANITIZE_ADD_SLASHES);
$obs = filter_var($_POST['obs'], FILTER_SANITIZE_ADD_SLASHES);
$descr = filter_var($_POST['descr'], FILTER_SANITIZE_ADD_SLASHES);
$file = $_FILES['file'];

//Traz atividades pelo registro
$dados = SelectUsuario::TrazAtividade($registro);

$regi64 = base64_encode($registro);

//Validação se numero de caracteres
$descricaoFormatada = strlen(Funcoes::sanitizeString($descr));

if ($descricaoFormatada > 60) {
    $_SESSION['editarInvalido'] = true;
    header("Location: ../aluno/editar.php?atv=$regi64");
    die();
}

$descr = Funcoes::sanitizeString($descr);

//Validação carga horaria
$cargaHor = strlen($cargaHor) > 6 ? 30 : $cargaHor;

//Validaçã se atividade ja foi validada
if ($dados['VALIDACAO'] != null) {
    // $ex = base64_encode("<span class='btn btn-danger'>Ja foi validado</span>");
    header("Location: ../aluno/enviados.php");
    die();
}

$regi64 = base64_encode($registro);


//Validaçã se a carga e numero ou não
$var = is_numeric($cargaHor) ? 'true' : 'false';


//Validação se o arquivo q esta att e menor que 4MB
if ($file['size'] > 4000000) {
    $ex = base64_encode("<span class='btn btn-danger'>Excedeu o limite de 4MB</span>");
    header("Location: ../aluno/editar.php?atv=$regi64");
    die();
}


//Validação se o obs e null ou não
$obs = empty($obs) ? null : $obs;


if (empty($ra) || empty($arq) || empty($registro) || empty($cargaHor) || empty($descr) || $var == 'false') {

    $_SESSION['editarInvalido'] = true;
    header("Location: ../aluno/editar.php?atv=$regi64");
} else {
    reUpload($arq, $file, $regi64);
    EditarAtividade::EditarAtividade($registro, $ra, $cargaHor, $descr, $obs);
    header("Location: ../aluno/editar.php?atv=$regi64");
}


// echo 'Ra: ' . $response->data[0]->RA . '<br>';
// echo 'Arquivo: ' . $arq . '<br>';
// echo 'Registro: ' . $registro . '<br>';
// echo 'Data Ini: ' . $dataIni . '<br>';
// echo 'Data Fin: ' . $dataFin . '<br>';
// echo 'Carga hor: ' .  $cargaHor . '<br>';
// echo 'OBS: ' . $obs . '<br>';
// echo 'Descrição: ' . $descr . '<br>';
