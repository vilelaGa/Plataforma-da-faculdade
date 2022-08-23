<!doctype html>
<html lang="pt-br">

<?php

// Verifica a sessão
include("verify.php");


//Impote de classe
use App\SelectUsuario\SelectUsuario;


//Chamada com retorno de uma querys
$cursos = SelectUsuario::TrazCursos();


@$erro_mb = base64_decode($_GET['msg']);

?>

<?php

// Nome da pagina
define("NOME_PAGINA", "Enviar material");

// Head
include("includes/head.php");

?>

<body>

    <div class="wrapper d-flex align-items-stretch">

        <!-- NAVBAR -->
        <?php include("includes/nav.php"); ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <!-- NAVBAR NAMEUSER -->
            <?php include("includes/navUser.php"); ?>

            <h2 class="mb-5">Envio material de apoio

                <?= $erro_mb ?>

                <?php
                // Retorno de erro no envio
                if (isset($_SESSION['envioInvalidoGestor'])) :
                ?>
                    - <span class="badge badge-danger">Algum campo vazio</span>
                <?php endif;
                unset($_SESSION['envioInvalidoGestor'])
                // Retorno de erro no envio
                ?>

            </h2>


            <div class="container">
                <div class="row">

                    <div class="col-md-6">
                        <!-- FORM -->
                        <form action="../data/envio_gestor.php" method="POST" name="form_main" enctype="multipart/form-data">

                            <textarea class="form-control mb-4" rows="3" maxlength="100" id="descricao" name="descricao" placeholder="Descrição" oninput="countText()"></textarea>
                            <span>Caracteres: <span id='caracteres'>0</span></span><br>
                            <b><span>Limite de caracteres: <span class="text-danger">100</span></span></b>

                            <script>
                                function countText() {
                                    let descricao = document.form_main.descricao.value;
                                    document.getElementById('caracteres').innerText = descricao.length;

                                }
                            </script>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-4">
                            <select class="form-control mb-4 js-example-basic-single" name="curso" aria-label="Default select example">
                                <option selected>CURSOS</option>

                                <?php
                                //Traz os dados do banco e forma o elemento na tela
                                foreach ($cursos as $curso) : ?>
                                    <option><?= $curso['NOME'] ?></option>
                                <?php endforeach;
                                //Traz os dados do banco e forma o elemento na tela
                                ?>

                            </select>
                        </div>

                        <div class="custom-file mb-4">
                            <input type="file" class="custom-file-input" name="file[]" id="customFileLang" lang="pt-br" multiple>
                            <label class="custom-file-label" for="customFileLang">Selecione os PDFS</label>
                        </div>

                        <button class="btn btn-primary">Enviar material</button>

                        </form>
                        <!-- FORM -->
                    </div>

                </div>
            </div>



        </div>
    </div>


    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
</body>

</html>