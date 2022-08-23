<?php

namespace App\EditarAtividade;

use App\DbInsercao\DbInsercao;

class EditarAtividade
{


    /**
     * Função para editar atividades
     * @param 5 
     * @var string registro
     * @var string ra
     * @var string carga horaia
     * @var string descrição
     * @var string obs
     */
    public static function EditarAtividade($registro, $ra, $cargaHor, $descri, $obs)
    {
        (new DbInsercao('CENTRAL_ATIVIDADE'))->update('RA = ' . $ra . ' AND REGISTRO = ' . $registro, [
            'CARGAHORARIA' => $cargaHor,
            'DESCRICAO' => $descri,
            'OBSERVACAO' => $obs,

        ]);
    }
}
