<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать автора', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['class' => 'action-column'],
                'contentOptions' => ['class' => 'action-column'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('Просмотр', $url, [
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('Редактировать', $url, [
                            'class' => 'btn btn-sm btn-warning',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('Удалить', $url, [
                            'class' => 'btn btn-sm btn-danger',
                            'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                            'data-method' => 'post',
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>
</div>


