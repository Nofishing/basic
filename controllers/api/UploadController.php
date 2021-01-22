<?php

namespace frontend\controllers\api;

use Yii;
use common\models\UploadForm;
use yii\web\UploadedFile;

class UploadController extends BaseApiController
{
    public function actionImg()
    {
        return $this->_fileHandle('img', 2);
    }

    public function actionVideo()
    {
        $vertical_screen = (int)Yii::$app->request->post('vertical_screen', 0);
        return $this->_fileHandle('video', 1, $vertical_screen);
    }

    private function _fileHandle($name, $file_type, $vertical_screen=0){
        $file_model = new UploadForm();
        $file_obj = UploadedFile::getInstanceByName($name);
        $file_url = null;
        if ($file_obj){
            if ($file_type == 0){
                $file_model->audioFile = $file_obj;
            }elseif ($file_type == 1){
                $file_model->videoFile = $file_obj;
            }elseif ($file_type == 2){
                $file_model->imgFile = $file_obj;
            }else {
                return ['code' => 201, 'message' => Yii::t('shop','failed')];
            }

            if (!$file_model->upload($file_type, $vertical_screen)) {
                return ['code' => 500, 'message' => Yii::t('shop','failed')];
            }
        }else {
            return ['code' => 202, 'message' => Yii::t('shop','failed')];
        }
        $file_url = $file_model->file_url;

        return ['code' => 200, 'message' => Yii::t('shop','success'), [
            'file_url' => $file_url,
        ]];
    }

    public function actionImgEditor(){
        $file_model = new UploadForm();
        $file_obj = UploadedFile::getInstanceByName('img_editor');
        $file_url = null;
        if ($file_obj){
            $file_model->imgFile = $file_obj;
            if (!$file_model->upload(2)) {
                return ['uploaded' => 0, 'error' => ['message' => current($file_model->getFirstErrors())]];
            }
            $file_url = $file_model->file_url;
        }else {
            return ['uploaded' => 0, 'error' => ['message' => 'File is empty']];
        }

        return ['uploaded' => true, 'url' => $file_url];
    }
}