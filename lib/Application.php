<?php

/**
 * @param $st_class
 */
function __autoload($st_class)
{
    if (file_exists('lib/' . $st_class . '.php')) {
        require_once 'lib/' . $st_class . '.php';
    }
}

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

    /**
     * @throws Exception
     */
    public function dispatch()
    {
        $this->loadRoute();

        //verificando se o arquivo de controle existe
        $_controller_file = 'controllers/' . $this->_controller . 'Controller.php';
        if (file_exists($_controller_file)) {
            require_once $_controller_file;
        } else {
            throw new Exception('Arquivo ' . $_controller_file . ' nao encontrado');
        }

        //verificando se a classe existe
        $st_class = $this->_controller . 'Controller';
        if (class_exists($st_class)) {
            $o_class = new $st_class;
        } else {
            throw new Exception("Classe '$st_class' nao existe no arquivo '$_controller_file'");
        }

        //verificando se o metodo existe
        $st_method = $this->_action . 'Action';
        if (method_exists($o_class, $st_method)) {
            $o_class->$st_method();
        } else {
            throw new Exception("Metodo '$st_method' nao existe na classe $st_class'");
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
