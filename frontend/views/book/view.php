<?php

use common\models\Book;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Авторы:
        <?= implode(', ', array_map(static function ($author) {
            return Html::encode($author->name);
        }, $model->authors)) ?>
    </p>

    <?php if ($model->year || $model->isbn): ?>
        <p>
            <?php if ($model->year): ?>
                <strong><?= Html::encode($model->year) ?></strong>
            <?php endif; ?>
            <?php if ($model->isbn): ?>
                &nbsp;· ISBN: <?= Html::encode($model->isbn) ?>
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <?php if ($model->cover_path): ?>
        <p>
            <img src="<?= Html::encode($model->cover_path) ?>" alt="<?= Html::encode($model->name) ?>" style="max-width: 250px;">
        </p>
    <?php endif; ?>

    <?php if ($model->description): ?>
        <div class="book-description">
            <p><?= nl2br(Html::encode($model->description)) ?></p>
        </div>
    <?php endif; ?>
</div>


