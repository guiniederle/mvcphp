<?php

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
            $stmt = $this->_db->prepare($sql);
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
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
    }

    private function mountReturn($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }
}