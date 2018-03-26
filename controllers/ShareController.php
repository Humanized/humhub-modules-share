<?php

namespace humhub\modules\share\controllers;

use Yii;
use humhub\modules\content\models\Content;
use humhub\modules\admin\components\Controller;

use humhub\modules\share\models\ShareEntry;
use humhub\modules\share\models\ShareModalForm;
use humhub\modules\share\widgets\ShareModal;

/**
 *  Mailing-Lists admin controller
 */
class ShareController extends Controller
{
    public $object;
    public $space;

    public function actionIndex() {
        $request = Yii::$app->request;
        $model = new ShareModalForm();
        $message = null;

        if($model->load(Yii::$app->request->post())) {
            if(!$model->save())
                return "could not proceed to action";

            if($model->object) {
                $message = "Items have been shared!";
                ShareEntry::createFor($model->object, $model->message,
                    ['id' => $model->spaces ]
                );
            }

            $model = new ShareModalForm([
                'id' => $model->id,
            ]);
        }
        else
            $model->id = $request->get('object');

        if(!$model->object)
            return "object not found";

        return ShareModal::widget([
            'model' => $model,
            'message' => $message,
        ]);
    }

    public function showShareCount() {
        Yii::$app->response->format = 'json';
        $this->fetch();
        if(!$this->object || !$this->object->canView())
            return null;
    }
}

