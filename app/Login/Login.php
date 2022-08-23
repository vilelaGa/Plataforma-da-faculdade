<?php

namespace App\Login;

use App\DbConsulta\DbConsulta;
use PDO;

class Login
{

    /**
     * Função que loga o colaborador
     * 2 Parametros necessarios
     * @var int ra
     * @var int cpf
     * OBS: CPF SÃO OS 6 PRIMEIROS DIGITOS 000000
     */
    public static function Login($ra, $cpf)
    {

        //FUNÇÃO LOGIN CHAMADA E UM SELECT personalizado DENTRO DE DBConsulta 
        $dadosUni = (new DbConsulta())->LOGIN("PFUNC PFU    (NOLOCK) INNER JOIN
        PPESSOA PPE  (NOLOCK) ON PFU.CODPESSOA = PPE.CODIGO INNER JOIN
        GUSUARIO GUS (NOLOCK)ON   RIGHT(REPLICATE('0', 6) + GUS.CODUSUARIO, 6) = PFU.CHAPA INNER JOIN 
        GUSRPERFIL  (NOLOCK) ON GUS.CODUSUARIO = GUSRPERFIL.CODUSUARIO ", "CODSITUACAO <> 'D' AND PFU.CODTIPO <> 'A' AND CHAPA = '$ra' AND CODPERFIL IN ('NTI', 'NPDA') AND GUSRPERFIL.CODSISTEMA = 'S'")
            ->fetch(PDO::FETCH_ASSOC);

        $cpfDb = $dadosUni['CPF'];
        // echo $cpfDb;
        // die();

        $splitCpf = str_split($cpfDb);

        $cpfSplitado = $splitCpf[0] . $splitCpf[1] . $splitCpf[2] . $splitCpf[3] . $splitCpf[4] . $splitCpf[5];

        if ($cpf == $cpfSplitado) {

            $raSession = $dadosUni['CHAPA'];
            $_SESSION['raLog'] = $raSession;
            header("Location: ../gestor/");
        } else {
            $_SESSION['userInvalido'] = true;
            header("Location: ../login.php");
        }
    }
}
