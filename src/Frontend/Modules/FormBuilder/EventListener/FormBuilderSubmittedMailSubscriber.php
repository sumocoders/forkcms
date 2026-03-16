<?php

namespace Frontend\Modules\FormBuilder\EventListener;

use Common\Mailer\EmailFactory;
use Frontend\Core\Language\Language;
use Frontend\Modules\FormBuilder\Event\FormBuilderSubmittedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * A Formbuilder submitted event subscriber that will send an email if needed
 */
final class FormBuilderSubmittedMailSubscriber
{
    public function __construct(
        protected MailerInterface $mailer,
        protected EmailFactory $emailFactory,
    ) {
    }

    public function onFormSubmitted(FormBuilderSubmittedEvent $event): void
    {
        $form = $event->getForm();
        $fieldData = $this->getEmailFields($event->getData());

        // need to send mail
        if ($form['method'] === 'database_email' || $form['method'] === 'email') {
            $this->mailer->send($this->getMessage($form, $fieldData, $form['email_subject']));
        }

        // check if we need to send confirmation mails
        foreach ($form['fields'] as $field) {
            if (array_key_exists('send_confirmation_mail_to', $field['settings']) &&
                $field['settings']['send_confirmation_mail_to'] === true
            ) {
                $message = $this->emailFactory->create()
                    ->subject($field['settings']['confirmation_mail_subject'])
                    ->to(new Address($fieldData[$field['id']]['value']))
                    ->htmlTemplate('/Core/Layout/Templates/Mails/Notification.html.twig')
                    ->context([
                        'message' => $field['settings']['confirmation_mail_message'],
                    ]);
                $this->mailer->send($message);
            }
        }
    }

    private function getMessage(
        array $form,
        array $fieldData,
        ?string $subject = null
    ): TemplatedEmail {
        if ($subject === null) {
            $subject = Language::getMessage('FormBuilderSubject');
        }

        $message = $this->emailFactory->create()
            ->subject(sprintf($subject, $form['name']))
            ->htmlTemplate('/FormBuilder/Layout/Templates/Mails/' . $form['email_template'])
            ->context([
                'subject' => $subject,
                'sentOn' => time(),
                'name' => $form['name'],
                'fields' => array_map(
                    function (array $field): array {
                        $field['value'] = html_entity_decode((string) $field['value']);

                        return $field;
                    },
                    $fieldData
                )
            ]);

        // check if we have a replyTo email set
        foreach ($form['fields'] as $field) {
            if (array_key_exists('reply_to', $field['settings']) &&
                $field['settings']['reply_to'] === true
            ) {
                $message->replyTo(new Address($fieldData[$field['id']]['value']));
            }
        }

        return $message;
    }

    /**
     * Converts the data to make sure it is nicely usable in the email
     *
     * @param array $data
     *
     * @return array
     */
    protected function getEmailFields(array $data): array
    {
        return array_map(
            function ($item): array {
                $value = unserialize($item['value'], ['allowed_classes' => false]);

                return [
                    'label' => $item['label'],
                    'value' => (
                    is_array($value)
                        ? implode(',', $value)
                        : nl2br($value)
                    ),
                ];
            },
            $data
        );
    }
}
