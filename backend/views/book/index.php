<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'year',
            'isbn',
            [
                'label' => 'Authors',
                'value' => function (\common\models\Book $model) {
                    return implode(', ', array_map(static function ($author) {
                        return $author->name;
                    }, $model->authors));
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]) ?>
</div>


