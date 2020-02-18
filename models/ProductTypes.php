<?php

require_once 'IModels.php';
require_once 'TaxesProductTypes.php';

class ProductTypes extends PersistModelAbstract implements IModels
{
    private $_dbName = 'producttype';
    private $_columns = 'id, description';
    private $_taxesProductTypes;

    public function __construct()
    {
        parent::__construct();
        $this->_taxesProductTypes = new TaxesProductTypes();
    }

    public function getAll()
    {
        $sql = "SELECT {$this->_columns} FROM {$this->_dbName}";
        $productType = $this->_db->prepare($sql);
        $productType->execute();

        return $productType->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT {$this->_columns} FROM {$this->_dbName} WHERE id = :id";
        $productType = $this->_db->prepare($sql);
        $productType->bindValue(':id', $id);
        $productType->execute();

        return $productType->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllWithTaxes()
    {
        $sql = "SELECT pt.id, pt.description, array_agg(tax.description) as taxdescription FROM {$this->_dbName} pt
        LEFT JOIN taxproducttype tpt ON tpt.productTypeId = pt.id
        LEFT JOIN tax ON tax.id = tpt.taxId GROUP BY pt.id";
        $productType = $this->_db->prepare($sql);
        $productType->execute();

        return $productType->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByIdWithTaxes($id)
    {
        $sql = "SELECT pt.id, pt.description, array_agg(tax.id) as taxid FROM {$this->_dbName} pt
        LEFT JOIN taxproducttype tpt ON tpt.productTypeId = pt.id
        LEFT JOIN tax ON tax.id = tpt.taxId WHERE pt.id = :id GROUP BY pt.description, pt.id";
        $productType = $this->_db->prepare($sql);
        $productType->bindValue(':id', $id);
        $productType->execute();

        return $productType->fetch(PDO::FETCH_ASSOC);
    }

    public function insertOrUpdate($data)
    {
        $sql = "INSERT INTO {$this->_dbName} (description) VALUES (:description)";
        if (!empty($data["id"]))
            $sql = "UPDATE {$this->_dbName} SET description = :description WHERE id = {$data["id"]}";
        try {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindValue(':description', $data['description']);
            $stmt->execute();
            $lastId = $this->_db->lastInsertId();

            $this->_taxesProductTypes->insert(!empty($data["id"]) ? $data["id"] : $lastId, $data['taxes']);
            return $this->mountReturn('success', "Tipo de produto salvo com sucesso!");
        } catch (PDOException $e) {
            return $this->mountReturn(
                'danger',
                "Não foi possível inserir o registro no momento. Erro: " . $e->getMessage()
            );
        }
    }

    public function delete($id)
    {
        $this->_taxesProductTypes->remove($id);

        try {
            $sql = "DELETE FROM {$this->_dbName} WHERE id = :id";
            $productType = $this->_db->prepare($sql);
            $productType->bindValue(':id', $id);
            $productType->execute();

            return $this->mountReturn('success',"Excluído com sucesso!");
        } catch (PDOException $e) {
            return $this->mountReturn(
                'danger',
                "Não foi possível inserir o registro no momento. Erro: " . $e->getMessage()
            );
        }
    }

    private function mountReturn($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }
}