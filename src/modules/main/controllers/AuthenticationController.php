<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use app\models\Role;
use app\models\LoginForm;
use yii\web\HttpException;
use app\models\database\user\User;
use \Yii;

class AuthenticationController extends SiteController
{
    protected $guestActions = ['login', 'signup'];

//    Role::ROLE_ADMINISTRATOR, Role::ROLE_CUSTOMER
    protected $allowedRoles = ['@'];


    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load($this->request->post()) && $model->login()) {
                return $this->redirect('/');
        }
        return $this->render('login',
        [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        if (Yii::$app->user->isGuest === false) {
            Yii::$app->user->logout();
        }
        return $this->render('logout');
    }

    public function actionSignup()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_SIGNUP;
        if ($model->load($this->request->post()) && ($model->role = Role::ROLE_CUSTOMER) && $model->signup()) {
            return $this->redirect('/login');
        }

        return $this->render('signup', [
            'model' => $model
        ]);
    }
}
