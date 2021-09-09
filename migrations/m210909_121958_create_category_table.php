<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m210909_121958_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'count' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->insert('category', [
            'name' => 'Одноместный',
            'count' => '2',
        ]);

        $this->insert('category', [
            'name' => 'Двуместный',
            'count' => '4',
        ]);

        $this->insert('category', [
            'name' => 'Люкс',
            'count' => '5',
        ]);

        $this->insert('category', [
            'name' => 'Де-Люк',
            'count' => '6',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
