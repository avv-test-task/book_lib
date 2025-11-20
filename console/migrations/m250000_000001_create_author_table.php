<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author}}`.
 */
class m250000_000001_create_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-author-name',
            '{{%author}}',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-author-name', '{{%author}}');
        $this->dropTable('{{%author}}');
    }
}


