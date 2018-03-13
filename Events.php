<?php

namespace humhub\modules\space_mentioning;

use Yii;

use humhub\modules\user\models\User;

use humhub\modules\space_mentioning\models\SpaceMention;

/**
 *  MentionSpacesEvents
 */
class Events extends \yii\base\Object
{
    public static function onContentInsert($event)
    {
        $content = $event->sender;
        SpaceMention::parse($content, $content->message);
    }

    public static function onContentUpdate($event)
    {
        $content = $event->sender;
        SpaceMention::parse($content, $content->message, true);
    }

    public static function onContentDelete($event)
    {
        $content = $event->sender;
        SpaceMention::deleteFor($content);
    }

}

