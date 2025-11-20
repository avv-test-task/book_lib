<?php

use common\models\Book;
use common\models\BookForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Book */
/* @var $formModel BookForm */

$this->title = 'Редактировать книгу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="book-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'formModel' => $formModel,
    ]) ?>
</div>


