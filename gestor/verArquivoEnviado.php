<?php

// Verifica a sessão
include("verify.php");


require_once '../vendor/autoload.php';


//Importe de classe
use App\SelectUsuario\SelectUsuario;
use App\Validacao\Validacao;

//Valida se o parametro e vazio
$ValidaRegistro = empty(base64_decode($_GET['arquivo'])) ? header('Location: materialApoio.php') : base64_decode($_GET['arquivo']);

//Armazena o parametro na variavel $registro
$registro = filter_var($ValidaRegistro, FILTER_SANITIZE_ADD_SLASHES);

//Valida o registro da atividade
$res = Validacao::ValidaRegistroMaterial($registro);

//Chamada com retorno de uma query
$material = SelectUsuario::TrazMaterialRegistro($registro);

//Explode nA string do nome de varios arquivos PDFS
$materialVer = explode(', ', $material['ARQUIVO']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php

define("NOME_PAGINA", "Downloads");

include("includes/head.php"); ?>

<body>


    <div class="alinhaDownload">
        <div class="container">
            <h3>Downloads</h3>
            <div class="row">
                <?php
                //Traz os dados do banco e forma o elemento na tela
                foreach ($materialVer as $key => $linha) { ?>

                    <div class="col-md-3">
                        <div class="card align-items-center" style="width: 18rem;">
                            <img src="../assets/images/img-pfd.png" width="50%" alt="...">

                            <div class="card-body text-center">
                                <h5 class="card-title">Arquivo <?= $key ?></h5>
                                <p class="card-text">
                                    Descrição:<br>
                                    <?= $material['DESCRICAO'] ?></p>
                                <a class="btn btn-primary" href="../data/uploadMaterialApoio/<?= $linha ?>" download="Arquivo <?= $key ?>" class="list-group-item list-group-item-action"><i class="fa fa-download" aria-hidden="true"></i> Download</a><br>
                            </div>
                        </div>
                    </div>


                <?php } ?>
            </div>
            <center>
                <a href="materialApoio.php" class="btn btn-success mt-4">Voltar</a>
            </center>
        </div>
    </div>

</body>

</html>