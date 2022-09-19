<?php

namespace App\Controllers\Interfaces;

interface IController
{
    public function indexAction();

    public function insertOrUpdateAction();

    public  function deleteAction();
}