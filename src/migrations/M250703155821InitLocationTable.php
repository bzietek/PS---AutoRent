<?php

namespace app\migrations;

use yii\db\Migration;

class M250703180000UpdateLocationTable extends Migration
{
    public function safeUp()
    {
        // Jeśli tabela LOCATION nie istnieje – utwórz ją
        if ($this->db->schema->getTableSchema('LOCATION', true) === null) {
            $this->createTable('LOCATION', [
                'id' => $this->primaryKey(),
                'zip_code' => $this->string(20)->notNull(),
                'street_name' => $this->string(255)->notNull(),
                'street_number' => $this->string(20)->notNull(),
                'province' => $this->string(100)->notNull(),
                'iso_code' => $this->string(10)->notNull(),
            ]);
        } else {
            // Jeśli tabela istnieje – dodaj brakujące kolumny
            $table = $this->db->schema->getTableSchema('LOCATION', true);

            if (!isset($table->columns['zip_code'])) {
                $this->addColumn('LOCATION', 'zip_code', $this->string(20)->notNull());
            }
            if (!isset($table->columns['street_name'])) {
                $this->addColumn('LOCATION', 'street_name', $this->string(255)->notNull());
            }
            if (!isset($table->columns['street_number'])) {
                $this->addColumn('LOCATION', 'street_number', $this->string(20)->notNull());
            }
            if (!isset($table->columns['province'])) {
                $this->addColumn('LOCATION', 'province', $this->string(100)->notNull());
            }
            if (!isset($table->columns['iso_code'])) {
                $this->addColumn('LOCATION', 'iso_code', $this->string(10)->notNull());
            }
        }
    }

    public function safeDown()
    {
        $this->dropTable('LOCATION');
    }
}

