<?php

namespace app\migrations;

use yii\db\Migration;

class M250703180000AddColumnsToLocations extends Migration
{
    public function safeUp()
    {
        $this->addColumn('locations', 'name', $this->string(100)->notNull());
        $this->addColumn('locations', 'address', $this->string(255)->notNull());
        $this->addColumn('locations', 'city', $this->string(100)->notNull());
        $this->addColumn('locations', 'postcode', $this->string(20)->notNull());
        $this->addColumn('locations', 'phone', $this->string(20)->defaultValue(null));
        $this->addColumn('locations', 'is_active', $this->boolean()->defaultValue(true));
    }

    public function safeDown()
    {
        $this->dropColumn('locations', 'name');
        $this->dropColumn('locations', 'address');
        $this->dropColumn('locations', 'city');
        $this->dropColumn('locations', 'postcode');
        $this->dropColumn('locations', 'phone');
        $this->dropColumn('locations', 'is_active');
    }
}


