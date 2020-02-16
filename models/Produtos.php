<?php

class Produtos extends PersistModelAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProducts(){
        $sql = "select * from imposto";
        return $this->_db->query($sql);
    }
}