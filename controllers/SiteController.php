<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use app\models\Roy;
use app\models\Employee;
use yii\helpers\Url;
use yii\web\Session;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        // if (!Yii::$app->user->isGuest) {
        //     return $this->render('index');    
        //  } else {
        //     $this->redirect(['site/login']);  
        //  }


        if (Yii::$app->user->isGuest) {            
            $this->redirect(['site/login']);  
        } 

        return $this->render('index');
    }

    public function actionSignup()
    {

        $model = new Employee();        
        if ($model->load(Yii::$app->request->post())) {
            //檢查帳號是否存在
            $isUsernameExist = Employee::find()->where(['username' => $_POST['Employee']['username']])->one();

            //已經存在
            if ($isUsernameExist) {
                Yii::$app->session->setFlash('duplicateUsername');                
                return $this->refresh();
            } else {

                $model->username = $_POST['Employee']['username'];
                $model->name = $_POST['Employee']['name'];
                $hash = Yii::$app->getSecurity()->generatePasswordHash($_POST['Employee']['password']);
                $model->password = $hash;
                $model->save();

                // var_dump($model->getErrors());
                // exit("123");
                if (!$model->hasErrors()) {
                    Yii::$app->session->setFlash('signupSuccess');
                    return $this->redirect(['site/login']);
                    // return $this->goHome(); 
                }
                               
            }  
        }

        return $this->render('signup', [
            'model' => $model,                       
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
        	// $session = Yii::$app->session;
        	// $session->open();
         //    $_SESSION['id'] = Yii::$app->user->identity->id;
            return $this->goHome();
            // $this->redirect(['employee/index']);
        }

        $model = new LoginForm();
        
        //如果登入成功
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return $this->goBack();
            $this->redirect(['employee/index']);
        }

        // $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
        // $this->redirect(['site/login']); 
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {

        return $this->render('about');
    }

    public function actionSay()
    {
        return $this->render('say');
    }

    public function actionEntry()
    {
        $model = new EntryForm;

                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    // 验证 $model 收到的数据

                    // 做些有意义的事 ...

                    return $this->render('entry-confirm', ['model' => $model]);
                } else {
                    // 无论是初始化显示还是数据验证错误
                    return $this->render('entry', ['model' => $model]);
                }
            
    }
}
