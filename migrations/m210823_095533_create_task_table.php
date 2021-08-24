<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m210823_095533_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(10)->notNull(),
            'date' => $this->date()->notNull(),
            'day' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task}}');
    }
}
