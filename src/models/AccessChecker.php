<?php

namespace app\models;

use app\models\database\user\Role;
use app\models\database\user\User;
use yii\rbac\CheckAccessInterface;

class AccessChecker implements CheckAccessInterface
{
    public function checkAccess($userId, $permissionName, $params = []) : bool
    {
        if (array_key_exists($permissionName, Role::getRoles())) {
            return User::getRole($userId) === $permissionName;
        }
        return false;
    }
}
