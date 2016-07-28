<?php

use DotPlant\Content\models\Page;
use DotPlant\Content\models\PageTranslation;
use DevGroup\DataStructure\helpers\PropertiesTableGenerator;
use yii\db\Migration;

class m160728_093615_init extends Migration
{
    public function up()
    {
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;
        $this->createTable(
            Page::tableName(),
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->defaultValue(''),
                'slug' => $this->string()->notNull()->defaultValue(''),
                'packed_json_content' => 'LONGTEXT NOT NULL',
                'packed_json_providers' => 'LONGTEXT NOT NULL',
                'template_id' => $this->integer()->notNull()->defaultValue(0),
                'layout_id' => $this->integer()->notNull()->defaultValue(0),
                // base actions info trait fields
                'created_at' => $this->integer()->null(),
                'updated_at' => $this->integer()->null(),
                'created_by' => $this->integer()->null(),
                'updated_by' => $this->integer()->null(),
            ],
            $tableOptions
        );
        $this->createTable(
            PageTranslation::tableName(),
            [
                'model_id' => $this->integer()->notNull(),
                'language_id' => $this->integer()->notNull(),
                'is_published' => $this->boolean()->defaultValue(true),
                // seo trait fields
                'title' => $this->string(255),
                'h1' => $this->string(255),
                'breadcrumbs_label' => $this->string(255),
                'meta_description' => $this->string(255),
                'slug' => $this->string(80)->notNull(),
            ],
            $tableOptions
        );
        $this->addPrimaryKey('page_translation-pk', PageTranslation::tableName(), ['model_id', 'language_id']);
        $this->addForeignKey(
            'fk-page_translation-model_id-page-id',
            PageTranslation::tableName(),
            'model_id',
            Page::tableName(),
            'id',
            'UPDATE',
            'UPDATE'
        );
        PropertiesTableGenerator::getInstance()->generate(Page::class);
    }

    public function down()
    {
        PropertiesTableGenerator::getInstance()->drop(Page::class);
        $this->dropTable(PageTranslation::tableName());
        $this->dropTable(Page::tableName());
    }
}
