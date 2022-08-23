<?php

session_start();

require("../vendor/autoload.php");

//Importes de classes
use App\SelectUsuario\SelectUsuario;
use App\Api\Api;


//Variavel que recebe a key do usuario logado no rm
$KeyGet = base64_decode($_GET['keyAluno']);


//Validação para ver se o parametro get e vazio
$valida1 = empty($KeyGet) ? header("Location: https://intra.ubm.br:8080/web/app/edu/PortalEducacional/#/") : 'Parâmetro preenchido';


//Faz a consulta na api e retorna o ra do usuario
Api::UserAPI($KeyGet);


/**
 * Pega o retorno httpStatus para ver se a consulta funcionou na Api
 * Se retorna for 500 a chave não e valida, 
 * Se for 200 ou qualquer numero != de 500 chave valida
 */
if ($response->HttpStatusCode == 500) {
    echo ("<script>
        alert('Usuario inválido');
        window.location = 'https://intra.ubm.br:8080/web/app/edu/PortalEducacional/#/';
        </script>");
} else {
    $raReturnApi = $response->data[0]->RA;


    //Faz a consulta no DB e retorna o ra do usuario
    SelectUsuario::TrazRaDb($raReturnApi);
    $raReturnDb;


    // Validação retorno da api e retorno do db para autenticar o usuario
    if ($raReturnDb == $raReturnApi) {
        $_SESSION['KeyUser'] = $KeyGet;
        $_SESSION['hora_acesso'] = time();
        header("location: index.php");
    } else {
        echo ("<script>
        alert('Usuario inválido');
        window.location = 'https://intra.ubm.br:8080/web/app/edu/PortalEducacional/#/';
        </script>");
    }
}
