<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation of table `space_mention`.
 */
class m180313_144523_create_space_mention_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('space_mention', [
            'id' => $this->primaryKey(),
            'related' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-space-mention-content',
            'space_mention',
            'related',
            'content',
            'id',
            // must be SET NULL and not CASCADE otherwise related Content
            // instance is not delete when space mention is deleted due to
            // cascade.
            'SET NULL'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('space_mention');
    }
}
