<?php

namespace Frontend\Modules\Mailmotor\Domain\Subscription\Event;

use Frontend\Modules\Mailmotor\Domain\Subscription\Command\Unsubscription;
use Symfony\Contracts\EventDispatcher\Event;

final class NotImplementedUnsubscribedEvent extends Event
{
    const EVENT_NAME = 'mailmotor.event.not_implemented.unsubscribed';

    public function __construct(private Unsubscription $unsubscription)
    {
    }

    public function getUnsubscription(): Unsubscription
    {
        return $this->unsubscription;
    }
}
