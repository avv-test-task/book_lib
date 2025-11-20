<?php

use common\models\BookForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $formModel BookForm */

$this->title = 'Create Book';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'formModel' => $formModel,
    ]) ?>
</div>


