<?php

require '../vendor/autoload.php';

use App\revogarValidacao\revogarValidacao;
use App\DbInsercao\DbInsercao;

$ref = base64_decode($_GET['referencia_revoga']);


$var = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("REGISTRO = '$ref'")
    ->fetch(PDO::FETCH_ASSOC);

$arquivo = $var['ARQUIVO'];

if ($var['MIGRACAO'] == NULL) {

    switch ($var['VALIDACAO']) {
        case (1):
            $caminho = "uploadMorto/$arquivo";
            if (file_exists($caminho)) {
                copy($caminho, 'uploadAtividadeAvaliacao/' . $arquivo);
                unlink($caminho);

                revogarValidacao::RevogarValidacao1($ref);

                //função de tempo para excluir do banco e dir
                header("Location: ../gestor/verValidaAtv.php?ref=" . base64_encode($ref));
            }
            break;
        case (0):
            $caminho = "uploadIndeferido/$arquivo";
            if (file_exists($caminho)) {
                copy($caminho, 'uploadAtividadeAvaliacao/' . $arquivo);
                unlink($caminho);

                revogarValidacao::RevogarValidacao0($ref);

                //função de tempo para excluir do banco e dir
                header("Location: ../gestor/verValidaAtv.php?ref=" . base64_encode($ref));
            }
            break;
    }
} else {
    header("Location: ../gestor/verValidaAtv.php?ref=" . base64_encode($ref));
}
