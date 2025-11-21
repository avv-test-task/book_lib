<?php

use common\models\Author;
use common\models\BookForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $formModel BookForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \common\models\Book|null */

$authors = ArrayHelper::map(Author::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
?>

<div class="book-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($formModel, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($formModel, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($formModel, 'year')->textInput() ?>
    <?= $form->field($formModel, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($formModel, 'authorIds')->checkboxList($authors) ?>

    <?php if (isset($model) && $model && $model->cover_path): ?>
        <div class="form-group">
            <label class="control-label">Текущая обложка</label>
            <div>
                <?php $coverUrl = $model->getCoverUrl(); ?>
                <img src="<?= Html::encode($coverUrl) ?>" alt="<?= Html::encode($model->name) ?>" style="max-width: 200px; margin-bottom: 10px; display: block;">
            </div>
            <?= $form->field($formModel, 'deleteCover')->checkbox(['label' => 'Удалить текущую обложку']) ?>
        </div>
    <?php endif; ?>

    <?= $form->field($formModel, 'coverFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


