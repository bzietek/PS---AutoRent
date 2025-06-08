<?php

namespace app\migrations;

use yii\db\Migration;

class M250608113726InitTheDb extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //TODO: remove this line
        return false;
        $this->execute('CREATE SEQUENCE car_seq START WITH 1 INCREMENT BY 1');
        $this->createTable('CAR', [
            'id' => $this->primaryKey(),
            'name' => $this->string(40)->notNull(),
            'brand' => $this->string(40)->notNull(),
            'model' => $this->string(40)->notNull(),
            'year' => $this->date()->notNull(),
            'VIN' => $this->string(17)->unique(),
            'status' => $this->string(40)->notNull(),
            'price_ratio' => $this->boolean()->notNull()->defaultValue(0),
            'localization' => $this->boolean()->notNull()->defaultValue(0),
        ]);
        $this->execute('
        CREATE TRIGGER trg_car_id
        BEFORE INSERT ON "CAR"
        FOR EACH ROW
        BEGIN
          IF :NEW."id" IS NULL THEN
            SELECT car_seq.NEXTVAL INTO :NEW."id" FROM DUAL;
          END IF;
        END;
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M250608113726InitTheDb cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M250608113726InitTheDb cannot be reverted.\n";

        return false;
    }
    */
}
