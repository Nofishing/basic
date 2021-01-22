<?php

namespace app\controllers;

use app\models\Goods;
use app\models\TUser;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;

class GoodsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        //跨域
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                // Allow methods
                'Access-Control-Request-Method' => ['POST', 'GET', 'OPTIONS', 'HEAD'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['*'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                'Access-Control-Allow-Credentials' => false,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                //'Access-Control-Expose-Headers' => [],
            ],
        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];

        //$behaviors['authenticator'] = [
        //'class' => HttpBearerAuth::class,
        //'optional' => [],
        //];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }


    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws MethodNotAllowedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $isPost = Yii::$app->request->isPost;
        if (!$isPost) {
            throw new MethodNotAllowedHttpException();
        }

        //$this->payload = json_decode(file_get_contents('php://input'), true);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionAdd()
    {
        //$post = Yii::$app->request->post();
        $payload = json_decode(file_get_contents('php://input'), true);


        try{
            $model = new Goods([
                'attributes' => $payload
            ]);
            if(!$model->save()){
                throw new Exception(current($model->getFirstErrors()));
            }
        }catch (Exception $e) {
            return ['code' => 500, 'message' => $e->getMessage()];
        }

        return ['code' => 200, 'message' => 'success'];
    }
}
