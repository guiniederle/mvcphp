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
        $sql = "SELECT id, descricao, porcentagem FROM imposto WHERE id = :id";
        $impostos = $this->_db->prepare($sql);
        $impostos->bindValue(':id', $id);
        $impostos->execute();

        return $impostos->fetch();
    }

    public function insertOrUpdate($data)
    {
        $sql = "INSERT INTO imposto (descricao, porcentagem) VALUES (:descricao, :porcentagem)";
        if (isset($data["id"]))
            $sql = "UPDATE imposto SET descricao = :descricao, porcentagem = :porcentagem WHERE id = {$data["id"]}";
        try {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindValue(':descricao', $data['descricao']);
            $stmt->bindValue(':porcentagem', $data['porcentagem']);
            $stmt->execute();
            return "Imposto salvo com sucesso!";
        } catch (PDOException $e) {
            return "Não foi possível inserir o registro no momento. Erro: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM imposto WHERE id = :id";
            $impostos = $this->_db->prepare($sql);
            $impostos->bindValue(':id', $id);
            $impostos->execute();

            return "Excluído com sucesso!";
        } catch (PDOException $e) {
            return "Não foi possível inserir o registro no momento. Erro: " . $e->getMessage();
        }
    }
}