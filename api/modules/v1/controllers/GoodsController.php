<?php

namespace app\api\modules\v1\controllers;

use app\models\Goods;
use Yii\rest\ActiveController;
class GoodsController extends ActiveController
{
    public $modelClass = 'api\models\Goods';

    public function actionIndex()
    {
        return Goods::findAll();
    }

}
