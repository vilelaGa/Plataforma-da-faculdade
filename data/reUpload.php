<?php

/**
 * Função criada para substituir um arquivo no diretorio
 * 3 Parametro
 * @var int $arquivo @var string $filepost @var int $regi64
 */
function reUpload($arquivo, $filePost, $regi64)
{
    $file = $filePost;
    $tmp = $file['tmp_name'];

    $extensions = "pdf";

    if (!empty($tmp)) {
        if (!preg_match("/application\/($extensions)/", $file['type'])) {
            $_SESSION['editarInvalido'] = true;
            header("Location: ../aluno/editar.php?atv=$regi64");
            die();
        } else {
            $caminho = 'uploadAtividadeAvaliacao/' . $arquivo;
            $novoArquivo = $arquivo;
            unlink($caminho);
            move_uploaded_file($tmp, 'uploadAtividadeAvaliacao/' . $novoArquivo);
        }
    }
}
