<?php

namespace Lib;

use Exception;

class Application
{
    protected $_controller;
    protected $_action;

    private function loadRoute()
    {
        /*
        * Se o controller nao for passado por GET,
        * assume-se como padrão o controller 'IndexController';
        */
        $this->_controller = isset($_REQUEST['controle']) ? $_REQUEST['controle'] : 'Index';

        /*
        * Se a action nao for passada por GET,
        * assume-se como padrão a action 'IndexAction';
        */
        $this->_action = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : 'index';
    }

    private function verifyClassExists()
    {
        $classController = "\\App\\Controllers\\{$this->_controller}Controller";

        //verificando se a classe existe
        if (class_exists($classController)) {
            return $classController;
        } else {
            throw new Exception("Classe '{$classController}' nao existe!");
        }
    }

    /**
     * @throws Exception
     */
    public function dispatch()
    {
        $this->loadRoute();

        $className = $this->verifyClassExists();
        $classInstance = new ($className);

        //verificando se o metodo existe
        $classMethod = $this->_action . 'Action';
        if (method_exists($classInstance, $classMethod)) {
            $classInstance->$classMethod();
        } else {
            throw new Exception("Metodo '{$classMethod}' nao existe na classe {$className}'");
        }
    }

    /**
     * Redireciona a chamada http para outra página
     * @param string $st_uri
     */
    static function redirect($st_uri)
    {
        header("Location: $st_uri");
    }
}

?>
