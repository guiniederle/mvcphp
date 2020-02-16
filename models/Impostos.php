<?php
require_once "IModels.php";

class Impostos extends PersistModelAbstract implements IModels
{
    public function getAll()
    {
        $sql = "SELECT id, descricao, porcentagem FROM imposto";
        $impostos = $this->_db->prepare($sql);
        $impostos->execute();

        return $impostos->fetchAll();
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function insertOrUpdate($data)
    {
        // TODO: Implement insertOrUpdate() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}