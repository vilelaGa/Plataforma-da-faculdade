<?php

namespace App\Validacao;

use App\DbInsercao\DbInsercao;


class Validacao
{

    /**
     * Função que valida atividade dos alunos
     * 5 Parametro
     * @var int $registro @var string $modalidade @var int $cargaHoraria @var string $descricao @var date $dataverifc
     */
    public static function ValidacaoAtividade($registro, $modalidade, $cargaHor, $descri, $dataVerifica, $ra)
    {
        return (new DbInsercao('CENTRAL_ATIVIDADE'))->update('REGISTRO = ' . $registro, [
            'VALIDACAO' => 1,
            'MODALIDADE' => $modalidade,
            'COMPONENTE' => $modalidade,
            'CARGAHORARIA' => $cargaHor,
            'DESCRICAO' => $descri,
            'DATAVALIDACAO' => $dataVerifica,
            'USUARIOVALIDACAO' => $ra

        ]);
    }


    /**
     * Função que revoga atividade dos alunos
     * 3 Parametro
     * @var int $registro @var string $ObsInde @var date $dataverifc
     */
    public static function RevogaAtividade($registro, $obsIndeferido, $dataVerifica, $ra)
    {
        return (new DbInsercao('CENTRAL_ATIVIDADE'))->update('REGISTRO = ' . $registro, [
            'VALIDACAO' => 0,
            'OBSINDEFERIDO' => $obsIndeferido,
            'DATAVALIDACAO' => $dataVerifica,
            'USUARIOVALIDACAO' => $ra
        ]);
    }


    /**
     * Função que valida registro
     * 2 Parametro
     * @var int $registro @var string $ra
     */
    public static function ValidaRegistro($registro, $ra)
    {
        $var = (new DbInsercao('CENTRAL_ATIVIDADE'))->select('REGISTRO = ' . $registro . 'AND RA = ' . $ra);

        if ($var->rowCount() != 0) {
            
        } else {
            return header('Location: enviados.php');
        }
    }


     /**
     * Função que valida registro da atividade
     * 1 Parametro
     * @var int $registro
     */
    public static function ValidaRegistroValidacao($registro)
    {
        $var = (new DbInsercao('CENTRAL_ATIVIDADE'))->select('REGISTRO = ' . $registro);

        if ($var->rowCount() != 0) {
            
        } else {
            return header('Location: entregues.php');
        }
    }


     /**
     * Função que valida registro do material
     * 1 Parametro
     * @var int $registro
     */
    public static function ValidaRegistroMaterial($registro)
    {
        $var = (new DbInsercao('CENTRAL_APOIO'))->select('REGISTRO = ' . $registro);

        if ($var->rowCount() != 0) {
            
        } else {
            return header('Location: materialApoio.php');
        }
    }
}
