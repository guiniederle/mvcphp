<?php

namespace App\Controllers;

use App\Controllers\Interfaces\IController;
use App\Models\Taxes;
use App\Models\TaxesProductTypes;
use Lib\View;

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

        if (!empty($_POST)) {
            $message = $this->_taxes->insertOrUpdate($_POST);
        }

        if (isset($_GET['id'])) {
            $data = $this->_taxes->getById($_GET['id']);
        }

        return new View("taxes/create.phtml", [
            'return' => $message,
            'data' => $data
        ]);
    }

    public function deleteAction()
    {
        if (isset($_GET['id'])) {
            $this->_taxes->delete($_GET['id']);
        }

        header('Location: http://'.$_SERVER['HTTP_HOST']."/?controle=Taxes&acao=index");
    }

    public function jsonAction()
    {
        $taxesProductType = new TaxesProductTypes();
        echo json_encode([
            'all' => $this->_taxes->getAll(),
            'selected' => isset($_GET['id']) && !empty($_GET['id']) ? $taxesProductType->getTaxesByProductTypeId($_GET['id']) : []
        ]);
    }
}