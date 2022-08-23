<!doctype html>
<html lang="pt-br">

<?php

// Verifica a sessão
include("verify.php");

//Importe de classe
use App\SelectUsuario\SelectUsuario;

//Chamada com retorno de uma query
$cursos = SelectUsuario::TrazCursos();

//Valida se o parametro curso e vazio
$postValidado = empty($_POST['cursos']) ? 'CURSOS' : $_POST['cursos'];

//Armazena o parametro em $cursoPost
@$cursoPost = filter_var($postValidado, FILTER_SANITIZE_ADD_SLASHES);

//Chamada com retorno de uma query
$material = SelectUsuario::TrazMaterial($cursoPost);

@$erro = base64_decode($_GET['erro']);

?>

<?php

// Nome da pagina
define("NOME_PAGINA", "Material de apoio");

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

      <h2 class="mb-4"><?= NOME_PAGINA ?> <?= $erro ?>
        <form action="" method="POST">
          <select class="inputPesquisa mb-4 js-example-basic-single" name="cursos" aria-label="Default select example">
            <option selected>CURSOS</option>

            <?php
            //Traz os dados do banco e forma o elemento na tela
            foreach ($cursos as $curso) : ?>
              <option><?= $curso['NOME'] ?></option>
            <?php endforeach;
            //Traz os dados do banco e forma o elemento na tela
            ?>

          </select>
          <button class="btn btn-primary">Buscar</button>
        </form>

      </h2>

      <div class="list-group" id='resposta'>

        <?php
        //Verifica se o retorno da query existe
        if (count($material) != 0) {  ?>

          <?php

          //Traz os dados do banco e forma o elemento na tela
          foreach ($material as $dados) { ?>

            <a href="verArquivoEnviado.php?arquivo=<?= base64_encode($dados['REGISTRO']) ?>" class="list-group-item list-group-item-action">
              <div class="display-delete">

                <span>Ir para downloads: <strong><?= $dados['DESCRICAO'] ?></strong> | Número de anexos: <?= count(explode(', ', $dados['ARQUIVO'])); ?></span>
                

                <form action="../data/deletarMaterial.php" method="POST">
                  <input type="hidden" value="<?= base64_encode($dados['ARQUIVO']) ?>" name="arq">
                  <button value="<?= base64_encode($dados['REGISTRO']) ?>" name="btn-delete-material" class="btn-delete"><i style="color: #ffff !important" class="fa fa-trash" aria-hidden="true"></i></button>
                </form>
 
              </div>
            </a>
          <?php } //FIMFOREACH
          //Traz os dados do banco e forma o elemento na tela

        } else { ?>

          <?php if ($cursoPost == 'CURSOS') { ?>

            <p>Selecione um curso</p>

          <?php } else { ?>

            <p>Nenhum resultado encontrado <?= $cursoPost ?></p>

        <?php }
        } ?>

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