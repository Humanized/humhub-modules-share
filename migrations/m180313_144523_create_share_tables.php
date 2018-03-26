<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation of table `share`.
 */
class m180313_144523_create_share_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('share_entry', [
            'id' => $this->primaryKey(),
            'entry' => $this->integer(),
            'message' => $this->string(256),
        ]);

        $this->addForeignKey(
            'fk-share-entry-content',
            'share_entry',
            'entry',
            'content',
            'id',
            // must be SET NULL and not CASCADE otherwise entry
            // instance is not delete when share is deleted due to
            // cascade.
            'SET NULL'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('share_entry');
    }
}
