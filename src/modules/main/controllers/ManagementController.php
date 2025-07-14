<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use app\models\database\user\Role;
use app\models\database\user\User;
use app\models\database\user\UserSearch;
use Yii;
use yii\base\Response;
use yii\web\Cookie;

class ManagementController extends SiteController
{
    protected $allowedRoles = ['@'];

    public function actionProfile($id) : Response|string
    {
        $userId = (int) Yii::$app->user->getIdentity()?->getId();
        if (
            $id !== null
            && (int) $id !== $userId
            && Yii::$app->user->getIdentity()?->role !== Role::ROLE_ADMINISTRATOR
        ) {
            return $this->redirect('/');
        }
        if ($id === null) {
            $id = $userId;
        }
        $user = User::findOne($id);
        $model = new User();
        $model->id = $user->id;
        $model->role = $user->role;
        $model->active = $user->active;
        $model->scenario = Yii::$app->user->getIdentity()?->role === Role::ROLE_ADMINISTRATOR
        && (int) $id !== (int) Yii::$app->user->getIdentity()?->getId()
            ? User::SCENARIO_EDIT_ADMIN
            : User::SCENARIO_EDIT_SELF;
        if ($model->load($this->request->post()) && $model->validate()) {
            $user->fillWithNonEmptyAttributes($model);
            $user->validate();
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

    public function actionProfiles($page = null, $count = null) : Response|string
    {
        if (
            Yii::$app->user->getIdentity()?->role !== Role::ROLE_ADMINISTRATOR
        ) {
            return $this->redirect('/profile');
        }

        if (isset($page)) {
            $page = (int) $page;
        } else {
            $page = 0;
        }
        if (isset($count)) {
            $count = (int) $count;
        }
        if ($count !== null) {
            if (Yii::$app->request->cookies->getValue('countPerPageProfiles') != $count) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageProfiles',
                    'value' => $count,
                ]));
            }
        } else {
            if (Yii::$app->request->cookies->has('countPerPageProfiles') === false) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'countPerPageProfiles',
                    'value' => USER::LIST_COUNT_PER_PAGE_DEFAULT,
                ]));
                $count = USER::LIST_COUNT_PER_PAGE_DEFAULT;
            } else {
                $count = (int) Yii::$app->request->cookies->getValue('countPerPageProfiles');
            }
        }

        $searchModel = new UserSearch();
        $searchModel->load(Yii::$app->request->get());
        $searchModel->validate();

        return $this->render('profileList', [
            'list' => $searchModel->search($count),
            'countPerPage' => $count,
            'searchModel' => $searchModel,
        ]);
    }
}
