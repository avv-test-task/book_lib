<?php

use common\models\Author;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Author */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->books): ?>
        <h3>Книги автора</h3>
        <ul>
            <?php foreach ($model->books as $book): ?>
                <li>
                    <?= Html::a(Html::encode($book->name), ['/book/view', 'id' => $book->id]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>У этого автора пока нет книг.</p>
    <?php endif; ?>
</div>


