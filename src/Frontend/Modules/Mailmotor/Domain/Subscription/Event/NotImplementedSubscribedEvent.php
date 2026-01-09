<?php

namespace Frontend\Modules\Mailmotor\Domain\Subscription\Event;

use Frontend\Modules\Mailmotor\Domain\Subscription\Command\Subscription;
use Symfony\Contracts\EventDispatcher\Event;

final class NotImplementedSubscribedEvent extends Event
{
    const EVENT_NAME = 'mailmotor.event.not_implemented.subscribed';

    public function __construct(private Subscription $subscription)
    {
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }
}
