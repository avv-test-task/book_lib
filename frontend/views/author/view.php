<?php

use common\models\Author;
use common\models\AuthorSubscriptionForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Author */
/* @var $subscriptionForm AuthorSubscriptionForm */
/* @var bool $codeSent */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->books): ?>
        <h3>Книги автора</h3>
        <ul>
            <?php foreach ($model->books as $book): ?>
                <li>
                    <?= Html::a(Html::encode($book->name), ['/book/view', 'id' => $book->id]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>У этого автора пока нет книг.</p>
    <?php endif; ?>

    <div class="subscription-form" style="margin-top: 30px;">
        <h3>Подписаться на обновления</h3>
        <p>Получайте уведомления о новых книгах этого автора на ваш телефон</p>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
        ]); ?>

        <?= Html::activeHiddenInput($subscriptionForm, 'authorId') ?>

        <?php if (!$codeSent): ?>
            <?= $form->field($subscriptionForm, 'phone')->textInput([
                'placeholder' => '+79001234567',
                'maxlength' => 20,
            ])->label('Номер телефона') ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить код подтверждения', ['class' => 'btn btn-success']) ?>
            </div>
        <?php else: ?>
            <?= $form->field($subscriptionForm, 'phone')->textInput([
                'readonly' => true,
                'maxlength' => 20,
            ])->label('Номер телефона') ?>

            <?= $form->field($subscriptionForm, 'verificationCode')->textInput([
                'placeholder' => '0000',
                'maxlength' => 4,
                'style' => 'width: 150px;',
            ])->label('Код подтверждения') ?>

            <div class="form-group">
                <?= Html::submitButton('Подтвердить и подписаться', ['class' => 'btn btn-success']) ?>
            </div>
        <?php endif; ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>


