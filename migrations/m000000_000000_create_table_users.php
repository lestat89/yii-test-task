<?php

use yii\db\Migration;

/**
 * Class m000000_000000_create_table_users
 */
class m000000_000000_create_table_users extends Migration
{
    private const TABLE_NAME = 'users';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $userSchema = [
            'id'                => $this->primaryKey(),
            'email'             => $this->string(64)->notNull(),
            'auth_key'          => $this->string(32)->notNull(),
            'password'          => $this->string(64)->notNull(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
            'last_name'         => $this->string(32)->notNull(),
            'first_name'        => $this->string(32)->notNull(),
            'middle_name'       => $this->string(32)->null(),
            'phone'             => $this->string(32)->null(),
            'document'          => $this->text()->notNull(),
        ];

        $tableName = $this->db->tablePrefix . self::TABLE_NAME;
        if ($this->db->getTableSchema($tableName, true) === null) {
            $this->createTable('users', $userSchema);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
