<?php

use yii\helpers\Url;

/**
 *  @var $object: wall entry's Content
 *  @var $entries: ActiveQuery of entries related to $object
 */
?>
<a href="#" data-action-click="ui.modal.load"
   data-action-url="<?=
        Url::toRoute(["/share/share", "object" => $object->id ])
   ?>"
>Share<?php
$count = $entries->count();
if($count) {
?>
    <span class="shared_info">(<?= $count ?>)</span>
<?php
}
?></a>

