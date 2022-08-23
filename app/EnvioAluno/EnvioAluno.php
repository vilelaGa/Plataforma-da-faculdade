<?php

namespace App\EnvioAluno;

use App\DbInsercao\DbInsercao;

class EnvioAluno
{
    public $codColidada;
    public $codCurso;
    public $codHabilitacao;
    public $turno;
    public $codFilial;
    public $codTipoCurso;
    public $ra;
    public $CodPeriodo;
    public $cargaHoraria;
    public $file;
    public $dataInicio;
    public $dataFinal;
    public $dataEntrega;
    public $inscricao;
    public $docEntregue;
    public $cumprioAtvd;
    public $oferta;
    public $codGadeMatriz; //Via db
    public $obs;


    /**
     * Função de upload de arquivos
     * @param 1
     * @var string $post que vem do form
     */
    public function uploadAluno($post)
    {
        $file = $post;
        $name = $file['name'];
        $tmp = $file['tmp_name'];

        $extensions = "pdf";
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        if (!preg_match("/application\/($extensions)/", $file['type'])) {
            $_SESSION['envioInvalidoAluno'] = true;
            header('Location: ../aluno/enviar.php');
            die();
        } else {
            $novoArquivo =  uniqid() . "." . $ext;
            move_uploaded_file($tmp, 'uploadAtividadeAvaliacao/' . $novoArquivo);
            return $novoArquivo;
        }
    }


    /**
     * Função que cadastra o material do banco
     */
    public function cadastrar()
    {
        $returnNovoArquivo = $this->uploadAluno($this->file);

        $obDatabase = new DbInsercao("CENTRAL_ATIVIDADE");

        $obDatabase->insert([
            'CODCOLIGADA' => $this->codColidada,
            'CODCURSO' => $this->codCurso,
            'CODHABILITACAO' => $this->codHabilitacao,
            'TURNO' => $this->turno,
            'CODFILIAL' => $this->codFilial,
            'CODTIPOCURSO' => $this->codTipoCurso,
            'RA' => $this->ra,
            'CODPERLET' => $this->CodPeriodo,
            'CARGAHORARIA' => $this->cargaHoraria,
            'ARQUIVO' => $returnNovoArquivo,
            'DATAINICIO' => $this->dataInicio,
            'DATAFIM' => $this->dataFinal,
            'INSCRICAOCONFIRMADA' => $this->inscricao,
            'DOCUMENTACAOENTREGUE' => $this->docEntregue,
            'CUMPRIUATIVIDADE' => $this->cumprioAtvd,
            'DESCRICAO' => $this->oferta,
            'CODGRADE' => $this->codGadeMatriz,
            'OBSERVACAO' => $this->obs,
            'DATACADASTRO' => $this->dataEntrega
        ]);

        header("Location: ../aluno/enviados.php?pagina=1");
    }
}
