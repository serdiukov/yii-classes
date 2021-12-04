<?php

namespace serdiukov\yii\rest;


use yii\base\Module;
use yii\web\Response;
use Yii;

abstract class RestModule extends Module
{
    public function init()
    {
        parent::init();

        Yii::$app->errorHandler->errorAction = $this->id .'/default/error';
        Yii::$app->user->enableSession = false;

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;

            if($response->format == 'raw') {
                $response->format = Response::FORMAT_JSON;
                return true;
            }

            $responseData = $response->data;

            if(is_string($responseData) && json_decode($responseData)) {
                $responseData = json_decode($responseData, true);
            }

            if($response->statusCode >= 200 && $response->statusCode <= 299) {
                $response->data = [
                    'success'   => true,
                    'status'    => $response->statusCode,
                    'data'      => $responseData,
                ];
            } else {

                $response->data = [
                    'success'   => false,
                    'status'    => $response->statusCode,
                    'data'      => $this->responseDataError($responseData)
                ];
            }
        });
    }

    /**
     * @param $responseData
     * @return array
     */
    protected function responseDataError($responseData)
    {
        if (is_array($responseData) && isset($responseData['message'])) {
            if ($this->is_serialized_string($responseData['message'])) {
                if ($arrayMessage = unserialize($responseData['message'])) {
                    if ($error = array_shift($arrayMessage)) {
                        $responseData['message'] = $error;
                        $responseData['errors'] = $arrayMessage;
                    }
                }
            }
        }

        return $responseData;
    }

    /**
     * @param $string
     * @return bool
     */
    protected function is_serialized_string($string)
    {
        return ($string == 'b:0;' || @unserialize($string) !== false);
    }
}
