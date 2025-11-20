<?php

namespace frontend\controllers;

use common\models\Author;
use common\models\AuthorSubscription;
use common\models\AuthorSubscriptionForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
            throw new NotFoundHttpException('Запрошенный автор не существует.');
        }

        $subscriptionForm = new AuthorSubscriptionForm();
        $subscriptionForm->authorId = $model->id;

        if ($subscriptionForm->load(Yii::$app->request->post())) {
            $subscriptionForm->phone = AuthorSubscriptionForm::normalizePhone($subscriptionForm->phone);

            if ($subscriptionForm->validate()) {
                $existingSubscription = AuthorSubscription::find()
                    ->where(['author_id' => $subscriptionForm->authorId, 'phone' => $subscriptionForm->phone])
                    ->one();

                if ($existingSubscription === null) {
                    $subscription = new AuthorSubscription();
                    $subscription->author_id = $subscriptionForm->authorId;
                    $subscription->phone = $subscriptionForm->phone;

                    if ($subscription->save()) {
                        Yii::$app->session->setFlash('success', 'Вы успешно подписались на обновления!');
                    } else {
                        Yii::$app->session->setFlash('error', 'Ошибка при сохранении подписки.');
                    }
                } else {
                    Yii::$app->session->setFlash('info', 'Вы уже подписаны на обновления этого автора.');
                }

                return $this->refresh();
            }
        }

        return $this->render('view', [
            'model' => $model,
            'subscriptionForm' => $subscriptionForm,
        ]);
    }

    /**
     * Subscribes to author updates.
     *
     * @param int $id
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function actionSubscribe($id)
    {
        $author = Author::find()->where(['id' => $id])->one();

        if ($author === null) {
            throw new NotFoundHttpException('Запрошенный автор не существует.');
        }

        $form = new AuthorSubscriptionForm();
        $form->authorId = $id;

        if ($form->load(Yii::$app->request->post())) {
            $form->phone = AuthorSubscriptionForm::normalizePhone($form->phone);

            if ($form->validate()) {
                $existingSubscription = AuthorSubscription::find()
                    ->where(['author_id' => $form->authorId, 'phone' => $form->phone])
                    ->one();

                if ($existingSubscription === null) {
                    $subscription = new AuthorSubscription();
                    $subscription->author_id = $form->authorId;
                    $subscription->phone = $form->phone;

                    if ($subscription->save()) {
                        Yii::$app->session->setFlash('success', 'Вы успешно подписались на обновления!');
                    } else {
                        Yii::$app->session->setFlash('error', 'Ошибка при сохранении подписки.');
                    }
                } else {
                    Yii::$app->session->setFlash('info', 'Вы уже подписаны на обновления этого автора.');
                }
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }
}


