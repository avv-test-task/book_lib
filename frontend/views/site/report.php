<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $availableYears array */
/* @var $selectedYear int|null */
/* @var $authorsData array */

$this->title = 'Отчет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-report">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Топ 10 авторов, опубликовавших наибольшее количество книг за выбранный год</p>

    <div class="form-group" style="margin-bottom: 20px;">
        <form method="get" action="<?= Url::to(['site/report']) ?>" style="display: inline-block;">
            <label for="year-select" class="control-label">Выберите год:</label>
            <select id="year-select" name="year" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 10px;" onchange="this.form.submit();">
                <option value="">-- Выберите год --</option>
                <?php foreach ($availableYears as $year): ?>
                    <option value="<?= Html::encode($year) ?>" <?= (string)$selectedYear === (string)$year ? 'selected' : '' ?>>
                        <?= Html::encode($year) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($selectedYear !== null && $selectedYear !== ''): ?>
        <?php if (!empty($authorsData)): ?>
            <h2>Результаты за <?= Html::encode($selectedYear) ?> год</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">№</th>
                        <th>Автор</th>
                        <th style="width: 150px; text-align: center;">Количество книг</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($authorsData as $index => $author): ?>
                        <tr>
                            <td style="text-align: center;"><?= $index + 1 ?></td>
                            <td><?= Html::encode($author['name']) ?></td>
                            <td style="text-align: center;"><?= Html::encode($author['book_count']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                За выбранный год не найдено книг.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            Выберите год для отображения отчета.
        </div>
    <?php endif; ?>
</div>

