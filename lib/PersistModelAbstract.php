<?php

/**
 * Class PersistModelAbstract
 */
abstract class PersistModelAbstract
{
    /**
    * Variável responsável por guardar dados da conexão do banco
    * @var resource
    */
    protected $_db;
      
    function __construct()
    {
        $dbconfig = json_decode(file_get_contents("dbconfig.json"));

        try {
            $this->_db = new PDO("pgsql:dbname={$dbconfig->dbname};host={$dbconfig->dbhost}", $dbconfig->dbuser, $dbconfig->dbpass);
            $this->_db->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            var_dump($e->getMessage());exit;
        }

    }
}
?>
