<?php

use humhub\libs\Html;
use humhub\widgets\TimeAgo;
use humhub\modules\space\models\Space;
use humhub\modules\user\widgets\Image as UserImage;
use humhub\modules\content\widgets\WallEntryControls;
use humhub\modules\space\widgets\Image as SpaceImage;
use humhub\modules\content\widgets\WallEntryAddons;
use humhub\modules\content\widgets\WallEntryLabels;
use yii\helpers\Url;

/* @var $object \humhub\modules\content\components\ContentContainerActiveRecord */
/* @var $renderControls boolean */
/* @var $wallEntryWidget string */
/* @var $user \humhub\modules\user\models\User */
/* @var $showContentContainer \humhub\modules\user\models\User */
?>



<div class="panel panel-default wall_<?= $object->getUniqueId(); ?>">
    <div class="panel-body">
        <div class="media">
            <!-- since v1.2 -->
            <div class="stream-entry-loader"></div>

            <!-- start: show wall entry options -->
            <?php if($renderControls) : ?>
                <ul class="nav nav-pills preferences">
                    <li class="dropdown ">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-label="<?= Yii::t('base', 'Toggle stream entry menu'); ?>" aria-haspopup="true">
                            <i class="fa fa-angle-down"></i>
                        </a>


                            <ul class="dropdown-menu pull-right">
                                <?= WallEntryControls::widget(['object' => $object, 'wallEntryWidget' => $wallEntryWidget]); ?>
                            </ul>
                    </li>
                </ul>
            <?php endif; ?>
            <!-- end: show wall entry options -->

            <div class="media-body">
                <div class="media-heading" style="margin-bottom: 1em;">
                    <?= Html::containerLink($user) ?> has mentioned this space in
                    <?= Html::containerLink($related->content->container) ?>
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
