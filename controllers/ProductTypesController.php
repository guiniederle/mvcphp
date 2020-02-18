<?php

require_once 'IController.php';
require_once 'models/ProductTypes.php';

class ProductTypesController implements IController
{
    private $_productType;

    public function __construct()
    {
        $this->_productType = new ProductTypes();
    }

    public function indexAction()
    {
        return new View("productType/index.phtml", ['data' => $this->_productType->getAllWithTaxes()]);
    }

    public function insertOrUpdateAction()
    {
        $message = null;
        $data = [
            'id' => '',
            'description' => '',
            'taxes' => []
        ];
        if (!empty($_POST))
            $message = $this->_productType->insertOrUpdate($_POST);
        if (isset($_GET['id']))
            $data = $this->_productType->getByIdWithTaxes($_GET['id']);
        return new View("productType/create.phtml", [
            'return' => $message,
            'data' => $data
        ]);
    }

    public function deleteAction()
    {
        if (isset($_GET['id']))
            $this->_productType->delete($_GET['id']);
        header('Location: http://'.$_SERVER['HTTP_HOST']."/?controle=ProductTypes&acao=index");
    }
}