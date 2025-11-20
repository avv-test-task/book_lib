<?php

use Yii;
use yii\db\Migration;
use yii\db\Query;

class m250000_000004_add_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (!defined('YII_ENV') || YII_ENV !== 'dev') {
            return;
        }

        $exists = (new Query())
            ->from('{{%user}}')
            ->where(['username' => 'admin'])
            ->exists($this->db);

        if ($exists) {
            return;
        }

        $time = time();

        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'password_reset_token' => null,
            'email' => 'admin@example.com',
            'status' => 10,
            'created_at' => $time,
            'updated_at' => $time,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (!defined('YII_ENV') || YII_ENV !== 'dev') {
            return;
        }

        $this->delete('{{%user}}', ['username' => 'admin']);
    }
}


