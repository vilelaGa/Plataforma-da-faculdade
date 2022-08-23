<!doctype html>
<html lang="pt-br">


<?php

// Verifica a sessão
include("verify.php");

// echo date("d/m/Y");

?>

<?php

// Nome da pagina
define("NOME_PAGINA", "Home");

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

      <h2 class="mb-4">Olá, Colaborador!
        Seja bem-vindo.</h2>

      <!-- Carrossel -->
      <?php include("../carrossel.php") ?>
      <!-- Carrossel -->

    </div>
  </div>

  <script src="../assets/js/popper.js"></script>
  <script src="../assets/js/main.js"></script>
</body>

</html>