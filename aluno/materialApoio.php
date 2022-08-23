<!doctype html>
<html lang="pt-br">

<?php

//Verifica a sessão
include("verify.php");

//Importe da classe que tem todos as querys SELECT
use App\SelectUsuario\SelectUsuario;

//Chamada com retorno de uma query
$curso = SelectUsuario::TrazMaterial($response->data[0]->NOMECURSO);

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

      <h2 class="mb-4"><?= NOME_PAGINA ?> </h2>

      <div class="list-group">

        <?php
        //Verifica se o retorno da query existe
        if (count($curso) != 0) { ?>

          <?php
          //Traz os dados do banco e forma o elemento na tela
          foreach ($curso as $linha) { ?>

            <a href="verArquivoEnviado.php?arquivo=<?= base64_encode($linha['REGISTRO']) ?>" class="list-group-item list-group-item-action">Ir para downloads:  <strong><?= $linha['DESCRICAO'] ?></strong></a>

          <?php } //FIMFOREACH 
          ?>

        <?php } else { ?>
          <p>Nenhum material disponivel</p>
        <?php } ?>

      </div>

    </div>
  </div>


  <script src="../assets/js/popper.js"></script>
  <script src="../assets/js/main.js"></script>
</body>

</html>