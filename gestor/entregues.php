<!doctype html>
<html lang="pt-br">

<?php

// Verifica a sessão
include("verify.php");


//Importe de classe

use App\DbConsulta\DbConsulta;
use App\DbInsercao\DbInsercao;
use App\SelectUsuario\SelectUsuario;

//Chamada com retorno de uma query
$cursos = SelectUsuario::TrazCursos();

//valida se o parametro post e vazio ou n
$validaCodCurso = empty($_POST['codCurso']) ? 'CURSOS' : $_POST['codCurso'];

//Armazena o parametro post na variavel $codCursoPOST
$codCursoPOST = filter_var($validaCodCurso, FILTER_SANITIZE_ADD_SLASHES);

//POST do formulario da propia pagina
@$actv = filter_var($_POST['acti'], FILTER_SANITIZE_ADD_SLASHES);

//POST do formulario da propia pagina
@$raA = filter_var($_POST['ra'], FILTER_SANITIZE_ADD_SLASHES);

//POST do formulario da propia pagina
@$nomeAluno = filter_var($_POST['nome'], FILTER_SANITIZE_ADD_SLASHES);

@$nomeAluno = empty($nomeAluno) ? NULL : $nomeAluno;

//Parametro de erro
@$erro = base64_decode($_GET['erro']);

//Chamada com retorno de uma query
$atividade = SelectUsuario::TrazAtividadesTudo($codCursoPOST, $raA, $actv, $nomeAluno);

// $nome = (new DbConsulta())->inner('SALUNO INNER JOIN PPESSOA ON SALUNO.CODPESSOA = PPESSOA.CODIGO', 'RA = ', $linha['RA']);
// var_dump($nome);



?>

<?php

// Nome da pagina
define("NOME_PAGINA", "Entregues");

// Head
include("includes/head.php");

?>

<style>
    a {
        color: #000;
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

            <h2 class="mb-5">Entregues <?= $erro ?>

                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6">

                            <form action="" method="POST">
                                <select class="inputPesquisa mb-4 js-example-basic-single" name="codCurso" aria-label="Default select example">
                                    <option selected>CURSOS</option>

                                    <?php
                                    //Traz os dados do banco e forma o elemento na tela
                                    foreach ($cursos as $curso) : ?>
                                        <option value="<?= $curso['CODCURSO'] ?>"><?= $curso['NOME'] ?></option>
                                    <?php endforeach; //FIMFOREACH
                                    ?>

                                </select>
                                <input type="text" style="width: 49rem;" class="form-control mb-2" name="ra" placeholder="Ra do aluno">
                                <!-- <hr> -->
                                <h6>ou</h6>
                                <input type="text" style="width: 49rem;" class="form-control mb-2" name="nome" placeholder="Nome do aluno">
                                <button class="btn btn-primary" name="acti" value="pesq">Pesquisar</button>
                                <button class="btn btn-secondary" name="acti" value="vali">Validados</button>
                                <button class="btn btn-secondary" name="acti" value="indf">Indeferidos</button>
                                <button class="btn btn-secondary" name="acti" value="asere">A serem validados</button>
                            </form>


                        </div>

                    </div>


                </form>
            </h2>


            <div class="container">
                <div class="row">

                    <div class="fundoTable">

                        <h4>Tabela</h4>
                        <span><?= count($atividade) ?> resultados para a busca</span>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Atividade</th>
                                    <th scope="col">Data entregue</th>
                                    <th scope="col"></th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                //Verifica se o retorno da query existe
                                if (count($atividade) != 0) { ?>
                                    <?php
                                    //Traz os dados do banco e forma o elemento na tela
                                    foreach ($atividade as $linha) { ?>

                                        <?php
                                        // $ra = $linha['RA'];
                                        // $nome = (new DbConsulta())->inner('SALUNO (NOLOCK) INNER JOIN PPESSOA (NOLOCK) ON SALUNO.CODPESSOA = PPESSOA.CODIGO', 'RA = ' . $ra)
                                        //     ->fetchAll(PDO::FETCH_ASSOC);
                                        ?>
                                        <tr>
                                            <th scope="row"><a target="_blank"><?= ucwords(strtolower($linha['NOME'])) ?></a></th>
                                            <td><a target="_blank"><?= $linha['DESCRICAO'] ?></a></td>
                                            <td><a target="_blank">
                                                    <?php
                                                    $dataReplace = str_replace(' 00:00:00.000', '', $linha['DATACADASTRO']);

                                                    $explode = explode('-', $dataReplace);

                                                    echo $explode[2] . '/' . $explode[1] . '/' . $explode[0];
                                                    ?>
                                                </a></td>
                                            <td><a href="verValidaAtv.php?ref=<?= base64_encode($linha['REGISTRO']) ?>" class="btn btn-primary">Ver atividade</a></td>
                                            <td>

                                                <!-- BLOCO DE VALIDAÇÃO -->
                                                <?php if ($linha['VALIDACAO'] == NULL) { ?>
                                                    <span class="badge badge-secondary">Aguarando avaliação</span>
                                                <?php } else if ($linha['VALIDACAO'] == 1) { ?>
                                                    <span class="badge badge-success">Atividade validada</span>
                                                <?php } else if ($linha['VALIDACAO'] == 0) { ?>
                                                    <span class="badge badge-danger">Atividade não validada</span>
                                                <?php } ?>
                                                <!-- BLOCO DE VALIDAÇÃO -->

                                            </td>
                                        </tr>


                                    <?php }
                                } else { ?>

                                    <tr>
                                        <td>
                                            <p>Nenhum resultado disponivel</p>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>
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