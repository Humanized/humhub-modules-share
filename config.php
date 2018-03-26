<?php

use humhub\modules\content\models\Content;
use humhub\modules\content\widgets\WallEntryLinks;
use humhub\modules\post\models\Post;

return [
    'id' => 'share',
    'class' => 'humhub\modules\share\Module',
    'namespace' => 'humhub\modules\share',

    'events' => [
        [ 'class' => WallEntryLinks::className(),
          'event' => WallEntryLinks::EVENT_INIT,
          'callback' => ['humhub\modules\share\Events', 'onWallEntryLinksInit'] ],

        [ 'class' => Content::className(), 'event' => Post::EVENT_BEFORE_DELETE,
          'callback' => ['humhub\modules\share\Events', 'onContentDelete'] ],

        // specifics
        [ 'class' => Post::className(), 'event' => Post::EVENT_AFTER_INSERT,
          'callback' => ['humhub\modules\share\Events', 'onPostInsert'] ],
        [ 'class' => Post::className(), 'event' => Post::EVENT_AFTER_UPDATE,
          'callback' => ['humhub\modules\share\Events', 'onPostUpdate'] ],

        /*[ 'class' => '\humhub\modules\calendar\interfaces\CalendarInterface',
          'event' => 'findItems',
          'callback' => ['humhub\modules\share\Events', 'onCalendarFindItems'] ],*/
    ],
];

?>

