<?php

require_once "../vendor/autoload.php";

use App\DeleteDados\DeleteDados;

$btn_delete_registro = filter_var(base64_decode($_POST['btn-delete-material']), FILTER_SANITIZE_ADD_SLASHES);
$arq = filter_var(base64_decode($_POST['arq']), FILTER_SANITIZE_ADD_SLASHES);

$explode = explode(", ", $arq);

// var_dump($explode);

for ($i = 0; $i < count($explode); $i++) {
    if (file_exists("uploadMaterialApoio/$explode[$i]")) {
        DeleteDados::DeleteMaterial($btn_delete_registro);
        unlink("uploadMaterialApoio/$explode[$i]");
        //função tempo de exclusão do banco
        header("Location: ../gestor/materialApoio.php");
    } else {
        $erro = base64_encode('<span class="btn btn-danger">Atividade já foi verificada</span>');
        header("Location: ../gestor/materialApoio.php?erro=$erro");
    }
}