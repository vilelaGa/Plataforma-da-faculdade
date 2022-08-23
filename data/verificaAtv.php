<?php

require_once '../vendor/autoload.php';

use App\SelectUsuario\SelectUsuario;
use App\Validacao\Validacao;
use App\Funcoes\Funcoes;

session_start();

$ra = $_SESSION['raLog'];

$validar = filter_var(base64_decode($_POST['validar']), FILTER_SANITIZE_ADD_SLASHES);

$modalidade = filter_var($_POST['modalidade'], FILTER_SANITIZE_ADD_SLASHES);
$arquivo = filter_var($_POST['arq'], FILTER_SANITIZE_ADD_SLASHES);
$revogar = filter_var(base64_decode($_POST['revogar']), FILTER_SANITIZE_ADD_SLASHES);

$dataVerifica = date('Y-m-d');

//form
// $dtI = filter_var($_POST['dtI'], FILTER_SANITIZE_ADD_SLASHES);
// $dtF = filter_var($_POST['dtF'], FILTER_SANITIZE_ADD_SLASHES);
$cargaHor = filter_var($_POST['carga'], FILTER_SANITIZE_ADD_SLASHES);
$descri = filter_var($_POST['descr'], FILTER_SANITIZE_ADD_SLASHES);
//form
$obsIndeferido = filter_var($_POST['obsIndeferido'], FILTER_SANITIZE_ADD_SLASHES);


//Valida a revogação
$res = !empty($revogar) ? $revogar : $validar;
$ref = base64_encode($res);


//Validação carga horaria
$cargaHor = strlen($cargaHor) > 6 ? 30 : $cargaHor;

$descricaoFormatada = strlen(Funcoes::sanitizeString($descri));

if ($descricaoFormatada > 60){ 
    $erro = base64_encode('<span class="btn btn-danger">Descrição excedeu 60 caracteres</span>');
    header("Location: ../gestor/verValidaAtv.php?ref=$ref&erro=$erro");
    die();
}

$descri = Funcoes::sanitizeString($descri);

$caminho = "uploadAtividadeAvaliacao/$arquivo";


if (!empty($validar) && !empty($modalidade) && !empty($arquivo) && $modalidade != 'Modalidade') {

    if (file_exists($caminho)) {

        Validacao::ValidacaoAtividade($validar, $modalidade, $cargaHor, $descri, $dataVerifica, $ra);

        copy($caminho, 'uploadMorto/' . $arquivo);
        unlink($caminho);
        //função de tempo para excluir do banco e dir
        header("Location: ../gestor/entregues.php");
    } else {
        $erro = base64_encode('<span class="btn btn-danger">Erro desconhecido</span>');
        header("Location: ../gestor/entregues.php?erro=$erro");
    }
} else if (!empty($revogar)) {

    if (!empty($obsIndeferido)) {
        if (file_exists($caminho)) {
            Validacao::RevogaAtividade($revogar, $obsIndeferido, $dataVerifica, $ra);
            copy($caminho, 'uploadIndeferido/' . $arquivo);
            unlink($caminho);
            //função de tempo para excluir do banco
            header("Location: ../gestor/entregues.php");
        } else {
            $erro = base64_encode('<span class="btn btn-danger">Erro desconhecido</span>');
            header("Location: ../gestor/entregues.php?erro=$erro");
        }
    } else {
        $erro = base64_encode('<span class="btn btn-danger">Digite a observação do indeferimento</span>');
        header("Location: ../gestor/verValidaAtv.php?ref=$ref&erro=$erro");
    }
} else {
    $erro = base64_encode('<span class="btn btn-danger">Selecione a modalidade</span>');
    header("Location: ../gestor/verValidaAtv.php?ref=$ref&erro=$erro");
}
