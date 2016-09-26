<?php

use DotPlant\Content\models\Page;
use DotPlant\Content\models\PageExtended;
use DotPlant\EntityStructure\models\BaseStructure;
use DotPlant\EntityStructure\models\Entity;
use DotPlant\Monster\DataEntity\StaticContentProvider;
use DotPlant\Monster\models\Template;
use DotPlant\Monster\models\TemplateRegion;
use DevGroup\Multilingual\models\Context;
use DevGroup\TagDependencyHelper\NamingHelper;
use yii\caching\TagDependency;
use yii\db\Migration;
use yii\db\Query;

class m160912_115202_dotplant_core_add_demo_page extends Migration
{
    const DEMO_PAGE_SLUG = 'dp3-monster-demo-page';

    private function invalidateTags()
    {
        TagDependency::invalidate(
            Yii::$app->cache,
            [
                NamingHelper::getCommonTag(TemplateRegion::class),
                NamingHelper::getCommonTag(Template::class),
                NamingHelper::getCommonTag(BaseStructure::class),
                NamingHelper::getCommonTag(PageExtended::class),
            ]
        );
    }

    public function up()
    {
        $this->insert(
            Template::tableName(),
            [
                'name' => 'Example page layout',
                'key' => 'example',
                'is_layout' => false,
                'packed_json_providers' => json_encode([
                    [
                        'class' => StaticContentProvider::class,
                        'entities' => [
                            'best' => [
                                't001' => [
                                    'title' => 'BEST company EVER',
                                    'blocks' => [
                                        [
                                            'name' => [
                                                'href' => '#block1',
                                                'anchor' => 'Block #1',
                                            ],
                                            'content' => 'Lorem ipsum UNO block',
                                        ],
                                        [
                                            'name' => [
                                                'href' => '#block2',
                                                'anchor' => 'Second block',
                                            ],
                                            'content' => 'Lorem ipsum DOS blockos',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
            ]
        );
        $templateId = $this->db->lastInsertID;
        $this->insert(
            TemplateRegion::tableName(),
            [
                'template_id' => $templateId,
                'sort_order' => 2,
                'name' => 'Common block',
                'key' => 'best',
                'entity_dependent' => 0,
                'packed_json_content' => json_encode([
                    't001' => [
                        'material' => 'core.frontend-monster.content-blocks.content-block-001',
                    ],
                ]),
            ]
        );
        $this->insert(
            TemplateRegion::tableName(),
            [
                'template_id' => $templateId,
                'sort_order' => 1,
                'name' => 'Page content region',
                'key' => 'content',
                'entity_dependent' => 1,
                'packed_json_content' => '[]',
            ]
        );
        $entityId = Entity::getEntityIdForClass(Page::class);
        $contexts = Context::find()->all();
        foreach ($contexts as $context) {
            $this->insert(
                BaseStructure::tableName(),
                [
                    'context_id' => $context->id,
                    'entity_id' => $entityId,
                ]
            );
            $id = $this->db->lastInsertID;
            foreach ($context->languages as $language) {
                $this->insert(
                    BaseStructure::getTranslationTableName(),
                    [
                        'model_id' => $id,
                        'language_id' => $language->id,
                        'name' => 'name',
                        'slug' => self::DEMO_PAGE_SLUG,
                        'url' => self::DEMO_PAGE_SLUG,
                    ]
                );
                $this->insert(
                    PageExtended::tableName(),
                    [
                        'model_id' => $id,
                        'language_id' => $language->id,
                        'packed_json_content' => '{"content":{"p01":{"material":"core.frontend-monster.content-blocks.content-block-001"},"p02":{"material":"core.frontend-monster.content-blocks.content-block-002"}}}',
                        'packed_json_providers' => '[]',
                    ]
                );
            }
        }
        $this->invalidateTags();
    }

    public function down()
    {
        $ids = (new Query())->select('model_id')
            ->from(BaseStructure::getTranslationTableName())
            ->where(['slug' => self::DEMO_PAGE_SLUG])
            ->column();
        $this->delete(
            BaseStructure::tableName(),
            ['id' => $ids]
        );
        $this->delete(Template::tableName(), ['key' => 'example']);
        $this->invalidateTags();
    }
}
