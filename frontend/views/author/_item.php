<?php

use common\models\Author;
use yii\helpers\Html;

/* @var $model Author */
?>

<div class="author-item">
    <h3>
        <?= Html::a(Html::encode($model->name), ['view', 'id' => $model->id]) ?>
    </h3>
</div>


