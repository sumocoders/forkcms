<?php

namespace Common\Mailer;

use Common\ModulesSettings;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class EmailFactory
{
    public function __construct(
        private readonly ModulesSettings $modulesSettings,
    ) {
    }

    public function create(): TemplatedEmail
    {
        return $this->createDefaultEmailInstance();
    }

    private function createDefaultEmailInstance(): TemplatedEmail
    {
        $email = new TemplatedEmail();

        // set some default based on the settings
        $defaultFrom = $this->modulesSettings->get('Core', 'mailer_from');
        if (!empty($defaultFrom)) {
            $email->from(new Address($defaultFrom['email'], $defaultFrom['name'] ?? null));
        }
        $defaultTo = $this->modulesSettings->get('Core', 'mailer_to');
        if (!empty($defaultTo)) {
            $email->to(new Address($defaultTo['email'], $defaultTo['name'] ?? null));
        }
        $defaultReplyTo = $this->modulesSettings->get('Core', 'mailer_reply_to');
        if (!empty($defaultReplyTo)) {
            $email->replyTo(new Address($defaultReplyTo['email'], $defaultReplyTo['name'] ?? null));
        }

        return $email;
    }
}
