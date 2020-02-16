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
        return new View("impostos/index.phtml", $this->_impostos->getAll());
    }

    public function insertOrUpdateAction()
    {
        // TODO: Implement insertOrUpdateAction() method.
    }

    public function deleteAction()
    {
        // TODO: Implement deleteAction() method.
    }
}