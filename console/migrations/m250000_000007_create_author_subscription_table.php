<?php

use yii\db\Migration;

class m250000_000007_create_author_subscription_table extends Migration
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

        $this->createTable('{{%author_subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-author_subscription-author_id',
            '{{%author_subscription}}',
            'author_id'
        );

        $this->createIndex(
            'idx-author_subscription-phone',
            '{{%author_subscription}}',
            'phone'
        );

        $this->createIndex(
            'ux-author_subscription-author_phone',
            '{{%author_subscription}}',
            ['author_id', 'phone'],
            true
        );

        $this->addForeignKey(
            'fk-author_subscription-author_id',
            '{{%author_subscription}}',
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
        $this->dropForeignKey('fk-author_subscription-author_id', '{{%author_subscription}}');
        $this->dropIndex('ux-author_subscription-author_phone', '{{%author_subscription}}');
        $this->dropIndex('idx-author_subscription-phone', '{{%author_subscription}}');
        $this->dropIndex('idx-author_subscription-author_id', '{{%author_subscription}}');
        $this->dropTable('{{%author_subscription}}');
    }
}

