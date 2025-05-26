<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use app\models\Role;
use yii\web\HttpException;

class DefaultController extends SiteController
{
    protected $allUsersActions = ['index', 'test'];
    protected $guestActions = ['contact'];
    // protected $allowedRoles = [Role::ROLE_ADMINISTRATOR];
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionContact()
    {
        return $this->render('contact');
    }

    public function actionTest()
    {
        throw new HttpException(403);
    }
}
