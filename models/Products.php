<?php

require_once 'IModels.php';

class Products extends PersistModelAbstract implements IModels
{
    private $_dbName = 'products';

    public function getAll()
    {
        $sql = "SELECT p.id, p.description, p.price, pt.description as producttype FROM {$this->_dbName} p
            JOIN producttype pt ON pt.id = p.productTypeId";
        $taxes = $this->_db->prepare($sql);
        $taxes->execute();

        return $taxes->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT p.id, p.description, p.price, pt.id as idpt, pt.description as producttype FROM {$this->_dbName} p
            JOIN producttype pt ON pt.id = productTypeId
            WHERE p.id = :id";
        $taxes = $this->_db->prepare($sql);
        $taxes->bindValue(':id', $id);
        $taxes->execute();

        return $taxes->fetch(PDO::FETCH_ASSOC);
    }

    public function insertOrUpdate($data)
    {
        $sql = "INSERT INTO {$this->_dbName} (description, producttypeid, price) VALUES (:description, :producttypeid, :price)";
        if (!empty($data["id"]))
            $sql = "UPDATE {$this->_dbName} SET description = :description, producttypeid = :producttypeid , price = :price 
                WHERE id = {$data["id"]}";
        try {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':producttypeid', $data['producttypeid']);
            $stmt->bindValue(':price', $data['price']);
            $stmt->execute();

            return $this->mountReturn('success', "Produto salvo com sucesso!");
        } catch (PDOException $e) {
            return $this->mountReturn(
                'danger',
                "Não foi possível inserir o registro no momento. Erro: " . $e->getMessage()
            );
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM {$this->_dbName} WHERE id = :id";
            $taxes = $this->_db->prepare($sql);
            $taxes->bindValue(':id', $id);
            $taxes->execute();

            return $this->mountReturn('success',"Excluído com sucesso!");
        } catch (PDOException $e) {
            return $this->mountReturn(
                'danger',
                "Não foi possível inserir o registro no momento. Erro: " . $e->getMessage()
            );
        }
    }

    public function getDataToSales($productId)
    {
        $sql = "SELECT p.price, tax.percentage FROM {$this->_dbName} p
            INNER JOIN producttype pt ON pt.id = p.productTypeId
            INNER JOIN taxproducttype taxpt ON pt.id = taxpt.productTypeId
            INNER JOIN tax ON tax.id = taxpt.taxId
            WHERE p.id = :id";
        $taxes = $this->_db->prepare($sql);
        $taxes->bindValue(':id', $productId);
        $taxes->execute();

        return $taxes->fetchAll(PDO::FETCH_ASSOC);
    }

    private function mountReturn($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }
}