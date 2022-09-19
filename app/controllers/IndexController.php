<?php

namespace App\Controllers;

use Lib\View;

class IndexController
{
    public function indexAction()
    {
        return new View('index.phtml');
    }
}