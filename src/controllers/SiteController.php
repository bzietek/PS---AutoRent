<?php

namespace app\controllers;

use app\models\AccessControl;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    //actions that are allowed to be accessed by ALL users (guests included)
    protected $allUsersActions = ['placeholder'];

    //actions that are allowed to be accessed by guests and allowedRoles
    protected $guestActions = ['placeholder'];
    //roles that are allowed to access all actions in the controller except guest only actions
    protected $allowedRoles = ['@'];
    //basic RBAC
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
                    //allow guests to use guest only actions
                    [
                        'actions' => $this->guestActions,
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    //forbid guests to use any other actions
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    //allow whitelisted users to use this controller
                    [
                        'allow' => true,
                        'roles' => $this->allowedRoles,
                    ]
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
                'class' => '\yii\web\ErrorAction',
            ],
        ];
    }
}
