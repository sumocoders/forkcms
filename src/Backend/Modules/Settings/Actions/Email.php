<?php

namespace Backend\Modules\Settings\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Language\Language as BL;
use function Symfony\Component\String\s;

/**
 * This is the email-action, it will display a form to set email settings
 */
class Email extends BackendBaseActionIndex
{
    /**
     * The form instance
     *
     * @var BackendForm
     */
    private $form;

    public function execute(): void
    {
        parent::execute();
        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    private function loadForm(): void
    {
        $this->form = new BackendForm('settingsEmail');

        // email settings
        $mailerFrom = $this->get('fork.settings')->get('Core', 'mailer_from');
        $this->form->addText('mailer_from_name', $mailerFrom['name'] ?? '');
        $this->form
            ->addText('mailer_from_email', $mailerFrom['email'] ?? '')
            ->setAttribute('type', 'email')
        ;
        $mailerTo = $this->get('fork.settings')->get('Core', 'mailer_to');
        $this->form->addText('mailer_to_name', $mailerTo['name'] ?? '');
        $this->form
            ->addText('mailer_to_email', $mailerTo['email'] ?? '')
            ->setAttribute('type', 'email')
        ;
        $mailerReplyTo = $this->get('fork.settings')->get('Core', 'mailer_reply_to');
        $this->form->addText('mailer_reply_to_name', $mailerReplyTo['name'] ?? '');
        $this->form
            ->addText('mailer_reply_to_email', $mailerReplyTo['email'] ?? '')
            ->setAttribute('type', 'email')
        ;
    }

    protected function parse(): void
    {
        parent::parse();

        // parse the form
        $this->form->parse($this->template);
    }

    private function validateForm(): void
    {
        // is the form submitted?
        if ($this->form->isSubmitted()) {
            // validate required fields
            $this->form->getField('mailer_from_name')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('mailer_from_email')->isEmail(BL::err('EmailIsInvalid'));
            $this->form->getField('mailer_to_name')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('mailer_to_email')->isEmail(BL::err('EmailIsInvalid'));
            $this->form->getField('mailer_reply_to_name')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('mailer_reply_to_email')->isEmail(BL::err('EmailIsInvalid'));

            // no errors ?
            if ($this->form->isCorrect()) {
                // e-mail settings
                $this->get('fork.settings')->set(
                    'Core',
                    'mailer_from',
                    [
                        'name' => $this->form->getField('mailer_from_name')->getValue(),
                        'email' => $this->form->getField('mailer_from_email')->getValue(),
                    ]
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'mailer_to',
                    [
                        'name' => $this->form->getField('mailer_to_name')->getValue(),
                        'email' => $this->form->getField('mailer_to_email')->getValue(),
                    ]
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'mailer_reply_to',
                    [
                        'name' => $this->form->getField('mailer_reply_to_name')->getValue(),
                        'email' => $this->form->getField('mailer_reply_to_email')->getValue(),
                    ]
                );

                // assign report
                $this->template->assign('report', true);
                $this->template->assign('reportMessage', BL::msg('Saved'));
            }
        }
    }
}
