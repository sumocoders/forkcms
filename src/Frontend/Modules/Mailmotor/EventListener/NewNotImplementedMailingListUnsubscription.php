<?php

namespace Frontend\Modules\Mailmotor\EventListener;

use Common\Language;
use Common\Mailer\EmailFactory;
use Frontend\Modules\Mailmotor\Domain\Subscription\Event\NotImplementedUnsubscribedEvent;
use Symfony\Component\Mailer\MailerInterface;

/**
 * New mailing list unsubscription
 *
 * This will send a mail to the administrator
 * to let them know that they have to manually unsubscribe a person.
 * Because the mail engine is "not_implemented".
 */
final readonly class NewNotImplementedMailingListUnsubscription
{
    public function __construct(
        protected MailerInterface $mailer,
        protected EmailFactory $emailFactory,
    ) {
    }

    public function onNotImplementedUnsubscribedEvent(NotImplementedUnsubscribedEvent $event): void
    {
        $subject = sprintf(
            Language::lbl('MailTitleUnsubscribeSubscriber'),
            $event->getUnsubscription()->email,
            strtoupper((string) $event->getUnsubscription()->locale)
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
