<?php

use DotPlant\Content\models\Page;
use DotPlant\EntityStructure\models\Entity;
use DotPlant\EntityStructure\models\StructureTranslation;
use yii\db\Migration;

class m160904_180932_extend_definition extends Migration
{
    public function up()
    {
        $this->update(
            Entity::tableName(),
            [
                'route' => '/content/universal-page',
            ],
            [
                'class_name' => Page::class,
            ]
        );

        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable(
            '{{%dotplant_page_ext}}',
            [
                'model_id' => $this->integer(),
                'language_id' => $this->integer(),
                'packed_json_content' => $this->db->driverName === 'mysql'
                    ? 'LONGTEXT NOT NULL'
                    : $this->text()->notNull(),
                'packed_json_providers' => $this->db->driverName === 'mysql'
                    ? 'LONGTEXT NOT NULL'
                    : $this->text()->notNull(),
                'template_id' => $this->integer()->notNull()->defaultValue(0),
                'layout_id' => $this->integer()->notNull()->defaultValue(0),
            ],
            $tableOptions
        );
        $this->addForeignKey(
            'fkPageExt',
            '{{%dotplant_page_ext}}',
            ['model_id', 'language_id'],
            StructureTranslation::tableName(),
            ['model_id', 'language_id'],
            'CASCADE'
        );
    }

    public function down()
    {
        $this->update(
            Entity::tableName(),
            [
                'route' => '',
            ],
            [
                'class_name' => Page::class,
            ]
        );
        $this->dropTable('{{%dotplant_page_ext}}');

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
