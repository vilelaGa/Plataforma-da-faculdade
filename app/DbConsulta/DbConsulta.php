<?php

namespace App\DbConsulta;

use PDOException;
use PDO;


class DbConsulta
{
    //Constantes de conexão com Db
    const SERVER = "";
    const DATABASE_NAME = "";
    const USER = "";
    const PASSWORD = "";

    //Variavel tabela
    private $tabela;

    /**
     * Conexão db
     * @var PDO
     */
    private $conexao;


    /**
     * Função define a tabela e instancia de conexão
     * 1 Parametro
     * @var string tabela
     */
    public function __construct($tabela = null)
    {
        $this->tabela = $tabela;
        $this->setConnection();
    }


    /**
     * Função que efetua a conexão com Db
     * OBS: CONEXÃO COM SQLSERVER Necessita de drives pdo_sqlsrv
     * Link donwload: https://docs.microsoft.com/pt-br/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver16
     * Link video instalação: https://www.youtube.com/watch?v=7spsRgc6AtE 
     */
    private function setConnection()
    {
        try {
            $this->conexao = new PDO('sqlsrv:Server=' . self::SERVER . '; Database=' . self::DATABASE_NAME,  self::USER, self::PASSWORD);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // die("ERRO DE CONEXÃO {$e->getMessage()}");
            die("ERRO: 405");
        }
    }


    /**
     * Função para execultar a query no Db
     * 1 Parametro 
     * @var string query
     */
    public function exculte($query)
    {
        try {
            $excQuery = $this->conexao->prepare($query);
            $excQuery->execute();
            return $excQuery;
        } catch (PDOException $e) {
            // die("ERRO NA QUERY {$e->getMessage()}");
            die("ERRO: 405");
        }
    }


    /**
     * Função Select com INNERS no Db
     * 1 Parametro 
     * @var string where
     */
    public function select($where = null)
    {
        $query = 'SELECT * FROM ' . $this->tabela . ' WHERE ' . $where;
        return $this->exculte($query);
    }


    /**
     * Função Select com INNERS no Db
     * 2 Parametros 
     * @var string inner
     * @var string where
     */
    public function inner($inner = null, $where = null)
    {
        $query = 'SELECT * FROM ' . $inner . ' WHERE ' . $where;
        // var_dump($query);
        // die();
        return $this->exculte($query);
    }


    /**
     * Função especial para logar colaboradores
     * 2 Parametros 
     * @var string inner
     * @var string where
     */
    public function LOGIN($inner = null, $where = null)
    {
        $query = 'SELECT *, PFU.CHAPA, PFU.NOME, PPE.CODUSUARIO, GUS.CODUSUARIO, PPE.CPF FROM ' . $inner . ' WHERE ' . $where;
        return $this->exculte($query);
    }


    /**
     * Função QUE SELECIONA OS CURSOS ATIVOS
     * 2 Parametros 
     * @var string inner
     * @var string where
     */
    public function innerTrazCursos($inner = null, $where = null)
    {
        $query = 'SELECT DISTINCT SCU.CODCURSO, SCU.NOME FROM ' . $inner . ' WHERE ' . $where;
        return $this->exculte($query);
    }
}
