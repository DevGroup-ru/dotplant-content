<?php

namespace DotPlant\Content\controllers;

use DevGroup\Frontend\controllers\FrontendController;
use DevGroup\Frontend\Universal\Core\FillEntities;
use DevGroup\Frontend\Universal\SuperAction;
use DotPlant\Content\models\Page;
use DotPlant\Monster\Universal\MainEntity;

class UniversalPageController extends FrontendController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => SuperAction::class,
                'actions' => [
                    [
                        'class' => FillEntities::class,
                        'entitiesMapping' => [
                            Page::class => 'page',
                        ],
                    ],
                    [
                        'class' => MainEntity::class,
                        'mainEntityKey' => 'page',
                        'defaultTemplateKey' => 'example',
                    ],
                ],
            ],
        ];
    }
}
