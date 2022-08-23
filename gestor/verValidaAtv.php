<!doctype html>
<html lang="pt-br">


<?php

// Verifica a sessão
include("verify.php");

//Importe de classe
use App\SelectUsuario\SelectUsuario;
use App\Validacao\Validacao;

//Armagena o parametro get na variavel $registro
$registro = filter_var(base64_decode($_GET['ref']), FILTER_SANITIZE_ADD_SLASHES);

//Valida registro
$res = Validacao::ValidaRegistroValidacao($registro);


//Chamada com retorno de uma query
$atividade = SelectUsuario::TrazAtividade($registro);

//Parametro de erro
@$erro = base64_decode($_GET['erro']);

$curso = SelectUsuario::cursoAluno($atividade['CODCURSO']);

?>

<?php

// Nome da pagina
define("NOME_PAGINA", "Ver Atividade");

// Head
include("includes/head.php");

?>
<style>
    #pdf-exemplo {
        border: 1px solid black;
    }
</style>

<body>

    <div class="wrapper d-flex align-items-stretch">

        <!-- NAVBAR -->
        <?php include("includes/nav.php"); ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <!-- NAVBAR NAMEUSER -->
            <?php include("includes/navUser.php"); ?>

            <h2 class="mb-4">Validação de atividade
            </h2>

            <div>
                <!-- BLOCO DE VALIDAÇÃO -->
                <?php if ($atividade['VALIDACAO'] == NULL) { ?>
                    <span class="badge badge-secondary">Aguarando avaliação</span>
                <?php } else if ($atividade['VALIDACAO'] == 1) { ?>
                    <span class="badge badge-success">Atividade validada</span>
                <?php } else if ($atividade['VALIDACAO'] == 0) { ?>
                    <span class="badge badge-danger">Atividade não validada</span>
                <?php } ?>
                <!-- BLOCO DE VALIDAÇÃO -->
                <?= $erro ?>
            </div>


            <div class="container">
                <div class="row">

                    <div class="col-md-6">
                        <!-- FORM -->
                        <form action="../data/verificaAtv.php" name="form_main" method="POST">

                            <div class="row mb-4">
                                <input type="hidden" name="arq" value="<?= $atividade['ARQUIVO'] ?>">
                                <div class="col-md-6">
                                    <label>Data Inicial</label>
                                    <input class="form-control" placeholder="<?php
                                                                                $dataReplace = str_replace('00:00:00.000', '', $atividade['DATAINICIO']);
                                                                                $explode = explode('-', $dataReplace);
                                                                                echo $explode[2] . '/' . $explode[1] . '/' . $explode[0]; ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>Data Final</label>
                                    <input class="form-control" placeholder="<?php
                                                                                $dataReplace = str_replace('00:00:00.000', '', $atividade['DATAFIM']);
                                                                                $explode = explode('-', $dataReplace);
                                                                                echo $explode[2] . '/' . $explode[1] . '/' . $explode[0];
                                                                                ?>" disabled>
                                </div>
                            </div>
                            <label>Carga horaria</label>
                            <input class="form-control mb-4" name="carga" value="<?= $atividade['CARGAHORARIA'] ?>" placeholder="<?= $atividade['CARGAHORARIA'] ?>">

                            <?php
                            //BLOCO VALIDAÇÃO
                            if ($atividade['OBSERVACAO'] == NULL) { ?>
                            <?php } else { ?>
                                <label>Observação do aluno</label>
                                <textarea class="form-control mb-4" rows="1" placeholder="<?= $atividade['OBSERVACAO'] ?>" disabled></textarea>
                            <?php }
                            //BLOCO VALIDAÇÃO
                            ?>

                            <label>Descrição</label>
                            <textarea class="form-control" rows="2" maxlength="60" id='descr' name="descr" placeholder="<?= $atividade['DESCRICAO'] ?>" oninput="countText()"><?= $atividade['DESCRICAO'] ?></textarea>
                            <span>Caracteres: <b><span id='caracteres'>0</span></b> | </span>
                            <b><span>Limite de caracteres: <span class="text-danger">60</span></span></b>
                            <br>
                            <script>
                                function countText() {
                                    let descr = document.form_main.descr.value;
                                    document.getElementById('caracteres').innerText = descr.length;

                                }
                            </script>
                            <br>
                            <?php
                            //BLOCO VALIDAÇÃO
                            if ($atividade['VALIDACAO'] == NULL) {  ?>
                                <div>

                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link btn btn-success" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Validar</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link btn btn-danger" id="profile-tab" id="esconde" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Indeferido</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                                            <div class="container">

                                                <select class="form-control mt-4" name="modalidade">
                                                    <option selected>Modalidade</option>
                                                    <option>Ensino</option>
                                                    <option>Pesquisa</option>
                                                    <option>Extensão</option>
                                                    <!-- <option>Indeferido</option> -->
                                                </select>
                                                <button class="btn btn-primary mt-3" name="validar" value="<?= base64_encode($atividade['REGISTRO']) ?>">Enviar</button>

                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                                            <div class="container">
                                                <textarea class="form-control mt-4" rows="3" name="obsIndeferido" placeholder="Observação do indeferimento"></textarea>
                                                <button class="btn btn-primary mt-3" name="revogar" value="<?= base64_encode($atividade['REGISTRO']) ?>">Enviar</button>
                        </form>
                        <!-- FORM -->

                    </div>

                </div>

            </div>

        </div>
    <?php } else { ?>

        <?php if ($atividade['MIGRACAO'] == NULL) { ?>

            <a class="btn btn-warning" href="../data/revogarValidacao.php?referencia_revoga=<?= base64_encode($atividade['REGISTRO']) ?>">Revogar validação</a>

        <?php } else { ?>

            <span style="font-size: 20px;" class="badge badge-warning">Já foi migrado</span>

        <?php } ?>


        <script>
            //SCRIPT BTN VALIDA E INDEFERIDO
            // document.querySelector('input[name="dtI"]').disabled = true;
            // document.querySelector('input[name="dtF"]').disabled = true;
            document.querySelector('input[name="carga"]').disabled = true;
            document.querySelector('textarea[name="descr"]').disabled = true;
            // document.querySelector('textarea[name="obs"]').disabled = true;
        </script>
    <?php }
                            //BLOCO VALIDAÇÃO
    ?>

    </div>



    <div class="col-md-6">

        <span>CURSO: <b class="text-danger"><?= $curso['NOME'] ?></b></span> -
        <span>RA: <b><?= $atividade['RA'] ?></b></span>

        </form>
        <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

        <!-- CONDE COSPE O PDF -->
        <div class="mt-4">
            <button id="prev">Proxima página</button>
            <button id="next">Página anterior</button>
            &nbsp; &nbsp;
            <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
        </div>
        <canvas id="pdf-exemplo"></canvas>
        <!-- CONDE COSPE O PDF -->


    </div>
    </div>
    </div>
    <!-- FORMS -->

    </div>
    </div>

    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/main.js"></script>

    <script>
        //SCRIPT BTN VALIDA E INDEFERIDO
        document.getElementById('profile-tab').addEventListener('click', function() {
            // document.querySelector('input[name="dtI"]').disabled = true;
            // document.querySelector('input[name="dtF"]').disabled = true;
            document.querySelector('input[name="carga"]').disabled = true;
            document.querySelector('textarea[name="descr"]').disabled = true;
            document.querySelector('select[name="modalidade"]').disabled = true;
            document.querySelector('textarea[name="obsIndeferido"]').disabled = false;
            document.querySelector('textarea[name="obs"]').disabled = true;


        });
        document.getElementById('home-tab').addEventListener('click', function() {
            // document.querySelector('input[name="dtI"]').disabled = false;
            // document.querySelector('input[name="dtF"]').disabled = false;
            document.querySelector('input[name="carga"]').disabled = false;
            document.querySelector('textarea[name="descr"]').disabled = false;
            document.querySelector('select[name="modalidade"]').disabled = false;
            document.querySelector('textarea[name="obsIndeferido"]').disabled = true;
            document.querySelector('textarea[name="obs"]').disabled = false;

        });
    </script>

    <?php
    //BLOCO VALIDAÇÃO
    if ($atividade['VALIDACAO'] == NULL) {
        $caminhoJs = "http://sistema.ubm.br:8090/atividade_complementar/data/uploadAtividadeAvaliacao/" . $atividade['ARQUIVO'];
    } else if ($atividade['VALIDACAO'] == 1) {
        $caminhoJs = "http://sistema.ubm.br:8090/atividade_complementar/data/uploadMorto/" . $atividade['ARQUIVO'];
    } else if ($atividade['VALIDACAO'] == 0) {
        $caminhoJs = "http://sistema.ubm.br:8090/atividade_complementar/data/uploadIndeferido/" . $atividade['ARQUIVO'];
    }
    //BLOCO VALIDAÇÃO
    ?>


    <script>
        //SCRIPT PEGA PDF COSPE NA TELA
        var url = '<?= $caminhoJs ?>';

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