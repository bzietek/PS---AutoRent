<?php

namespace app\migrations;

use yii\db\Migration;
use \Yii;

class M250508171859InitUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // reserve id number one for the initial admin account
        $this->execute('CREATE SEQUENCE user_seq START WITH 1 INCREMENT BY 1');
        $this->createTable('APP_USER', [
            'id' => $this->primaryKey(),
            'name' => $this->string(40)->notNull(),
            'surname' => $this->string(40)->notNull(),
            'email' => $this->string(40)->unique()->notNull(),
            'password' => $this->string(150)->notNull(),
            'phone_number' => $this->string(12),
            'role' => $this->string(40)->notNull(),
            'active' => $this->boolean()->notNull(),
        ]);
        $this->execute('
        CREATE TRIGGER trg_app_user_id
        BEFORE INSERT ON "APP_USER"
        FOR EACH ROW
        BEGIN
          SELECT user_seq.NEXTVAL INTO :NEW."id" FROM DUAL;
        END;
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP TRIGGER trg_app_user_id');
        $this->dropTable('APP_USER');
        $this->execute('DROP SEQUENCE user_seq');
    }
}
