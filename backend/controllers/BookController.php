<?php

namespace backend\controllers;

use backend\models\BookSearch;
use common\models\Book;
use common\models\BookForm;
use common\services\contracts\BookServiceInterface;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class BookController extends Controller
{
    /**
     * @var BookServiceInterface
     */
    private $bookService;

    /**
     * @param string              $id
     * @param \yii\base\Module    $module
     * @param BookServiceInterface $bookService
     * @param array               $config
     */
    public function __construct($id, $module, BookServiceInterface $bookService, $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
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

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Book model.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $form = new BookForm();

        if ($form->load(Yii::$app->request->post())) {
            $form->coverFile = UploadedFile::getInstance($form, 'coverFile');

            try {
                $book = $this->bookService->create($form);

                return $this->redirect(['update', 'id' => $book->id]);
            } catch (\DomainException $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'formModel' => $form,
        ]);
    }

    /**
     * Updates an existing Book model.
     *
     * @param int $id
     *
     * @return string|\yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
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
            } catch (\DomainException $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'formModel' => $form,
            'model' => $book,
        ]);
    }

    /**
     * Deletes an existing Book model.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $book = $this->findModel($id);
        $this->bookService->delete($book);

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     *
     * @return Book
     *
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if (($model = Book::find()->with('authors')->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested book does not exist.');
    }
}


