<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200328_185448_address
 */
class m200328_185448_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('d_address',[
            "address_id" => Schema::TYPE_PK,
            "zipcode" => Schema::TYPE_STRING,
            "city" => Schema::TYPE_STRING,
            "street" => Schema::TYPE_STRING,
            "country" => Schema::TYPE_STRING,
            "latitude" => Schema::TYPE_FLOAT,
            "longitude" => Schema::TYPE_FLOAT
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200328_185448_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200328_185448_address cannot be reverted.\n";

        return false;
    }
    */
}
