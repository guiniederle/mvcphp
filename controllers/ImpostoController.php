<?php
require_once 'models/Impostos.php';
require_once 'IController.php';

class ImpostoController implements IController
{
    private $_impostos;

    public function __construct()
    {
        $this->_impostos = new Impostos();
    }

    //?controle=Contato&acao=listarContato
    public function indexAction()
    {
        return new View("impostos/index.phtml", ['data' => $this->_impostos->getAll()]);
    }

    public function insertOrUpdateAction()
    {
        $mensagem = null;
        $dados = [
            'id' => '',
            'descricao' => '',
            'porcentagem' => '',
        ];
        if (!empty($_POST))
            $mensagem = $this->_impostos->insertOrUpdate($_POST);
        if (isset($_GET['id']))
            $dados = $this->_impostos->getById($_GET['id']);
        return new View("impostos/create.phtml", [
            'mensagem' => $mensagem,
            'dados' => $dados
        ]);
    }

    public function deleteAction()
    {
        if (isset($_GET['id']))
            $this->_impostos->delete($_GET['id']);
        return new View("impostos/index.phtml", ['data' => $this->_impostos->getAll()]);
    }
}