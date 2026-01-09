<?php

namespace Frontend\Modules\Mailmotor\Domain\Subscription\Command;

use Frontend\Core\Language\Locale;
use Symfony\Component\Validator\Constraints as Assert;
use Frontend\Modules\Mailmotor\Domain\Subscription\Validator\Constraints as MailingListAssert;

final class Unsubscription
{
    public function __construct(
        public Locale $locale,
        /**
         * @Assert\NotBlank(message="err.FieldIsRequired")
         * @Assert\Email(message="err.EmailIsInvalid")
         * @MailingListAssert\EmailUnsubscription
         */
        public ?string $email = null
    ) {
    }
}
