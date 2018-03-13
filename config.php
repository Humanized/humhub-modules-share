<?php

use humhub\modules\space_mentioning\Module;

use humhub\modules\post\models\Post;

return [
    'id' => 'space_mentioning',
    'class' => 'humhub\modules\space_mentioning\Module',
    'namespace' => 'humhub\modules\space_mentioning',

    'events' => [
        [ 'class' => Post::className(), 'event' => Post::EVENT_AFTER_INSERT,
          'callback' => ['humhub\modules\space_mentioning\Events', 'onContentInsert'] ],
        [ 'class' => Post::className(), 'event' => Post::EVENT_AFTER_UPDATE,
          'callback' => ['humhub\modules\space_mentioning\Events', 'onContentUpdate'] ],
        [ 'class' => Post::className(), 'event' => Post::EVENT_BEFORE_DELETE,
          'callback' => ['humhub\modules\space_mentioning\Events', 'onContentDelete'] ],
    ],
];

?>

