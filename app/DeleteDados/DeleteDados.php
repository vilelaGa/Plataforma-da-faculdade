<?php

namespace App\DeleteDados;

use App\DbInsercao\DbInsercao;


class DeleteDados
{

    /**
     * Função para deletar atividades
     * 2 Parametros 
     * @var string registro
     * @var string ra
     */
    public static function DeleteAtividades($registro, $ra)
    {
        return (new DbInsercao("CENTRAL_ATIVIDADE"))->delete("REGISTRO = " . $registro . " AND (VALIDACAO IS NULL OR VALIDACAO = 0)" . " AND RA = $ra");
    }


    /**
     * Função para deletar material postado
     * 2 Parametros 
     * @var string registro
     * @var string ra
     */
    public static function DeleteMaterial($registro)
    {
        return (new DbInsercao("CENTRAL_APOIO"))->delete("REGISTRO = " . $registro);
    }
}
