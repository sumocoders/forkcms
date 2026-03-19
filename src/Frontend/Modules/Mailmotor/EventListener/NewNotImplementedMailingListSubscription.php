<?php

namespace Frontend\Modules\Mailmotor\EventListener;

use Common\Language;
use Common\Mailer\EmailFactory;
use Frontend\Modules\Mailmotor\Domain\Subscription\Event\NotImplementedSubscribedEvent;
use Symfony\Component\Mailer\MailerInterface;

/**
 * New mailing list subscription
 *
 * This will send a mail to the administrator
 * to let them know that they have to manually subscribe a person.
 * Because the mail engine is "not_implemented".
 */
final readonly class NewNotImplementedMailingListSubscription
{
    public function __construct(
        protected MailerInterface $mailer,
        protected EmailFactory $emailFactory,
    ) {
    }

    public function onNotImplementedSubscribedEvent(NotImplementedSubscribedEvent $event): void
    {
        $subject = sprintf(
            Language::lbl('MailTitleSubscribeSubscriber'),
            $event->getSubscription()->email,
            strtoupper((string) $event->getSubscription()->locale)
        );
        $message = $this->emailFactory->create()
            ->subject($subject)
            ->htmlTemplate('/Core/Layout/Templates/Mails/Notification.html.twig')
            ->context([
                'message' => $subject,
            ]);
        $this->mailer->send($message);
    }
}
