<?php

namespace Backend\Modules\Mailmotor\Domain\Settings\Event;

use Backend\Modules\Mailmotor\Domain\Settings\Command\SaveSettings;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Mailmotor settings saved Event
 */
final class SettingsSavedEvent extends Event
{
    /**
     * @var string The name the listener needs to listen to to catch this event.
     */
    const string EVENT_NAME = 'mailmotor.event.settings_saved';

    public function __construct(protected SaveSettings $settings)
    {
    }

    public function getSettings(): SaveSettings
    {
        return $this->settings;
    }
}
