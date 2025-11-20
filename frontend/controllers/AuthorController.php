<?php

namespace frontend\controllers;

use common\models\Author;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
{
    /**
     * Lists all Author models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Author::find(),
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
     * Displays a single Author model.
     *
     * @param int $id
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = Author::find()->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested author does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}


