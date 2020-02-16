<?php
require_once 'models/Produtos.php';

class IndexController
{

    public function indexAction()
    {
//        $produtos = new Produtos();
//        var_dump($produtos->getProducts());exit;


        $o_view = new \View('index.phtml');
    }
}