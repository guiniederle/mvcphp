<?php
require_once 'models/Taxes.php';
require_once 'IController.php';

class TaxesController implements IController
{
    private $_taxes;

    public function __construct()
    {
        $this->_taxes = new Taxes();
    }

    public function indexAction()
    {
        return new View("taxes/index.phtml", ['data' => $this->_taxes->getAll()]);
    }

    public function insertOrUpdateAction()
    {
        $message = null;
        $data = [
            'id' => '',
            'description' => '',
            'percentage' => '',
        ];
        if (!empty($_POST))
            $message = $this->_taxes->insertOrUpdate($_POST);
        if (isset($_GET['id']))
            $data = $this->_taxes->getById($_GET['id']);
        return new View("taxes/create.phtml", [
            'return' => $message,
            'data' => $data
        ]);
    }

    public function deleteAction()
    {
        if (isset($_GET['id']))
            $this->_taxes->delete($_GET['id']);
        header('Location: http://'.$_SERVER['HTTP_HOST']."/?controle=Taxes&acao=index");
    }

    public function jsonAction()
    {
        echo json_encode($this->_taxes->getAll());
    }
}