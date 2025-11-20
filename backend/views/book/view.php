<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this book?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            'year',
            'isbn',
            [
                'label' => 'Authors',
                'value' => implode(', ', array_map(static function ($author) {
                    return $author->name;
                }, $model->authors)),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?php if ($model->cover_path): ?>
        <p>
            <strong>Cover:</strong><br>
            <img src="<?= Html::encode($model->cover_path) ?>" alt="<?= Html::encode($model->name) ?>" style="max-width: 200px;">
        </p>
    <?php endif; ?>
</div>


