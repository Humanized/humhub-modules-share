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

<?php
    $form = ActiveForm::begin([
        'action' => Url::to(["/share/share"])
    ])
?>
<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

<label class="control-label">Select spaces</label>
<ul class="media-list share-spaces">
<?php
function renderSpace($space, $entry) {
    $content = $entry ? $entry->content : null;
    $by = $content ? $content->getCreatedBy()->one() : null;
?>
    <img class="media-object img-rounded pull-left"
         src="<?php echo $space->getProfileImage()->getUrl(); ?>"
         style="width: 32px; height: 32px; margin-right: 1em;">
    <div class="media-body">
        <h4 class="media-heading"><?= Html::encode($space->name); ?>
        <?php if($content): ?>
        <h5><a href="<?= $content->url ?>"
                title="<?= $content->created_at ?>">
            Shared by <?= $by ? $by->getDisplayName() : '' ?>
        </a></h5>
        <?php endif ?>
    </div>
<?php
}

foreach ($spaces as $space) :
    $entry = isset($entries[$space->id]) ? $entries[$space->id] : null;
?>
    <li class="media">
        <?php if($entry) : ?>
        <label>
        <?php else: ?>
        <input type="checkbox" id="share-space-<?= $space->id ?>"
            name="ShareModalForm[spaces][]" value="<?= $space->id ?>">
        <label for="share-space-<?= $space->id ?>">
        <?php endif ?>
        <?php renderSpace($space, $entry) ?>
        </label>
    </li>
<?php endforeach; ?>
</ul>

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

