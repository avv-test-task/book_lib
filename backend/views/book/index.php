<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'columns' => [
            [
                'label' => 'Обложка',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 80px'],
                'contentOptions' => ['style' => 'width: 80px; text-align: center'],
                'value' => function (\common\models\Book $model) {
                    if ($model->cover_path && $coverUrl = $model->getCoverUrl()) {
                        return Html::img($coverUrl, [
                            'alt' => Html::encode($model->name),
                            'style' => 'max-width: 60px; max-height: 80px; object-fit: cover;',
                        ]);
                    }
                    return '<span class="not-set">(не задано)</span>';
                },
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (\common\models\Book $model) {
                    $name = Html::encode($model->name);
                    if (mb_strlen($name) > 40) {
                        return '<span title="' . $name . '">' . mb_substr($name, 0, 37) . '...</span>';
                    }
                    return $name;
                },
            ],
            [
                'attribute' => 'year',
                'headerOptions' => ['style' => 'width: 80px'],
                'contentOptions' => ['style' => 'width: 80px'],
            ],
            [
                'attribute' => 'isbn',
                'headerOptions' => ['style' => 'width: 120px'],
                'contentOptions' => ['style' => 'width: 120px'],
            ],
            [
                'label' => 'Авторы',
                'format' => 'raw',
                'value' => function (\common\models\Book $model): string {
                    $authors = array_map(static fn($author) => Html::encode($author->name), $model->authors);
                    $fullText = implode(', ', $authors);
                    $text = $fullText;
                    if (mb_strlen($text) > 40) {
                        $text = mb_substr($text, 0, 37) . '...';
                    }
                    return '<span title="' . Html::encode($fullText) . '">' . $text . '</span>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'headerOptions' => ['class' => 'action-column', 'style' => 'width: 200px !important; min-width: 200px !important;'],
                'contentOptions' => ['class' => 'action-column', 'style' => 'width: 200px !important; min-width: 200px !important; white-space: nowrap !important;'],
                'buttons' => [
                    'update' => fn($url, $model) => Html::a('Редактировать', $url, [
                        'class' => 'btn btn-sm btn-warning',
                    ]),
                    'delete' => fn($url, $model) => Html::a('Удалить', $url, [
                        'class' => 'btn btn-sm btn-danger',
                        'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                        'data-method' => 'post',
                    ]),
                ],
            ],
        ],
    ]) ?>
</div>


