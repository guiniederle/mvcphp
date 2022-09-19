<?php

namespace App\Models;

use App\Models\Interfaces\IModels;
use Lib\PersistModelAbstract;
use PDO;
use PDOException;

class Taxes extends PersistModelAbstract implements IModels
{
    private $_dbName = 'tax';
    private $_columns = 'id, description, percentage';

    public function getAll()
    {
        $sql = "SELECT {$this->_columns} FROM {$this->_dbName}";
        $taxes = $this->getConnection()->prepare($sql);
        $taxes->execute();

        return $taxes->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT {$this->_columns} FROM {$this->_dbName} WHERE id = :id";
        $taxes = $this->getConnection()->prepare($sql);
        $taxes->bindValue(':id', $id);
        $taxes->execute();

        return $taxes->fetch();
    }

    public function insertOrUpdate($data)
    {
        $sql = "INSERT INTO {$this->_dbName} (description, percentage) VALUES (:description, :percentage)";
        if (!empty($data["id"])) {
            $sql = "UPDATE {$this->_dbName} SET description = :description, percentage = :percentage WHERE id = {$data["id"]}";
        }

        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':percentage', str_replace('%', '', $data['percentage']));
            $stmt->execute();

            return $this->mountReturn('success', "Imposto salvo com sucesso!");
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
            $taxes = $this->getConnection()->prepare($sql);
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

    private function mountReturn($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }
}