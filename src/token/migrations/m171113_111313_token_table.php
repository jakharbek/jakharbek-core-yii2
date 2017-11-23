<?php

use yii\db\Migration;

class m171113_111313_token_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('token', [
            'id' => $this->primaryKey()->unique()->comment("Уникальный порядковый номер"),
            'type' => $this->string(255)->notNull()->comment("Тип"),
            'value' => $this->string(255)->notNull()->comment("Значение"),
            'description' => $this->string(255)->comment("Описание"),
            'token' => $this->string(255)->notNull()->comment("Токен"),
            'ip' => $this->string(255)->notNull()->comment("IP"),
            'user_uid' => $this->string(255)->comment("Пользаватель"),
            'add_date' =>  $this->integer(50)->notNull()->comment("Дата добавление"),
            'update_date' => $this->integer(50)->notNull()->comment("Дата обновление"),
            'status' => $this->integer(50)->notNull()->comment("Статус"),
        ]);
    }

    public function down()
    {
        $this->dropTable('token');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m171113_111313_token_table cannot be reverted.\n";

        return false;
    }
    */
}
