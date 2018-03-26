<?php

/**
 *  Note: we override default rendering of a Content because
 *  we have a slightly different design since we integrate other
 *  Content inside our.
 */

use humhub\libs\Html;
use humhub\modules\content\widgets\WallEntryControls;
use humhub\modules\content\widgets\WallEntryAddons;
use humhub\modules\content\widgets\WallEntryLabels;
use yii\helpers\Url;

/* @var $object \humhub\modules\content\components\ContentContainerActiveRecord */
/* @var $renderControls boolean */
/* @var $wallEntryWidget string */
/* @var $user \humhub\modules\user\models\User */
?>

<div class="panel panel-default wall_<?= $object->getUniqueId(); ?>">
    <div class="panel-body">
        <div class="media">
            <div class="stream-entry-loader"></div>

            <?php if($renderControls) : ?>
                <ul class="nav nav-pills preferences">
                    <li class="dropdown ">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"
                             aria-label="<?= Yii::t('base', 'Toggle stream entry menu'); ?>"
                             aria-haspopup="true">
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="dropdown-menu pull-right">
                            <?= WallEntryControls::widget(['object' => $object, 'wallEntryWidget' => $wallEntryWidget]); ?>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>

            <div class="media-body content">
                <div class="media-heading" style="margin-bottom: 1em;">
                    <?= Html::containerLink($user) ?> shared this from
                    <?= Html::a(
                        $entry->content->container->getDisplayName(),
                        $entry->content->url
                    ) ?>
                    on
                    <?= Html::a(
                        $object->content->container->getDisplayName(),
                        $object->content->url
                    ) ?>
                    :
                    <?php if($object->message): ?>
                    <div style="margin: 1em; font-size: 0.9em;">
                        <?= $object->message ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="content" id="wall_content_<?= $object->getUniqueId(); ?>">
                <?= $content; ?>
            </div>

            <!-- wall-entry-addons class required since 1.2 -->
            <?php if($renderAddons) : ?>
                <div class="stream-entry-addons clearfix">
                    <?= WallEntryAddons::widget($addonOptions); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
