<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}` which represents
 * the many-to-many relation between books and authors.
 */
class m250000_000003_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            'pk-book_author',
            '{{%book_author}}',
            ['book_id', 'author_id']
        );

        $this->createIndex(
            'idx-book_author-book_id',
            '{{%book_author}}',
            'book_id'
        );

        $this->createIndex(
            'idx-book_author-author_id',
            '{{%book_author}}',
            'author_id'
        );

        $this->addForeignKey(
            'fk-book_author-book_id',
            '{{%book_author}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            '{{%book_author}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-book_author-author_id', '{{%book_author}}');
        $this->dropForeignKey('fk-book_author-book_id', '{{%book_author}}');

        $this->dropIndex('idx-book_author-author_id', '{{%book_author}}');
        $this->dropIndex('idx-book_author-book_id', '{{%book_author}}');

        $this->dropPrimaryKey('pk-book_author', '{{%book_author}}');

        $this->dropTable('{{%book_author}}');
    }
}


