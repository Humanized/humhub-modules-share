<?php

namespace humhub\modules\share\widgets;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

use humhub\modules\share\models\ShareEntry;

/**
 * This widget is used to show a like link inside the wall entry controls.
 *
 * @package humhub.modules_core.like
 * @since 0.5
 */
class ShareLink extends \yii\base\Widget
{
    /**
     *  Content object
     */
    public $object;

    public function run() {
        return $this->render('shareLink', [
            'object' => $this->object,
            'entries' => ShareEntry::findFor($this->object),
        ]);
    }
}

