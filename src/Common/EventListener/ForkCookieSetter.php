<?php

namespace Common\EventListener;

use Common\Core\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ForkCookieSetter
{
    public function __construct(private readonly Cookie $cookie)
    {
    }

    /**
     * Add the fork cookies to the response
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $this->cookie->attachToResponse($event->getResponse());
    }
}
