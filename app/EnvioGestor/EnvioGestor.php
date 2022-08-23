<?php

namespace App\EnvioGestor;

use App\DbInsercao\DbInsercao;

class EnvioGestor
{

    // public $nomeMaterial;
    public $descricao;
    public $curso;
    public $file;
    public $data;
    public $raGestor;



    /**
     * Função de upload de arquivos
     * @param 1
     * @var string $post que vem do form
     */
    public function uploadGestor($post)
    {

        $file = $post;
        $names = $file['name'];
        $tmp = $file['tmp_name'];
        $return = [];

        foreach ($names as $index => $name) {

            $extensions = "vnd.openxmlformats-officedocument.wordprocessingml.document|pdf";
            $ext = pathinfo($name, PATHINFO_EXTENSION);

            if (!preg_match("/application\/($extensions)/", $file['type'][$index])) {
                $_SESSION['envioInvalidoGestor'] = true;
                header('Location: ../gestor/envio.php');
                die();
            } else {
                $novoArquivo =  uniqid() . "." . $ext;

                move_uploaded_file($tmp[$index], 'uploadMaterialApoio/' . $novoArquivo);

                $return[] = $novoArquivo;
            }
        }
        return $return;
    }


    /**
     * Função que cadastra o material do banco
     */
    public function cadastrar()
    {

        $files = $this->uploadGestor($this->file);

        $implode = implode(', ', $files);

        $obDados = (new DbInsercao('CENTRAL_APOIO'));

        $obDados->insert([
            'DESCRICAO' => $this->descricao,
            'ARQUIVO' => $implode,
            'DATACADASTRO' => $this->data,
            'CODUSUARIO' => $this->raGestor,
            'CURSO' => $this->curso
        ]);
        header("Location: ../gestor/materialApoio.php");
    }
}
