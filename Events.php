<?php

namespace humhub\modules\share;

use Yii;

use humhub\modules\user\models\User;

use humhub\modules\share\models\ShareEntry;
use humhub\modules\share\widgets\ShareLink;

/**
 *  MentionSpacesEvents
 */
class Events extends \yii\base\Object
{
    /**
     *  Add widget to wall entry
     */
    public static function onWallEntryLinksInit($event)
    {
        $event->sender->addWidget(ShareLink::className(),
            array('object' => $event->sender->object->content),
            array('sortOrder' => 20)
        );
    }

    /**
     *  Remove related items when a content is deleted
     */
    public static function onContentDelete($event)
    {
        if($event->sender->object_model == ShareEntry::className())
            return;
        ShareEntry::deleteFor($event->sender);
    }

    /**
     *  Parse post content for mentions and create ShareMentions
     *  according to them.
     */
    public static function onPostInsert($event)
    {
        ShareEntry::parse($event->sender->content, $event->sender->message);
    }

    /**
     *  Parse post content for mentions and create or delete ShareMentions
     *  according to them.
     */
    public static function onPostUpdate($event)
    {
        ShareEntry::parse($event->sender->content, $event->sender->message, true);
    }
}

