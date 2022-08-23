<?php

require_once '../vendor/autoload.php';

session_start();

use App\DeleteDados\DeleteDados;
use App\SelectUsuario\SelectUsuario;
use App\Api\Api;

$key = $_SESSION['KeyUser'];

//Traz dados do usuario pela api
Api::UserAPI($key);

$regitro = filter_var(base64_decode($_POST['deletar']), FILTER_SANITIZE_ADD_SLASHES);
$arq = filter_var(base64_decode($_POST['arq']), FILTER_SANITIZE_ADD_SLASHES);

//Traz a atv do db
$res = SelectUsuario::TrazAtividade($regitro);


//Valida se a atividade ja foi validada ou n
$caminho = $res['VALIDACAO'] == NULL ? "uploadAtividadeAvaliacao/$arq" : "uploadIndeferido/$arq";


//Bloco de execulção de exclusão de arquivos e no banco de dados
if (file_exists($caminho)) {
    DeleteDados::DeleteAtividades($regitro, $response->data[0]->RA);
    unlink($caminho);
    //função tempo de exclusão do banco
    header("Location: ../aluno/enviados.php?pagina=1");
} else {
    $erro = base64_encode('<span class="btn btn-danger">Atividade já foi verificada</span>');
    header("Location: ../aluno/enviados.php?erro=$erro");
}
