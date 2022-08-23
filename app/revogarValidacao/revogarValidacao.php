<?php

namespace App\revogarValidacao;

use App\DbInsercao\DbInsercao;

class revogarValidacao
{
    public static function RevogarValidacao1($ref)
    {
        session_start();
        $matricula = $_SESSION['raLog'];

        return (new DbInsercao('CENTRAL_ATIVIDADE'))->update('REGISTRO = ' . $ref, [
            'VALIDACAO' => NULL,
            'MODALIDADE' => NULL,
            'COMPONENTE' => NULL,
            'DATAVALIDACAO' => NULL,
            'USUARIOVALIDACAO' => $matricula

        ]);
    }

    public static function RevogarValidacao0($ref)
    {
        session_start();
        $matricula = $_SESSION['raLog'];

        return (new DbInsercao('CENTRAL_ATIVIDADE'))->update('REGISTRO = ' . $ref, [
            'VALIDACAO' => NULL,
            'MODALIDADE' => NULL,
            'COMPONENTE' => NULL,
            'DATAVALIDACAO' => NULL,
            'OBSINDEFERIDO' => NULL,
            'USUARIOVALIDACAO' => $matricula

        ]);
    }
}
