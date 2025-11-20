<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Author;
use common\models\AuthorSubscriptionForm;
use common\services\contracts\SubscriptionServiceInterface;
use Yii;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AuthorController extends Controller
{
    private SubscriptionServiceInterface $subscriptionService;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(string $id, Module $module, SubscriptionServiceInterface $subscriptionService, array $config = [])
    {
        $this->subscriptionService = $subscriptionService;
        parent::__construct($id, $module, $config);
    }
    public function actionIndex(): string
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

    public function actionView(int $id): string|Response
    {
        $model = Author::find()->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('Запрошенный автор не существует.');
        }

        $subscriptionForm = new AuthorSubscriptionForm();
        $subscriptionForm->authorId = $model->id;

        $phoneInSession = Yii::$app->session->get("subscription_phone_{$model->id}", null);
        $codeSent = $phoneInSession !== null;

        if ($subscriptionForm->load(Yii::$app->request->post())) {
            if (empty($subscriptionForm->verificationCode)) {
                if ($subscriptionForm->validate(['phone', 'authorId'])) {
                    $authorId = (int)$subscriptionForm->authorId;
                    if ($this->subscriptionService->subscriptionExists($authorId, $subscriptionForm->phone)) {
                        Yii::$app->session->setFlash('info', 'Вы уже подписаны на обновления этого автора.');
                        return $this->refresh();
                    }

                    $result = $this->subscriptionService->sendVerificationCode($authorId, $subscriptionForm->phone);
                    
                    if ($result['success']) {
                        Yii::$app->session->set("subscription_phone_{$model->id}", $subscriptionForm->phone);
                        $codeSent = true;
                        $phoneInSession = $subscriptionForm->phone;
                    }
                    
                    Yii::$app->session->setFlash($result['success'] ? 'success' : 'error', $result['message']);
                }
            } else {
                if ($phoneInSession === null || $phoneInSession !== $subscriptionForm->phone) {
                    Yii::$app->session->setFlash('error', 'Неверный номер телефона. Начните подписку заново.');
                    Yii::$app->session->remove("subscription_phone_{$model->id}");
                    return $this->refresh();
                }

                if (!$subscriptionForm->validate(['phone', 'authorId', 'verificationCode'])) {
                    return $this->refresh();
                }

                $authorId = (int)$subscriptionForm->authorId;
                $result = $this->subscriptionService->verifyAndSubscribe(
                    $authorId,
                    $subscriptionForm->phone,
                    $subscriptionForm->verificationCode
                );

                $flashType = $result['success'] ? ($result['alreadySubscribed'] ? 'info' : 'success') : 'error';
                Yii::$app->session->setFlash($flashType, $result['message']);

                if ($result['success']) {
                    Yii::$app->session->remove("subscription_phone_{$model->id}");
                    return $this->refresh();
                }
            }
        }

        if ($codeSent && $phoneInSession) {
            $subscriptionForm->phone = $phoneInSession;
        }

        return $this->render('view', [
            'model' => $model,
            'subscriptionForm' => $subscriptionForm,
            'codeSent' => $codeSent,
        ]);
    }

}


