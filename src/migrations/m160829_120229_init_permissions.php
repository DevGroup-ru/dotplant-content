<?php

use yii\db\Migration;
use app\helpers\PermissionsHelper;

class m160829_120229_init_permissions extends Migration
{
    private static $rules = [
        'ContentManager' => [
            'descr' => 'Content Management Role',
            'permits' => [
                'dotplant-content-view' => 'View Content Entries',
                'dotplant-content-publish' => 'Publish/Unpublish Content Entries'
            ]
        ],
        'ContentAdministrator' => [
            'descr' => 'Content Administration Role',
            'permits' => [
                'dotplant-content-edit' => 'View Content Entries',
                'dotplant-content-delete' => 'Delete/Restore Content Entries'
            ],
            'roles' => [
                'ContentManager'
            ]
        ]
    ];

    public function up()
    {
        PermissionsHelper::createPermissions(self::$rules);
    }

    public function down()
    {
        PermissionsHelper::removePermissions(self::$rules);
    }
}
