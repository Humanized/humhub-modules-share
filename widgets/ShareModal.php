<?php

namespace humhub\modules\share\widgets;

use Yii;
use yii\helpers\Url;

use humhub\widgets\Modal;

use humhub\modules\share\models\ShareEntry;

/**
 *  Modal popup displaying various options to user when sharing
 */
class ShareModal extends Modal
{
    public $model;
    public $message;

    public function run() {
        $object = $this->model->object;

        // shared entries by space
        $entries = [];
        foreach(ShareEntry::findFor($object)->all() as $entry) {
            if(!$entry->content->canRead())
                continue;
            $entries[$entry->content->space->id] = $entry;
        }

        return $this->render('shareModal', [
            'message' => $this->message,
            'object' => $object,
            'model' => $this->model,
            'entries' => $entries,
            'spaces' => $this->model->allowedSpaces()->all(),
        ]);
    }

    public function save() {
        return true;
    }
}

