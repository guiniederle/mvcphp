<?php

namespace Lib;

use PDO;
use PDOException;

/**
 * Class PersistModelAbstract
 */
abstract class PersistModelAbstract
{
    /**
    * Variável responsável por guardar dados da conexão do banco
    * @var PDO
    */
    protected $_db;
      
    public function __construct()
    {
        $dbconfig = json_decode(file_get_contents("env.json"));

        try {
            $this->_db = new PDO(
                "pgsql:dbname={$dbconfig->dbconfig->dbname};host={$dbconfig->dbconfig->dbhost}",
                $dbconfig->dbconfig->dbuser,
                $dbconfig->dbconfig->dbpass
            );
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            var_dump($e->getMessage());exit;
        }

    }

    /**
     * Retorna a conexão com o banco
     */
    protected function getConnection(): PDO
    {
        return $this->_db;
    }
}
?>
