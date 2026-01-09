<?php

namespace Frontend\Modules\Mailmotor\EventListener;

use Frontend\Core\Language\Locale;
use Common\ModulesSettings;
use Frontend\Modules\FormBuilder\Event\FormBuilderSubmittedEvent;
use MailMotor\Bundle\MailMotorBundle\Helper\Subscriber;

/**
 * A Formbuilder submitted event subscriber that will subscribe the email when the checkbox is checked
 */
final readonly class FormBuilderSubmittedMailmotorSubscriber
{
    public function __construct(
        private ModulesSettings $modulesSettings,
        private Subscriber $mailmotorSubscriber,
    ) {
    }

    public function onFormSubmitted(FormBuilderSubmittedEvent $event): void
    {
        $listId = $this->findListId($event->getForm()['fields']);
        if ($listId === null) {
            return;
        }

        $emailAddresses = $this->findEmailAddressesToSubscribe($event->getForm()['fields'], $event->getData());
        if (empty($emailAddresses)) {
            return;
        }

        foreach ($emailAddresses as $emailAddress) {
            $this->mailmotorSubscriber->subscribe(
                $emailAddress,
                Locale::frontendLanguage(),
                [],
                [],
                $this->modulesSettings->get('Mailmotor', 'double_opt_in', true),
                $listId
            );
        }
    }

    private function findEmailAddressesToSubscribe(array $formFields, array $formData): array
    {
        $fieldIds = array_keys(
            array_filter(
                $formFields,
                fn(array $formField): bool => $formField['settings']['use_to_subscribe_with_mailmotor'] ?? false
            )
        );

        return array_map(
            fn(array $fieldData) => unserialize($fieldData['value'], ['allowed_classes' => false]),
            array_filter(
                $formData,
                fn($key) => in_array($key, $fieldIds, true),
                ARRAY_FILTER_USE_KEY
            )
        );
    }

    private function findListId(array $formFields): ?string
    {
        foreach ($formFields as $formField) {
            if ($formField['type'] === 'mailmotor') {
                return $formField['settings']['list_id'];
            }
        }

        return null;
    }
}
