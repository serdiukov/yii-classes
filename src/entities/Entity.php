<?php

namespace serdiukov\yii\entities;


use Ramsey\Uuid\Uuid;
use yii\db\ActiveRecord;
use yii\db\Connection;
use Yii;

abstract class Entity extends ActiveRecord
{
    /**
     * @return Connection
     */
    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function createModel()
    {
        $model = new static();
        $model->uuid = self::generateUUID();

        return $model;
    }

    public static function generateUUID()
    {
        try {
            return Uuid::uuid4()->toString();

        } catch (\Exception $exception) {
            throw new \RuntimeException('GENERATE_UUID_ERROR', 400);
        }
    }
}
