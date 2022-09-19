<?php

namespace App\Models;

use Lib\PersistModelAbstract;
use PDO;
use PDOException;

class TaxesProductTypes extends PersistModelAbstract
{
    private $_dbName = 'taxproducttype';

    public function insert($productTypeId, $taxesIds)
    {
        $this->remove($productTypeId);

        try {
            $values = '';
            foreach ($taxesIds as $id) {
                $values .= "({$productTypeId}, {$id}),";
            }
            $values = rtrim($values, ',');
            $sql = "INSERT INTO {$this->_dbName} VALUES {$values}";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute();

            return $this->mountReturn('success', "Imposto salvo com sucesso!");
        } catch (PDOException $e) {
            return $this->mountReturn(
                'danger',
                "Não foi possível inserir o registro no momento. Erro: " . $e->getMessage()
            );
        }
    }

    public function remove($productTypeId)
    {
        $sql = "DELETE FROM {$this->_dbName} WHERE productTypeId = {$productTypeId}";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute();
    }

    public function getTaxesByProductTypeId($productTypeId)
    {
        $query = "SELECT TAXID FROM {$this->_dbName} WHERE PRODUCTTYPEID = {$productTypeId}";
        $taxes = $this->getConnection()->prepare($query);
        $taxes->execute();

        return $taxes->fetchAll(PDO::FETCH_COLUMN);
    }

    private function mountReturn($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }
}