<?php

namespace app\migrations;

use yii\db\Migration;

class M250703155821InitLocationTable extends Migration
{
    public function safeUp()
    {
        $this->addColumn('LOCATION', 'street_name', $this->string(255)->notNull());
        $this->addColumn('LOCATION', 'street_number', $this->string(20)->notNull());
        $this->addColumn('LOCATION', 'zip_code', $this->string(20)->notNull());
        $this->addColumn('LOCATION', 'province', $this->string(100)->notNull());
        $this->addColumn('LOCATION', 'iso_code', $this->string(10)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('LOCATION', 'street_name');
        $this->dropColumn('LOCATION', 'street_number');
        $this->dropColumn('LOCATION', 'zip_code');
        $this->dropColumn('LOCATION', 'province');
        $this->dropColumn('LOCATION', 'iso_code');
    }
}
