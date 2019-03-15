<?php

use yii\db\Migration;

/**
 * Class m190306_145328_alter_feed_table
 */
class m190306_145328_alter_feed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-feed-post_id-post-id', 'feed', 'post_id', 'post', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-feed-post_id-post-id', 'feed', 'post_id', 'CASCADE', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190306_145328_alter_feed_table cannot be reverted.\n";

        return false;
    }
    */
}
