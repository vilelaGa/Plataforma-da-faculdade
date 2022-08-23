<!doctype html>
<html lang="pt-br">

<?php

use App\SelectUsuario\SelectUsuario;
use App\Validacao\Validacao;
use App\Api\Api;

include("verify.php");

?>

<?php

require_once '../vendor/autoload.php';

// Nome da pagina
define("NOME_PAGINA", "Editar");

// Head
include("includes/head.php");

Api::UserAPI($_SESSION['KeyUser']);

$res = filter_var(base64_decode($_GET['atv']), FILTER_SANITIZE_ADD_SLASHES);

$ret = Validacao::ValidaRegistro($res, $response->data[0]->RA);

// echo $ret;


// echo $res; 

$dados = SelectUsuario::TrazAtividade($res);


if ($dados['VALIDACAO'] != NULL) {
    header("Location: enviados.php");
}

?>

<body>

    <style>
        #pdf-exemplo {
            border: 1px solid black;
        }
    </style>


    <div class="wrapper d-flex align-items-stretch">

        <!-- NAVBAR -->
        <?php include("includes/nav.php"); ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <!-- NAVBAR NAMEUSER -->
            <?php include("includes/navUser.php"); ?>

            <h2 class="mb-4">Editar atividade

                <?php
                //Aviso Envio invalido

                if (isset($_SESSION['editarInvalido'])) :
                ?>
                    - <span class="badge badge-danger">Algum campo inválido</span>
                <?php endif;
                unset($_SESSION['editarInvalido'])

                //Aviso Envio invalido
                ?>

            </h2>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">

                        <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

                        <!-- CONDE COSPE O PDF -->
                        <div class="mt-4">
                            <button id="prev">Proxima página</button>
                            <button id="next">Página anterior</button>
                            &nbsp; &nbsp;
                            <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
                        </div>
                        <canvas id="pdf-exemplo"></canvas>

                    </div>
                    <div class="col-md-6">
                        <!-- FORM -->
                        <form action="../data/editaAtv.php" method="POST" name='form_main' enctype="multipart/form-data">

                            <input type="hidden" name="registro" value="<?= $dados['REGISTRO'] ?>">

                            <div class="row mb-4">
                                <input type="hidden" name="arq" value="<?= $dados['ARQUIVO'] ?>">
                                <div class="col-md-6">
                                    <label>Data Inicial</label>
                                    <input class="form-control" placeholder="<?php
                                                                                $dataReplace = str_replace(' 00:00:00.000', '', $dados['DATAINICIO']);
                                                                                $explode = explode('-', $dataReplace);
                                                                                echo $explode[2] . '/' . $explode[1] . '/' . $explode[0];
                                                                                ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>Data Final</label>
                                    <input class="form-control" placeholder="<?php
                                                                                $dataReplace = str_replace(' 00:00:00.000', '', $dados['DATAFIM']);
                                                                                $explode = explode('-', $dataReplace);
                                                                                echo $explode[2] . '/' . $explode[1] . '/' . $explode[0];
                                                                                ?>" disabled>
                                </div>
                            </div>

                            <label>Carga horaria</label>
                            <input class="form-control mb-4" name="carga" value="<?= $dados['CARGAHORARIA'] ?>" placeholder="<?= $dados['CARGAHORARIA'] ?>">


                            <label>Observação do aluno</label>
                            <textarea class="form-control mb-4" rows="1" name="obs" placeholder="<?= $dados['OBSERVACAO'] ?>"><?= $dados['OBSERVACAO'] ?></textarea>


                            <label>Descrição</label>
                            <textarea class="form-control" rows="2" id='descr' maxlength="60" name="descr" placeholder="<?= $dados['DESCRICAO'] ?>" oninput="countText()"><?= $dados['DESCRICAO'] ?></textarea>

                            <span>Caracteres: <b><span id='caracteres'>0</span></b> | </span>
                            <b><span>Limite de caracteres: <span class="text-danger">60</span></span></b>
                            <script>
                                function countText() {
                                    let descr = document.form_main.descr.value;
                                    document.getElementById('caracteres').innerText = descr.length;

                                }
                            </script>

                            <div class="custom-file mt-3 mb-4">
                                <input type="file" name="file" class="custom-file-input" id="customFileLang" lang="pt-br">
                                <label class="custom-file-label" for="customFileLang">Selecione <b>um</b> PDF</label>
                            </div>

                            <button class="btn btn-primary">Atualizar</button>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/main.js"></script>

    <script>
        //SCRIPT PEGA PDF COSPE NA TELA
        var url = "http://sistema.ubm.br:8090/atividade_complementar/data/uploadAtividadeAvaliacao/<?= $dados['ARQUIVO'] ?>";

        var pdfjsLib = window['pdfjs-dist/build/pdf'];

        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

        var pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 0.8,
            canvas = document.getElementById('pdf-exemplo'),
            ctx = canvas.getContext('2d');


        function renderPage(num) {
            pageRendering = true;

            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport({
                    scale: scale
                });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            document.getElementById('page_num').textContent = num;
        }


        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        /**
         * mostra a página anterior
         */
        function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        }
        document.getElementById('prev').addEventListener('click', onPrevPage);

        /**
         * mostra a proxima página
         */
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        }
        document.getElementById('next').addEventListener('click', onNextPage);

        /**
         * Download assincrono do PDF.
         */
        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page_count').textContent = pdfDoc.numPages;

            renderPage(pageNum);
        });
    </script>
</body>

</html>