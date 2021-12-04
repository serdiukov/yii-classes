<?php

namespace serdiukov\yii\repositories;


interface RepositoryInterface
{
    public function query();
    public function getById(int $id);
    public function getByUUID(string $uuid);
    public function getBy(array $conditions, string $select = '*');
    public function getAllBy(array $conditions, string $select = '*', array $order_by = []);
}
