<?php

use DotPlant\Content\models\Page;
use DevGroup\DataStructure\helpers\PropertiesTableGenerator;
use DotPlant\EntityStructure\models\Entity;
use \yii\helpers\Console;
use yii\db\Migration;

class m160728_093615_init extends Migration
{
    public function up()
    {
        if (null === $this->db->getTableSchema(Entity::tableName())) {
            Console::stderr(
                "Please, first install if not and activate 'DotPlant Entity Structure' extension!" . PHP_EOL
            );
            return false;
        }

        $this->insert(
            Entity::tableName(),
            [
                'name' => 'Page',
                'class_name' => Page::class
            ]
        );
        PropertiesTableGenerator::getInstance()->generate(Page::class);
        $auth = Yii::$app->authManager;
        $permission = $auth->createPermission('content-manage');
        $permission->description = 'Content manage permission';
        $auth->add($permission);
    }

    public function down()
    {
        $this->delete(
            Entity::tableName(),
            [
                'name' => 'Page',
                'class_name' => Page::class
            ]
        );
        PropertiesTableGenerator::getInstance()->drop(Page::class);
        $auth = Yii::$app->authManager;
        $permission = $auth->getPermission('content-manage');
        if (null !== $permission) {
            $auth->remove($permission);
        }
    }
}
