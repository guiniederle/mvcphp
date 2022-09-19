<?php

namespace App\Models;

use App\Models\Interfaces\IModels;
use Lib\PersistModelAbstract;
use PDO;
use PDOException;

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
        $productType = $this->getConnection()->prepare($sql);
        $productType->execute();

        return $productType->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT {$this->_columns} FROM {$this->_dbName} WHERE id = :id";
        $productType = $this->getConnection()->prepare($sql);
        $productType->bindValue(':id', $id);
        $productType->execute();

        return $productType->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllWithTaxes()
    {
        $sql = "SELECT pt.id, pt.description, array_agg(tax.description) as taxdescription FROM {$this->_dbName} pt
        LEFT JOIN taxproducttype tpt ON tpt.productTypeId = pt.id
        LEFT JOIN tax ON tax.id = tpt.taxId GROUP BY pt.id";
        $productType = $this->getConnection()->prepare($sql);
        $productType->execute();

        return $productType->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByIdWithTaxes($id)
    {
        $sql = "SELECT pt.id, pt.description, array_agg(tax.id) as taxid FROM {$this->_dbName} pt
        LEFT JOIN taxproducttype tpt ON tpt.productTypeId = pt.id
        LEFT JOIN tax ON tax.id = tpt.taxId WHERE pt.id = :id GROUP BY pt.description, pt.id";
        $productType = $this->getConnection()->prepare($sql);
        $productType->bindValue(':id', $id);
        $productType->execute();

        return $productType->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert or update in producttype and taxproducttype
     * 
     * @param $productTypeValues = [
     *  description => valueDescription,
     *  id => valueId,
     *  taxes => [taxId]
     * ]
     */
    public function insertOrUpdate($productTypeValues)
    {
        $description = trim($productTypeValues['description']);
        if (empty($description)) {
            return $this->mountReturn(
                'warning',
                "O campo descrição não pode ser vazio!"
            );
        }

        $sql = "INSERT INTO {$this->_dbName} (description) VALUES (:description)";
        if (!empty($productTypeValues["id"])) {
            $sql = "UPDATE {$this->_dbName} SET description = :description WHERE id = {$productTypeValues["id"]}";
        }

        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':description', $description);
            $stmt->execute();
            
            $idTaxesProductTypes = $productTypeValues["id"];
            if (empty($productTypeValues["id"])) {
                $idTaxesProductTypes = $this->getConnection()->lastInsertId();
            }

            $this->_taxesProductTypes->insert($idTaxesProductTypes, $productTypeValues['taxes']);

            return $this->mountReturn('success', "Tipo de produto salvo com sucesso!");
        } catch (PDOException $e) {
            return $this->mountReturn(
                'danger',
                "Não foi possível inserir o registro no momento. Erro: {$e->getMessage()}"
            );
        }
    }

    public function delete($id)
    {
        try {
            $this->_taxesProductTypes->remove($id);
            $sql = "DELETE FROM {$this->_dbName} WHERE id = :id";
            $productType = $this->getConnection()->prepare($sql);
            $productType->bindValue(':id', $id);
            $productType->execute();

            return $this->mountReturn('success', "Excluído com sucesso!");
        } catch (PDOException $e) {
            return $this->mountReturn(
                'danger',
                "Não foi possível inserir o registro no momento. Erro: {$e->getMessage()}"
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