<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use app\models\database\user\LoginForm;
use app\models\database\user\Role;
use app\models\database\user\User;
use Yii;

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
        if ($model->load($this->request->post())) {
            $model->role = Role::ROLE_CUSTOMER;
            $model->active = false;
            if ($model->signup()) {
                return $this->redirect('/login');
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionInactive()
    {
        $user = Yii::$app->user->getIdentity();
        if ($user->active) {
            return $this->redirect('/');
        }
        return $this->render('inactive', []);
    }

    public function actionActivate()
    {
        //TODO: add token checking logic
        $user = Yii::$app->user->getIdentity();
        if (YII_ENV === 'dev') {
            $user->active = true;
            $user->save();
            return $this->render('activated', []);
        }
        if ($user->active) {
            return $this->redirect('/');
        }
        $user->active = true;
        $user->save();
        return $this->render('activated', []);
    }
}
