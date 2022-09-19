<?php

namespace App\Models\Interfaces;

interface IModels
{
    public function getAll();

    public function getById($id);

    public function insertOrUpdate($data);

    public function delete($id);
}