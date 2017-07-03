<?php

use yii\db\Migration;

class m170624_044216_create_tbl_category extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{category}}', 
            [
                'id' => 'pk',
                'title' => 'VARCHAR(8) NOT NULL',
                'next' => 'INT(11) NOT NULL',
                'level' => 'INT(11) NOT NULL',
                'tree' => 'INT(11) NOT NULL',
            ], 
            'DEFAULT CHARSET=utf8 ENGINE = INNODB'
        );

    }

    public function safeDown()
    {
        echo "m170624_044216_create_tbl_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170624_044216_create_tbl_category cannot be reverted.\n";

        return false;
    }
    */
}
