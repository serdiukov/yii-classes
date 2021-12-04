<?php

namespace serdiukov\yii\rest;


use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use Yii;

abstract class RestController extends Controller
{
    // Customize the verbs as needed
    private $_verbs = ['GET','POST','PATCH','PUT','DELETE'];

    /**
     * @param array array[verbs]
     *              array[cors][methods]
     *              array[cors][headers]
     *              array[except]
     * @return array
     */
    public function ruleBehaviors(array $array = [])
    {
        $behaviors = parent::behaviors();

        // remove auth filter
        unset($behaviors['authenticator']);

        // array actions
        $verbs = $array['verbs'] ?? [];

        // add verbs filter
        $behaviors['verbs'] = [
            'class'     => VerbFilter::class,
            'actions'   => $verbs
        ];

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => [getenv('CORS_ORIGIN')],
                'Access-Control-Request-Method'     => $array['cors']['methods'] ?? $this->_verbs,
                'Access-Control-Allow-Credentials'  => true,
                'Access-Control-Expose-Headers'     => ['*'],
                'Access-Control-Allow-Headers'      => ['*']
            ]
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'user' => Yii::$app->user
        ];

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = $array['except'] ?? [];

        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only'  => array_keys($verbs),
            'rules' => [
                [
                    'allow'     => true,
                    'actions'   => array_keys($verbs),
                    'matchCallback' => $array['accessMatchCallback'] ?? []
                ]
            ]
        ];

        return $behaviors;
    }
}
