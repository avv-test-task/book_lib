<?php

use yii\db\Migration;

class m250000_000008_create_author_subscription_verification_table extends Migration
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

        $this->createTable('{{%author_subscription_verification}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'code' => $this->string(4)->notNull(),
            'expires_at' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-author_subscription_verification-author_phone',
            '{{%author_subscription_verification}}',
            ['author_id', 'phone']
        );

        $this->addForeignKey(
            'fk-author_subscription_verification-author_id',
            '{{%author_subscription_verification}}',
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
        $this->dropForeignKey('fk-author_subscription_verification-author_id', '{{%author_subscription_verification}}');
        $this->dropIndex('idx-author_subscription_verification-author_phone', '{{%author_subscription_verification}}');
        $this->dropTable('{{%author_subscription_verification}}');
    }
}

