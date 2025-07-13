
<?php

namespace app\controllers;

use app\models\AccessControl;
use app\models\database\user\Role;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class SiteController extends Controller
{
    // actions that are allowed to be accessed by ALL users (guests included)
    protected $allUsersActions = ['placeholder'];

    // actions that are allowed to be accessed by guests and allowedRoles
    protected $guestActions = ['placeholder'];

    // roles that are allowed to access all actions in the controller except guest only actions
    protected $allowedRoles = ['@'];

    // basic RBAC
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => $this->allUsersActions,
                        'allow' => true,
                    ],
                    // allow guests to use guest only actions
                    [
                        'actions' => $this->guestActions,
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    // forbid guests to use any other actions
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    // allow whitelisted users to use this controller
                    [
                        'allow' => true,
                        'roles' => $this->allowedRoles,
                    ]
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => '\yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action): \yii\web\Response|bool
    {
        if (parent::beforeAction($action) === false) {
            return false;
        }
        $user = Yii::$app->user;
        if ($user->isGuest) {
      
            return true;
        }

        $user = $user->getIdentity();
        if (
            !$user->active
            && $user->role === Role::ROLE_CUSTOMER
            && !in_array($action->actionMethod ?? 'error', [
                'error',
                'actionInactive',
                'actionLogout',
                'actionActivate',
            ])
        ) {
            return $this->redirect(Url::to(['/inactive']));
        }

        return true;
    }


}

