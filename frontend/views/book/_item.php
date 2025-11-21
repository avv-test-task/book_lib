<?php

use common\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model Book */
?>

<div class="book-item" style="display: flex; gap: 15px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
    <?php if ($model->getCoverUrl()): ?>
        <div style="flex-shrink: 0;">
            <?= Html::a(
                Html::img($model->getCoverUrl(), [
                    'alt' => Html::encode($model->name),
                    'style' => 'max-width: 100px; height: auto; display: block;'
                ]),
                ['view', 'id' => $model->id]
            ) ?>
        </div>
    <?php endif; ?>
    <div style="flex: 1;">
        <h3 style="margin-top: 0;">
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
</div>


