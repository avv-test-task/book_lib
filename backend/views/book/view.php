<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
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
                'label' => 'Авторы',
                'value' => implode(', ', array_map(static function ($author) {
                    return $author->name;
                }, $model->authors)),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?php if ($model->cover_path && $coverUrl = $model->getCoverUrl()): ?>
        <p>
            <strong>Обложка:</strong><br>
            <img src="<?= Html::encode($coverUrl) ?>" alt="<?= Html::encode($model->name) ?>" style="max-width: 200px;">
        </p>
    <?php endif; ?>
</div>


