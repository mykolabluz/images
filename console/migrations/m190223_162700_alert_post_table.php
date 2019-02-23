<?php

use yii\db\Migration;

class m190223_162700_alert_post_table extends Migration
{
        public function safeUp()
    {
            $this->addColumn('{{%post}}', 'updated_at', $this->integer(11));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'updated_at');
    }

}
