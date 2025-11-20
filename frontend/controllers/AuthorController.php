<?php

namespace frontend\controllers;

use common\models\Author;
use common\models\AuthorSubscription;
use common\models\AuthorSubscriptionForm;
use common\models\AuthorSubscriptionVerification;
use common\services\contracts\SmsServiceInterface;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AuthorController extends Controller
{
    /**
     * @var SmsServiceInterface|null
     */
    private $smsService;

    /**
     * @param string $id
     * @param \yii\base\Module $module
     * @param SmsServiceInterface|null $smsService
     * @param array $config
     */
    public function __construct($id, $module, SmsServiceInterface $smsService = null, $config = [])
    {
        $this->smsService = $smsService;
        parent::__construct($id, $module, $config);
    }
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

        $phoneInSession = Yii::$app->session->get("subscription_phone_{$model->id}", null);
        $codeSent = $phoneInSession !== null;

        if ($subscriptionForm->load(Yii::$app->request->post())) {
            if (empty($subscriptionForm->verificationCode)) {
                if ($subscriptionForm->validate(['phone', 'authorId'])) {
                    $existingSubscription = AuthorSubscription::find()
                        ->where(['author_id' => $subscriptionForm->authorId, 'phone' => $subscriptionForm->phone])
                        ->one();

                    if ($existingSubscription !== null) {
                        Yii::$app->session->setFlash('info', 'Вы уже подписаны на обновления этого автора.');
                        return $this->refresh();
                    }

                    if ($this->smsService !== null) {
                        $code = AuthorSubscriptionVerification::generateCode();
                        $expiresAt = time() + 600;

                        AuthorSubscriptionVerification::deleteAll([
                            'author_id' => $subscriptionForm->authorId,
                            'phone' => $subscriptionForm->phone,
                        ]);

                        $verification = new AuthorSubscriptionVerification();
                        $verification->author_id = $subscriptionForm->authorId;
                        $verification->phone = $subscriptionForm->phone;
                        $verification->code = $code;
                        $verification->expires_at = $expiresAt;

                        if ($verification->save()) {
                            $message = "Ваш код подтверждения: {$code}";
                            if ($this->smsService->send($subscriptionForm->phone, $message)) {
                                Yii::$app->session->set("subscription_phone_{$model->id}", $subscriptionForm->phone);
                                Yii::$app->session->setFlash('success', 'Код подтверждения отправлен на ваш номер телефона.');
                                $codeSent = true;
                                $phoneInSession = $subscriptionForm->phone;
                            } else {
                                Yii::$app->session->setFlash('error', 'Не удалось отправить SMS. Попробуйте позже.');
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'Ошибка при создании кода подтверждения.');
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Служба отправки SMS не настроена.');
                    }
                }
            } else {
                if (empty(trim($subscriptionForm->verificationCode))) {
                    Yii::$app->session->setFlash('error', 'Введите код подтверждения.');
                    return $this->refresh();
                }

                if ($phoneInSession === null || $phoneInSession !== $subscriptionForm->phone) {
                    Yii::$app->session->setFlash('error', 'Неверный номер телефона. Начните подписку заново.');
                    Yii::$app->session->remove("subscription_phone_{$model->id}");
                    return $this->refresh();
                }

                if (!$subscriptionForm->validate(['phone', 'authorId', 'verificationCode'])) {
                    return $this->refresh();
                }

                $verification = AuthorSubscriptionVerification::find()
                    ->where([
                        'author_id' => $subscriptionForm->authorId,
                        'phone' => $subscriptionForm->phone,
                        'code' => trim($subscriptionForm->verificationCode),
                    ])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->one();

                if ($verification === null || $verification->isExpired()) {
                    Yii::$app->session->setFlash('error', 'Неверный или устаревший код подтверждения.');
                    return $this->refresh();
                }

                $existingSubscription = AuthorSubscription::find()
                    ->where(['author_id' => $subscriptionForm->authorId, 'phone' => $subscriptionForm->phone])
                    ->one();

                if ($existingSubscription === null) {
                    $subscription = new AuthorSubscription();
                    $subscription->author_id = $subscriptionForm->authorId;
                    $subscription->phone = $subscriptionForm->phone;

                    if ($subscription->save()) {
                        AuthorSubscriptionVerification::deleteAll([
                            'author_id' => $subscriptionForm->authorId,
                            'phone' => $subscriptionForm->phone,
                        ]);
                        Yii::$app->session->remove("subscription_phone_{$model->id}");
                        Yii::$app->session->setFlash('success', 'Вы успешно подписались на обновления!');
                        return $this->refresh();
                    } else {
                        Yii::$app->session->setFlash('error', 'Ошибка при сохранении подписки.');
                    }
                } else {
                    AuthorSubscriptionVerification::deleteAll([
                        'author_id' => $subscriptionForm->authorId,
                        'phone' => $subscriptionForm->phone,
                    ]);
                    Yii::$app->session->remove("subscription_phone_{$model->id}");
                    Yii::$app->session->setFlash('info', 'Вы уже подписаны на обновления этого автора.');
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


