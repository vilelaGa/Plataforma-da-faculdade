<?php

session_start();

$key = $_SESSION['KeyUser'];

require "../vendor/autoload.php";

use App\EnvioAluno\EnvioAluno;
use App\Api\Api;
use App\SelectUsuario\SelectUsuario;
use App\ValidaData\ValidaData;
use App\Funcoes\Funcoes;

//Traz dados do usuario logado pela api
Api::UserAPI($key);


$dataIni = filter_var($_POST['dataIni'], FILTER_SANITIZE_ADD_SLASHES);
$dataFin = filter_var($_POST['dataFin'], FILTER_SANITIZE_ADD_SLASHES);
$cargaHor = filter_var($_POST['cargaHor'], FILTER_SANITIZE_ADD_SLASHES);
$descricao = filter_var($_POST['descricao'], FILTER_SANITIZE_ADD_SLASHES);
$obs = filter_var($_POST['obs'], FILTER_SANITIZE_ADD_SLASHES);


//Validação se o obs e null ou não
$obsN = empty($obs) ? NULL : $obs;

//Validação carga horaria
$cargaHor = strlen($cargaHor) > 6 ? 30 : $cargaHor;

$file = $_FILES['file'];

//Validação se o arquivo q esta att e menor que 4MB
if ($file['size'] > 4000000) {
    $ex = base64_encode("<span class='btn btn-danger'>Excedeu o limite de 4MB</span>");
    header("Location: ../aluno/enviar.php?limite=$ex");
    die();
} else {
    $tmp = $file['tmp_name'];
}


$descricaoFormatada = strlen(Funcoes::sanitizeString($descricao));

// echo $descricaoFormatada;
// die();
$descricao = Funcoes::sanitizeString($descricao);


if (empty($dataIni) || empty($dataFin) || empty($cargaHor) || empty($tmp) || empty($descricao)) {
    $_SESSION['envioInvalidoAluno'] = true;
    header('Location: ../aluno/enviar.php');
} else {

    ValidaData::ValidaData($dataIni, $dataFin);

    $codGrade = SelectUsuario::TrazCodGrade($response->data[0]->IDHABILITACAOFILIAL);

    if (($checkDataIni && $checkDataFin) && ($dataTimeIni <= $dataTimeFin) && (is_numeric($cargaHor)) && $descricaoFormatada <= 60) {

        // echo $dataIni . '<br>';
        // echo $dataFin  . '<br>';
        // die();
        $objetoEnvi = new EnvioAluno;
        $objetoEnvi->codColidada = $response->data[0]->CODCOLIGADA;
        $objetoEnvi->codCurso = $response->data[0]->CODCURSO;
        $objetoEnvi->codHabilitacao = $response->data[0]->CODHABILITACAO;
        $objetoEnvi->turno = $response->data[0]->NOMETURNO;
        $objetoEnvi->codFilial = $response->data[0]->CODFILIAL;
        $objetoEnvi->codTipoCurso = $response->data[0]->CODTIPOCURSO;
        $objetoEnvi->ra = $response->data[0]->RA;
        $objetoEnvi->CodPeriodo = $response->data[0]->CODPERLET;
        $objetoEnvi->cargaHoraria = $cargaHor;
        $objetoEnvi->file = $file;
        $objetoEnvi->dataInicio = $dataIni;
        $objetoEnvi->dataFinal = $dataFin;
        $objetoEnvi->dataEntrega = date("Y-m-d");
        $objetoEnvi->oferta = $descricao;
        $objetoEnvi->codGadeMatriz = $codGrade; //via db
        $objetoEnvi->inscricao = 'S';
        $objetoEnvi->docEntregue = 'S';
        $objetoEnvi->cumprioAtvd = 'S';
        $objetoEnvi->obs = $obsN;
        $objetoEnvi->cadastrar();
    } else {
        $_SESSION['envioInvalidoAluno'] = true;
        header('Location: ../aluno/enviar.php');
    }
}
