<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\widgets\ModalDialog;

/**
 *  @var Content $object
 *  @var ShareModalForm $model
 *  @var [Space->id => ShareEntry] $entries
 *  @var [Space] $spaces
 */

ModalDialog::begin(['header' => 'Share']);
?>
<div class="modal-body media-body">

<?php if($message): ?>
<div class="alert-info"><?= $message ?></div>
<?php endif ?>

<?php /*
<ul class="nav nav-tabs" role="tablist">
    <li role="tab"><a>Share to</a></li>
    <li role="presentation" class="active"><a href="#share-to-spaces"
        aria-controls="share-to-spaces" role="tab" data-toggle="tab">Spaces</a></li>
    <li role="presentation"><a href="#share-to-users"
         aria-controls="share-to-users" role="tab" data-toggle="tab">User Pages</a></li>
</ul> */ ?>
<label>Select spaces</label>

<?php
    $form = ActiveForm::begin([
        'action' => Url::to(["/share/share"])
    ])
?>
<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>


<div class="tab-content">
    <ul role="tabpanel" class="tab-pane media-list active"
            id="share-to-spaces">
        <?php
        foreach ($spaces as $space) :
            $entry = isset($entries[$space->id]) ? $entries[$space->id] : null;
            $content = $entry ? $entry->content : null;
            $by = $content ? $content->getCreatedBy()->one() : null;
        ?>
        <li class="media full-width"><label class="full-width">
            <img class="media-object img-rounded pull-left"
                 src="<?= $space->getProfileImage()->getUrl() ?>"
                 style="width: 32px; height: 32px; margin-right: 1em;">
            <h4 class="media-body">
                <?= Html::encode($space->name) ?>
                <?php if($content):
                    $title = $by ?
                        "Shared by $by->displayName, $content->created_at" :
                        "Shared at $content->created_at";
                ?>
                <a href="<?= $content->url ?>" title="<?= $title ?>" class="pull-right">
                    <span class="fa fa-link"></span>
                </a>
                <?php else: ?>
                <span class="pull-right">
                    <input type="checkbox" id="share-space-<?= $space->id ?>"
                        name="ShareModalForm[spaces][]" value="<?= $space->id ?>">
                </span>
                <?php endif ?>
            </h4>
        </label></li>
        <?php endforeach; ?>
    </ul>

    <div role="tabpanel" class="tab-pane" id="share-to-users">
    </div>
</div>

<?= $form->field($model, 'message')->textInput([
    'placeHolder' => 'Optionally, add a message'
]) ?>

<hr>

<button data-action-click="ui.modal.submit"
    data-action-url="<?= Url::to(["/share/share"]) ?>"
    class="btn btn-success pull-right">Share!</button>

<a href="<?= $object->url ?>" class="fa fa-link"> Original content</a>

<?php ActiveForm::end() ?>

</div>
<?php ModalDialog::end() ?>

