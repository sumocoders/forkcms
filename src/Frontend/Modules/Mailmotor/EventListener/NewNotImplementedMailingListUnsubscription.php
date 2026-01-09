<?php

namespace Frontend\Modules\Mailmotor\EventListener;

use Common\Language;
use Common\Mailer\Message;
use Frontend\Modules\Mailmotor\Domain\Subscription\Event\NotImplementedUnsubscribedEvent;
use Swift_Mailer;
use Common\ModulesSettings;

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
        private Swift_Mailer $mailer,
        private ModulesSettings $modulesSettings,
    ) {
    }

    public function onNotImplementedUnsubscribedEvent(NotImplementedUnsubscribedEvent $event): void
    {
        $title = sprintf(
            Language::lbl('MailTitleUnsubscribeSubscriber'),
            $event->getUnsubscription()->email,
            strtoupper((string) $event->getUnsubscription()->locale)
        );

        $to = $this->modulesSettings->get('Core', 'mailer_to');
        $from = $this->modulesSettings->get('Core', 'mailer_from');
        $replyTo = $this->modulesSettings->get('Core', 'mailer_reply_to');

        $message = Message::newInstance($title)
            ->setFrom([$from['email'] => $from['name']])
            ->setTo([$to['email'] => $to['name']])
            ->setReplyTo([$replyTo['email'] => $replyTo['name']])
            ->parseHtml(
                FRONTEND_CORE_PATH . '/Layout/Templates/Mails/Notification.html.twig',
                [
                    'message' => $title,
                ],
                true
            )
        ;

        $this->mailer->send($message);
    }
}
