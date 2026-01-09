<?php

namespace Frontend\Modules\Mailmotor\Domain\Subscription\Command;

use Common\ModulesSettings;
use Frontend\Core\Language\Locale;
use MailMotor\Bundle\MailMotorBundle\Helper\Subscriber;

final class UnsubscriptionHandler
{
    public function __construct(
        private readonly Subscriber $subscriber,
        private readonly ModulesSettings $modulesSettings,
    ) {
    }

    public function handle(Unsubscription $unsubscription): void
    {
        // Unsubscribing the user, will dispatch an event
        $this->subscriber->unsubscribe(
            $unsubscription->email,
            $this->modulesSettings->get('Mailmotor', 'list_id_' . Locale::frontendLanguage())
        );
    }
}
