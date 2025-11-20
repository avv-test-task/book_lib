<?php

use common\models\Book;
use yii\helpers\Html;

/* @var $model Book */
?>

<div class="book-item">
    <h3>
        <?= Html::a(Html::encode($model->name), ['view', 'id' => $model->id]) ?>
    </h3>
    <p>
        <?php if ($model->year): ?>
            <strong><?= Html::encode($model->year) ?></strong>
        <?php endif; ?>
        <?php if ($model->isbn): ?>
            &nbsp;· ISBN: <?= Html::encode($model->isbn) ?>
        <?php endif; ?>
    </p>
    <p>
        Авторы:
        <?= implode(', ', array_map(static function ($author) {
            return Html::encode($author->name);
        }, $model->authors)) ?>
    </p>
</div>


