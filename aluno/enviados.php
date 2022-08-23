<!doctype html>
<html lang="pt-br">

<?php

//Verifica a sessão
include("verify.php");

//Class que contem todas as querys SELECT
use App\SelectUsuario\SelectUsuario;

//Chamada com retorno de uma query
$dados = SelectUsuario::TrazAtividadesRegistro($response->data[0]->RA);

//Get para o retorno de um erro
@$erro = base64_decode($_GET['erro']);


//==================PAGINAÇÃO==================


// Numero de itens que vai aparecer na pagina
$itens_por_pagina = 3;

@$pagina = empty(filter_var(intval($_GET['pagina']), FILTER_SANITIZE_ADD_SLASHES)) ? 1 : filter_var(intval($_GET['pagina']), FILTER_SANITIZE_ADD_SLASHES);

$dadoPaginacao = SelectUsuario::TrazAtividadesPaginacao($response->data[0]->RA, $pagina, $itens_por_pagina);

$num_total = ceil(count($dados) / $itens_por_pagina);

//==================PAGINAÇÃO==================
?>

<?php

// Nome da pagina
define("NOME_PAGINA", "Envios");

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

      <h2 class="mb-4"><?= NOME_PAGINA ?> <?= $erro ?></h2>

      <!-- CARD ENVIO ATIVIDADES -->
      <?php
      //Verifica se o retorno da query existe
      if (count($dadoPaginacao) != 0) { ?>
        <?php
        //Traz os dados do banco e forma o elemento na tela
        foreach ($dadoPaginacao as $linha) { ?>

          <div class="atividadesEnviadas mb-3">

            <div>

              <p>Atividade: <?= $linha['DESCRICAO'] ?> -

                <!-- BLOCO DE VALIDAÇÃO DE ATIVIDADE -->
                <?php if ($linha['VALIDACAO'] == NULL) { ?>
                  <span class="badge badge-secondary">Aguarando avaliação</span> -
                  <a href="../data/uploadAtividadeAvaliacao/<?= $linha['ARQUIVO'] ?>" target="_blank">Vizualizar Arquivo enviado</a>
                <?php } else if ($linha['VALIDACAO'] == 1) { ?>
                  <span class="badge badge-success">Atividade validada</span> -
                  <a href="../data/uploadMorto/<?= $linha['ARQUIVO'] ?>" target="_blank">Vizualizar Arquivo enviado</a>
                <?php } else if ($linha['VALIDACAO'] == 0) { ?>
                  <span class="badge badge-danger">Atividade não validada</span> -
                  <a href="../data/uploadIndeferido/<?= $linha['ARQUIVO'] ?>" target="_blank">Vizualizar Arquivo enviado</a>
                  <br>
                  <hr>
                  <span> Motivo do indeferimento: <?= $linha['OBSINDEFERIDO'] ?></span>
                <?php } ?>
                <!-- BLOCO DE VALIDAÇÃO DE ATIVIDADE -->

              </p>

            </div>

            <!-- BLOCO BTN VALIDA -->
            <div>

              <?php if (($linha['VALIDACAO'] == NULL) || ($linha['VALIDACAO'] == 0)) { ?>

                <form action="../data/deletarAtividade.php" method="POST">
                  <input type="hidden" value="<?= base64_encode($linha['ARQUIVO']) ?>" name="arq">
                  <button style="font-size: 12px;" value="<?= base64_encode($linha['REGISTRO']) ?>" name="deletar" class="btn btn-danger">Excluir</button>



                  <?php if ($linha['VALIDACAO'] == NULL) { ?>
                    <a href="editar.php?atv=<?= base64_encode($linha['REGISTRO']) ?>" style="font-size: 12px;" class="btn btn-primary">Editar</a>
                  <?php } ?>
                </form>

              <?php } else { ?>

                <span style="font-size: 12px;" class="btn btn-primary" disabled>Atividade ja foi validada</span>

              <?php } ?>

            </div>
            <!-- BLOCO BTN VALIDA -->

          </div>

        <?php } //FIMFOREACH 
        ?>

      <?php } else { ?>
        <p>Nenhuma atividade registrada</p>
      <?php } ?>

      <!-- CARD ENVIO ATIVIDADES -->


      <nav aria-label="...">
        <ul class="pagination pagination-sm">
          <?php for ($i = 0; $i < $num_total; $i++) { ?>
            <li class="page-item"><a class="page-link" href="enviados.php?pagina=<?= $i + 1 ?>"><?= $i + 1 ?></a></li>
          <?php } ?>
        </ul>
      </nav>


    </div>
  </div>


  <script src="../assets/js/popper.js"></script>
  <script src="../assets/js/main.js"></script>
</body>

</html>