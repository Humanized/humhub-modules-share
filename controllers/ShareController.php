<?php

namespace humhub\modules\share\controllers;

use Yii;
use humhub\modules\content\models\Content;
use humhub\modules\admin\components\Controller;

use humhub\modules\share\models\ShareEntry;
use humhub\modules\share\models\ShareModalForm;

/**
 *  Mailing-Lists admin controller
 */
class ShareController extends Controller
{
    public $object;
    public $space;

    // get shared entry for user's accessible spaces
    function getSpacesEntry($object) {
        $entries = [];
        foreach(ShareEntry::findFor($object)->all() as $entry) {
            if(!$entry->content->canRead())
                continue;
            $entries[$entry->content->space->id] = $entry;
        }
        return $entries;
    }

    // default view is the "share" modal
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

        return $this->renderAjax('@share/views/modal', [
            'model' => $model,
            'object' => $model->object,
            'spaces' => $model->allowedSpaces()->all(),
            'entries' => $this->getSpacesEntry($model->object),
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

