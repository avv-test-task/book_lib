<?php

declare(strict_types=1);

namespace backend\controllers;

use backend\models\BookSearch;
use common\models\Book;
use common\models\BookForm;
use common\services\contracts\BookServiceInterface;
use DomainException;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class BookController extends Controller
{
    private BookServiceInterface $bookService;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(string $id, Module $module, BookServiceInterface $bookService, array $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new BookForm();

        if ($form->load(Yii::$app->request->post())) {
            $form->coverFile = UploadedFile::getInstance($form, 'coverFile');

            try {
                $book = $this->bookService->create($form);
                Yii::$app->session->setFlash('success', 'Книга создана успешно');

                return $this->redirect(['update', 'id' => $book->id]);
            } catch (DomainException $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'formModel' => $form,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $book = $this->findModel($id);

        $form = new BookForm();
        $form->loadFromBook($book);

        if ($form->load(Yii::$app->request->post())) {
            $form->coverFile = UploadedFile::getInstance($form, 'coverFile');

            try {
                $this->bookService->update($book, $form);
                Yii::$app->session->setFlash('success', 'Обновлено успешно');

                return $this->redirect(['update', 'id' => $book->id]);
            } catch (DomainException $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'formModel' => $form,
            'model' => $book,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $book = $this->findModel($id);
        $this->bookService->delete($book);
        Yii::$app->session->setFlash('success', 'Книга удалена успешно');

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    private function findModel(int $id): Book
    {
        if (($model = Book::find()->with('authors')->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested book does not exist.');
    }
}


