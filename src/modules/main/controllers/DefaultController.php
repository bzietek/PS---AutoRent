<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use yii\web\HttpException;
use Yii;
use app\models\database\user\Role;

class DefaultController extends SiteController
{
    protected $allUsersActions = ['index', 'test', 'about', 'contact'];
    protected $guestActions = ['contact'];

    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/main';
        $user = Yii::$app->user?->getIdentity();

        if ($user === null || $user->role === Role::ROLE_CUSTOMER) {
            return $this->render('landing');
        }

        return $this->render('index');
    }

    public function actionAbout()
    {
        $this->layout = '@app/views/layouts/main';
        return $this->render('about');
    }

    public function actionContact()
    {
        $this->layout = '@app/views/layouts/main';
        return $this->render('contact');
    }

    public function actionTest()
    {
        throw new HttpException(403);
    }
}

