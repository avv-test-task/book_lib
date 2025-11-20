<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m250000_000002_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'year' => $this->smallInteger(),
            'isbn' => $this->string(20),
            'cover_path' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-book-year',
            '{{%book}}',
            'year'
        );

        $this->createIndex(
            'ux-book-isbn',
            '{{%book}}',
            'isbn',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('ux-book-isbn', '{{%book}}');
        $this->dropIndex('idx-book-year', '{{%book}}');
        $this->dropTable('{{%book}}');
    }
}


