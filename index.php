<?php
//configurando o PHP para mostrar os erros na tela
ini_set('display_errors', 1);


//configurando o PHP para reportar todos e quaisquer erros
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
use Lib\Application;

$application = new Application();
$application->dispatch();
?>
