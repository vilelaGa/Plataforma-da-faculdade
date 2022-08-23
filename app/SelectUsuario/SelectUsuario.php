<?php

namespace App\SelectUsuario;


use App\DbConsulta\DbConsulta;
use App\DbInsercao\DbInsercao;
use PDO;


class SelectUsuario
{
    /**
     * Função que traz dados do usuario pelo ra
     * 1 Parametro
     * @var int ra
     */
    public static function TrazRaDb($ra)
    {
        $var = (new DbConsulta('GUSUARIO'))->select("CODUSUARIO = '$ra'")
            ->fetch(PDO::FETCH_ASSOC);

        global $varGestor;
        global $raReturnDb;

        //Variavel recebida no gestor/verify.php
        $varGestor = $var;

        //Variavel recebida no aluno/recebeKey.php
        $raReturnDb = $var['CODUSUARIO'];
    }


    /**
     * Função que traz chapa (OBS: CHAPA E A MATRICOLA MAIS ANTIGA QUE PEGA SO UM ZERO)
     * 1 Parametro
     * @var int ra
     */
    public static function TRAZCHAPA($ra)
    {
        $var = (new DbConsulta('GUSUARIO'))->LOGIN("PFUNC PFU    (NOLOCK) INNER JOIN
        PPESSOA PPE  (NOLOCK) ON PFU.CODPESSOA = PPE.CODIGO INNER JOIN
        GUSUARIO GUS (NOLOCK)ON   RIGHT(REPLICATE('0', 6) + GUS.CODUSUARIO, 6) = PFU.CHAPA INNER JOIN 
        GUSRPERFIL  (NOLOCK) ON GUS.CODUSUARIO = GUSRPERFIL.CODUSUARIO ", "CODSITUACAO <> 'D' AND PFU.CODTIPO <> 'A' AND CHAPA = '$ra' AND CODPERFIL IN ('NTI', 'NPDA') AND GUSRPERFIL.CODSISTEMA = 'S'")
            ->fetch(PDO::FETCH_ASSOC);

        global $varGestor;
        global $raReturnDb;

        //Variavel recebida no gestor/verify.php
        $varGestor = $var;

        //Variavel recebida no aluno/recebeKey.php
        $raReturnDb = $var['CODUSUARIO'];
    }


    /**
     * Função que traz o codigo da grade
     * 1 Parametro
     * @var int idHabilitação
     */
    public static function TrazCodGrade($id_habilitacao)
    {
        $dado = (new DbConsulta('SHABILITACAOFILIAL'))->select("IDHABILITACAOFILIAL = '$id_habilitacao'")
            ->fetch(PDO::FETCH_ASSOC);

        return $dado['CODGRADE'];
    }


    /**
     * Função que traz os cursos ativos
     * 0 Parametro
     */
    public static function TrazCursos()
    {
        $cursos = (new DbConsulta())->innerTrazCursos('SMATRICPL SMA (NOLOCK) INNER JOIN
        SPLETIVO  SPL (NOLOCK) ON SMA.CODCOLIGADA = SPL.CODCOLIGADA AND SMA.IDPERLET = SPL.IDPERLET INNER JOIN
        SHABILITACAOFILIAL SHF (NOLOCK) ON SMA.CODCOLIGADA = SHF.CODCOLIGADA AND SMA.IDHABILITACAOFILIAL = SHF.IDHABILITACAOFILIAL INNER JOIN
        SCURSO SCU (NOLOCK) ON SHF.CODCOLIGADA = SCU.CODCOLIGADA AND SHF.CODCURSO = SCU.CODCURSO', 'LEFT(SPL.CODPERLET, 4) = 2022 AND SPL.CODTIPOCURSO IN (1, 8)')
            ->fetchAll(PDO::FETCH_ASSOC);

        return $cursos;
    }

    /**
     * Função que traz os atividade feitas
     * 1 Parametro
     * @var int $ra
     */
    public static function TrazAtividadesRegistro($ra)
    {
        $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("RA = '$ra'")
            ->fetchAll(PDO::FETCH_ASSOC);
        return $atividade;
    }


    /**
     * Função que traz os materiais postados
     * 1 Parametro
     * @var int $numero_curso
     */
    public static function TrazMaterial($cursos)
    {
        $material = (new DbInsercao('CENTRAL_APOIO'))->select("CURSO = '$cursos'")
            ->fetchAll(PDO::FETCH_ASSOC);
        return $material;
    }


    /**
     * Função que traz os materiais postados com base no numero de registro
     * 1 Parametro
     * @var int $registro
     */
    public static function TrazMaterialRegistro($registro)
    {
        $materialRegistro = (new DbInsercao('CENTRAL_APOIO'))->select("REGISTRO = '$registro'")
            ->fetch(PDO::FETCH_ASSOC);
        return $materialRegistro;
    }


    /**
     * Função que traz todas as atividades
     * 3 Parametro
     * @var int $codigoCurso @var int $ra @var int $active (OBS: "ACTIVE" ATIVA A FUNÇÃO DESEJADA)
     */
    public static function TrazAtividadesTudo($codCurso, $ra, $active, $nomeAluno)
    {

        switch ($active) {
            case ('pesq'):
                if (empty($ra) && $codCurso != 'CURSOS') {
                    $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "CENTRAL.CODCURSO = '$codCurso'")
                        ->fetchAll(PDO::FETCH_ASSOC);
                    return $atividade;
                } else if (!empty($ra) && $codCurso == 'CURSOS') {
                    $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "CENTRAL.RA = '$ra'")
                        ->fetchAll(PDO::FETCH_ASSOC);
                    return $atividade;
                } else if (!empty($ra) && $codCurso != 'CURSOS') {
                    $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "CENTRAL.RA = '$ra' AND CENTRAL.CODCURSO = '$codCurso'")
                        ->fetchAll(PDO::FETCH_ASSOC);
                    return $atividade;
                } else if (!empty($nomeAluno)) {
                    $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "PE.NOME LIKE '%$nomeAluno%'")
                        ->fetchAll(PDO::FETCH_ASSOC);
                    return $atividade;
                } else if (empty($ra) && $codCurso == 'CURSOS') {
                    $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "CENTRAL.RA = '$ra' AND CENTRAL.CODCURSO = '$codCurso'")
                        ->fetchAll(PDO::FETCH_ASSOC);
                    return $atividade;
                }
                break;
            case ('vali'):
                $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "CENTRAL.VALIDACAO = 1")
                    ->fetchAll(PDO::FETCH_ASSOC);
                return $atividade;
                break;
            case ('indf'):
                $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "CENTRAL.VALIDACAO = 0")
                    ->fetchAll(PDO::FETCH_ASSOC);
                return $atividade;
                break;
            case ('asere'):
                $atividade = (new DbInsercao())->filtroCursos("RM.CORPORERM.DBO.SALUNO SA (NOLOCK) INNER JOIN RM.CORPORERM.DBO.PPESSOA PE (NOLOCK) ON SA.CODPESSOA = PE.CODIGO INNER JOIN CENTRAL_ATIVIDADE CENTRAL (NOLOCK) ON SA.RA = CENTRAL.RA", "CENTRAL.VALIDACAO IS NULL")
                    ->fetchAll(PDO::FETCH_ASSOC);
                return $atividade;
                break;
            case (''):
                return $atividade = [];
                break;
        }
    }


    /**
     * Função que traz todas as atividades
     * 3 Parametro
     * @var int $codigoCurso @var int $ra @var int $active (OBS: "ACTIVE" ATIVA A FUNÇÃO DESEJADA)
     */
    // public static function TrazAtividadesTudo($codCurso, $ra, $active)
    // {

    //     switch ($active) {
    //         case ('pesq'):
    //             if (empty($ra) && $codCurso != 'CURSOS') {
    //                 $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("CODCURSO = '$codCurso'")
    //                     ->fetchAll(PDO::FETCH_ASSOC);
    //                 return $atividade;
    //             } else if (!empty($ra) && $codCurso == 'CURSOS') {
    //                 $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("RA = '$ra'")
    //                     ->fetchAll(PDO::FETCH_ASSOC);
    //                 return $atividade;
    //             } else if (!empty($ra) && $codCurso != 'CURSOS') {
    //                 $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("RA = '$ra' AND CODCURSO = '$codCurso'")
    //                     ->fetchAll(PDO::FETCH_ASSOC);
    //                 return $atividade;
    //             } else if (empty($ra) && $codCurso == 'CURSOS') {
    //                 $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("RA = '$ra' AND CODCURSO = '$codCurso'")
    //                     ->fetchAll(PDO::FETCH_ASSOC);
    //                 return $atividade;
    //             }
    //             break;
    //         case ('vali'):
    //             $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("VALIDACAO = 1")
    //                 ->fetchAll(PDO::FETCH_ASSOC);
    //             return $atividade;
    //             break;
    //         case ('indf'):
    //             $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("VALIDACAO = 0")
    //                 ->fetchAll(PDO::FETCH_ASSOC);
    //             return $atividade;
    //             break;
    //         case ('asere'):
    //             $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("VALIDACAO IS NULL")
    //                 ->fetchAll(PDO::FETCH_ASSOC);
    //             return $atividade;
    //             break;
    //         case (''):
    //             return $atividade = [];
    //             break;
    //     }
    // }


    /**
     * Função que traz atividades com base no registro
     * 1 Parametro
     * @var int $ra
     */
    public static function TrazAtividade($registro)
    {
        $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->select("REGISTRO = '$registro'")
            ->fetch(PDO::FETCH_ASSOC);
        return $atividade;
    }


    /**
     * Função que cria a paginação de atividades enviadas
     * 3 Parametro
     * @var int $ra @var int $pagina_atual @var int $itens_por_pagina
     */
    public static function TrazAtividadesPaginacao($ra, $pagina, $itens_por_pagina)
    {
        $atividade = (new DbInsercao('CENTRAL_ATIVIDADE'))->selectPaginacao($ra, $pagina, $itens_por_pagina)
            ->fetchAll(PDO::FETCH_ASSOC);
        return $atividade;
    }


    /**
     * Função que pegar o curso do aluno
     * 1 Parametro
     * @var int $codigoCurso 
     */
    public static function cursoAluno($codigo)
    {
        $curso = (new DbConsulta('SCURSO'))->select("CODCURSO = '$codigo'")
            ->fetch(PDO::FETCH_ASSOC);
        return $curso;
    }
}
