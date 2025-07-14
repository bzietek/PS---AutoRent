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
        //LOCATION TABLE
        $this->execute('CREATE SEQUENCE LOCATION_SEQ START WITH 1 INCREMENT BY 1');
        $this->createTable('LOCATION', [
            'id' => $this->primaryKey(),
        ]);
        $this->execute('
        CREATE TRIGGER trg_location_id
        BEFORE INSERT ON "LOCATION"
        FOR EACH ROW
        BEGIN
          IF :NEW."id" IS NULL THEN
            SELECT LOCATION_SEQ.NEXTVAL INTO :NEW."id" FROM DUAL;
          END IF;
        END;
        ');

        //CAR TABLE
        $this->execute('CREATE SEQUENCE CAR_SEQ START WITH 1 INCREMENT BY 1');
        $this->createTable('CAR', [
            'id' => $this->primaryKey(),
            'brand' => $this->string(40)->notNull(),
            'model' => $this->string(40)->notNull(),
            'year' => $this->date()->notNull(),
            'VIN' => $this->string(17)->unique(),
            'status' => $this->string(40)->notNull(),
            'price_per_day' => $this->decimal(6,2)->notNull(),
            'location_id' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk-car-location_id',
            'CAR',
            'location_id',
            'LOCATION',
            'id',
            'CASCADE'
        );
        $this->execute('
        CREATE TRIGGER trg_car_id
        BEFORE INSERT ON "CAR"
        FOR EACH ROW
        BEGIN
          IF :NEW."id" IS NULL THEN
            SELECT CAR_SEQ.NEXTVAL INTO :NEW."id" FROM DUAL;
          END IF;
        END;
        ');

        //ORDER TABLE
        $this->execute('CREATE SEQUENCE ORDER_SEQ START WITH 1 INCREMENT BY 1');
        $this->createTable('CAR_ORDER', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'car_id' => $this->integer()->notNull(),
            'date_start' => $this->date()->notNull(),
            'date_end' => $this->date()->notNull(),
            'status' => $this->string(40)->notNull(),
            'price' => $this->decimal(6,2)->notNull(),
            'used_fuel' => $this->integer(),
            'distance' => $this->integer(),
            'placed_at' => $this->date()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-order-car_id',
            'CAR_ORDER',
            'car_id',
            'CAR',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-order-user_id',
            'CAR_ORDER',
            'user_id',
            'APP_USER',
            'id',
            'CASCADE'
        );
        $this->execute('
        CREATE TRIGGER trg_car_order_id
        BEFORE INSERT ON "CAR_ORDER"
        FOR EACH ROW
        BEGIN
          IF :NEW."id" IS NULL THEN
            SELECT ORDER_SEQ.NEXTVAL INTO :NEW."id" FROM DUAL;
          END IF;
        END;
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP TRIGGER TRG_CAR_ORDER_ID');
        $this->execute('DROP SEQUENCE ORDER_SEQ');
        $this->dropForeignKey('fk-order-user_id', 'CAR_ORDER');
        $this->dropForeignKey('fk-order-car_id', 'CAR_ORDER');
        $this->dropTable('CAR_ORDER');

        $this->execute('DROP TRIGGER TRG_CAR_ID');
        $this->execute('DROP SEQUENCE CAR_SEQ');
        $this->dropForeignKey('fk-car-location_id', 'CAR');
        $this->dropTable('CAR');

        $this->execute('DROP TRIGGER TRG_LOCATION_ID');
        $this->execute('DROP SEQUENCE LOCATION_SEQ');
        $this->dropTable('LOCATION');
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
