<?php

use common\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;

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
        <?= implode(', ', array_map(static fn($author) => Html::a(Html::encode($author->name), ['/author/view', 'id' => $author->id]), $model->authors)) ?>
    </p>
</div>


