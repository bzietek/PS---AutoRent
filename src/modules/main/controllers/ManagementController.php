<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use app\models\Role;
use app\models\LoginForm;
use Throwable;
use yii\base\Response;
use yii\web\HttpException;
use app\models\database\user\User;
use \Yii;

class ManagementController extends SiteController
{
    protected $allowedRoles = ['@'];
    protected $allUsersActions = ['profile'];


    /**
     * @throws Throwable
     */
    public function actionProfile($id): Response|string
    {
        $model = User::findOne($id);

        return $this->render('profile', [
            'model' => $model,
            'own' => $model->id === Yii::$app->user->id,
            'isAdmin' => Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR,
        ]);
    }

    public function actionEditProfile($id) : Response|string
    {
        if (
            (int) $id !== (int) Yii::$app->user->getIdentity()?->getId()
            && Yii::$app->user->getIdentity()?->role !== Role::ROLE_ADMINISTRATOR
        ) {
            return $this->redirect('/');
        }
        $user = User::findOne($id);
        $model = new User();
        $model->id = $user->id;
        $model->role = $user->role;
        $model->scenario = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR
        && (int) $id !== (int) Yii::$app->user->getIdentity()?->getId()
            ? User::SCENARIO_EDIT_ADMIN
            : User::SCENARIO_EDIT_SELF;
        if ($model->load($this->request->post()) && $model->validate()) {
            $user->fillWithNonEmptyAttributes($model);
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'User information updated successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update user information.');
            }
        }
        return $this->render('editProfile', [
            'model' => $model,
        ]);
    }

}
