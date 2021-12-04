<?php

namespace serdiukov\yii\repositories;


use serdiukov\yii\entities\Entity;
use yii\db\ActiveQuery;
use yii\db\Exception;

abstract class Repositories implements RepositoryInterface
{
    /**
     * @return ActiveQuery
     */
    abstract public function query() : ActiveQuery;

    /**
     * @param int $id
     * @return Entity|null
     */
    public function getById(int $id)
    {
        try {
            return $this->getBy(['id' => $id]);

        } catch (Exception $exception) {
            throw new \RuntimeException('DATABASE_ERROR', 400);
        }
    }

    /**
     * @param string $uuid
     * @return Entity|null
     */
    public function getByUUID(string $uuid)
    {
        try {
            return $this->getBy(['uuid' => $uuid]);

        } catch (Exception $exception) {
            throw new \RuntimeException('DATABASE_ERROR', 400);
        }
    }

    /**
     * @param array $conditions
     * @param string $select
     * @param array $order_by
     * @return Entity[]|array
     */
    public function getAllBy(array $conditions, string $select = '*', array $order_by = [])
    {
        try {
            return $this
                ->query()
                ->select($select)
                ->andWhere($conditions)
                ->orderBy($order_by)
                ->all();

        } catch (Exception $exception) {
            throw new \RuntimeException('DATABASE_ERROR', 400);
        }
    }

    /**
     * @param array $conditions
     * @param string $select
     * @param array $order_by
     * @return Entity|null
     */
    public function getBy(array $conditions, string $select = '*', array $order_by = [])
    {
        try {
            return $this
                ->query()
                ->select($select)
                ->andWhere($conditions)
                ->orderBy($order_by)
                ->limit(1)
                ->one();

        } catch (Exception $exception) {
            throw new \RuntimeException('DATABASE_ERROR', 400);
        }
    }
}