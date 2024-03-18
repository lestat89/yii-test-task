<?php

namespace app\controllers;

use app\forms\LoginForm;
use app\models\Users;
use app\models\UsersSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 *
 */
class UsersController extends Controller
{
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
                        'actions' => [
                            'index',
                            'login',
                            'registration',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'view',
                            'update',
                            'create',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return Response|string
     */
    public function actionIndex(): Response|string
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect(['/users/login']);

        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return Response|string
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login())
            return $this->goBack();

        $loginForm->password = '';

        return $this->render('login', [
            'model' => $loginForm,
        ]);
    }

    /**
     * @return Response|string
     * @throws \yii\base\Exception
     */
    public function actionRegistration(): Response|string
    {
        $userModel = new Users();

        if ($this->request->isPost) {
            $userData = $this->request->post();
            if ($userModel->load($userData) && $userModel->validate()) {
                $userModel->cryptPassword();
                if ($userModel->save())
                    return $this->goHome();
            }
        } else {
            $userModel->loadDefaultValues();
        }

        $userModel->password = '';

        return $this->render('create', [
            'model' => $userModel,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return Response|string
     * @throws \yii\base\Exception
     */
    public function actionCreate(): Response|string
    {
        $userModel = new Users();

        if ($this->request->isPost) {
            $userData = $this->request->post();
            if ($userModel->load($userData) && $userModel->validate()) {
                $userModel->cryptPassword();
                if ($userModel->save())
                    return $this->redirect(['view', 'id' => $userModel->id]);
            }
        } else {
            $userModel->loadDefaultValues();
        }

        $userModel->password = '';

        return $this->render('create', [
            'model' => $userModel,
        ]);
    }

    /**
     * @param int $id
     *
     * @return Response|string
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate(int $id): Response|string
    {
        $userModel = $this->findModel($id);

        if ($this->request->isPost) {
            $userData = $this->request->post();
            if ($userModel->load($userData) && $userModel->validate()) {
                if ($userModel->save())
                    return $this->redirect(['view', 'id' => $userModel->id]);
            }
        }

        return $this->render('update', [
            'model' => $userModel,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     *
     * @return Users
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Users
    {
        if (($userModel = Users::findOne(['id' => $id])) !== null)
            return $userModel;

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
