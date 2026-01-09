<?php

namespace Backend\Modules\Location\DataFixtures;

use SpoonDatabase;

final class LoadLocationSettings
{
    public const string SETTING_NAME_1 = 'foo';
    public const string SETTING_VALUE_1 = 'bar';
    public const string SETTING_NAME_2 = 'ping';
    public const string SETTING_VALUE_2 = 'pong';

    public function load(SpoonDatabase $database): void
    {
        $database->insert(
            'location_settings',
            [
                [
                    'map_id' => LoadLocation::getLocationId(),
                    'name' => self::SETTING_NAME_1,
                    'value' => serialize(self::SETTING_VALUE_1),
                ],
                [
                    'map_id' => LoadLocation::getLocationId(),
                    'name' => self::SETTING_NAME_2,
                    'value' => serialize(self::SETTING_VALUE_2),
                ],
            ]
        );
    }
}
