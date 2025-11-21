<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Book;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find()->with('authors'),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $model = Book::find()->with('authors')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('Книга не найдена.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}


