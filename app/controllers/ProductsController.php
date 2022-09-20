<?php

namespace App\Controllers;

use App\Controllers\Interfaces\IController;
use App\Models\Products;
use App\Models\ProductTypes;
use Lib\View;

class ProductsController implements IController
{
    private $_product;
    private $_productTypes;

    public function __construct()
    {
        $this->_product = new Products();
        $this->_productTypes = new ProductTypes();
    }

    public function indexAction()
    {
        return new View("products/index.phtml", ['data' => $this->_product->getAll()]);
    }

    public function insertOrUpdateAction()
    {
        $message = null;
        $data = [
            'id' => '',
            'description' => '',
            'producttype' => '',
            'price' => '',
        ];

        if (!empty($_POST)) {
            $message = $this->_product->insertOrUpdate($_POST);
        }

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $data = $this->_product->getById($_GET['id']);
        }

        return new View("products/create.phtml", [
            'return' => $message,
            'data' => $data
        ]);
    }

    public function deleteAction()
    {
        if (isset($_GET['id'])) {
            $this->_product->delete($_GET['id']);
        }

        header('Location: http://'.$_SERVER['HTTP_HOST']."/?controle=Products&acao=index");
    }

    public function jsonAction()
    {
        echo json_encode($this->_product->getAll());
    }

    public function infoProductAction()
    {
        echo json_encode($this->_product->getDataToSales($_POST['productId']));
    }
}