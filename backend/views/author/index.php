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


