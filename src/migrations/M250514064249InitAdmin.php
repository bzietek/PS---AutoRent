<?php

namespace app\migrations;

use app\models\database\user\Role;
use Yii;
use yii\db\Migration;

class M250514064249InitAdmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('APP_USER', [
            'id' => 1,
            'role' => Role::ROLE_ADMINISTRATOR,
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'root',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('root'),
            'active' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('APP_USER', [
            'id' => 1,
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M250514064249InitAdmin cannot be reverted.\n";

        return false;
    }
    */
}
