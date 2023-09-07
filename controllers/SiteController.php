<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        // check user is logged in
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        // get docker lists
        exec('docker container ls -a --format=json', $dockers);
        $dockers = array_map(fn ($docker) => json_decode($docker, true), $dockers);

        return $this->render('index', ['dockers' => $dockers]);
    }

    public function actionStart($id)
    {
        // start docker
        $status = exec("docker container start $id");

        if ($status != $id) {
            // redirect with error message
            Yii::$app->session->setFlash('error', 'Failed to start container');
            return $this->goHome();
        }

        // redirect with success message
        Yii::$app->session->setFlash('success', 'Container started successfully');
        return $this->goHome();
    }

    public function actionStop($id)
    {
        // stop docker
        $status = exec("docker container stop $id");

        if ($status != $id) {
            // redirect with error message
            Yii::$app->session->setFlash('error', 'Failed to stop container');
            return $this->goHome();
        }

        // redirect with success message
        Yii::$app->session->setFlash('success', 'Container stopped successfully');
        return $this->goHome();
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
