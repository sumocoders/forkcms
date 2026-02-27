<?php

namespace Frontend\Modules\Faq\Widgets;

use Common\Mailer\EmailFactory;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Form as FrontendForm;
use Frontend\Core\Language\Language as FL;
use Symfony\Component\Mailer\MailerInterface;

/**
 * This is a widget with the form to ask a question
 */
class AskOwnQuestion extends FrontendBaseWidget
{
    /**
     * Form instance
     *
     * @var FrontendForm
     */
    private $form;

    /**
     * The form status
     *
     * @var string
     */
    private $status;

    public function execute(): void
    {
        parent::execute();

        $this->loadTemplate();

        if (!$this->get('fork.settings')->get($this->getModule(), 'allow_own_question', false)) {
            return;
        }

        $this->buildForm();
        $this->handleForm();
        $this->parse();
    }

    private function buildForm(): void
    {
        $this->form = new FrontendForm('own_question', '#' . FL::getAction('OwnQuestion'));
        $this->form->addText('name')->setAttributes(['required' => null]);
        $this->form->addText('email')->setAttributes(['required' => null, 'type' => 'email']);
        $this->form->addTextarea('message')->setAttributes(['required' => null]);
    }

    private function parse(): void
    {
        // parse an option so the stuff can be shown
        $this->template->assign('widgetFaqOwnQuestion', true);

        // parse the form or a status
        if (empty($this->status)) {
            $this->form->parse($this->template);

            return;
        }

        $this->template->assign($this->status, true);
    }

    private function validateForm(): bool
    {
        $this->form->cleanupFields();

        $this->form->getField('name')->isFilled(FL::err('NameIsRequired'));
        $this->form->getField('email')->isEmail(FL::err('EmailIsInvalid'));
        $this->form->getField('message')->isFilled(FL::err('QuestionIsRequired'));

        return $this->form->isCorrect();
    }

    private function getSubmittedQuestion(): array
    {
        return [
            'sentOn' => time(),
            'name' => $this->form->getField('name')->getValue(),
            'emailaddress' => $this->form->getField('email')->getValue(),
            'message' => $this->form->getField('message')->getValue(),
        ];
    }

    private function handleForm(): void
    {
        if (!$this->form->isSubmitted() || !$this->validateForm()) {
            return;
        }

        $question = $this->getSubmittedQuestion();

        $this->sendNewQuestionNotification($question);
        $this->status = 'success';
    }

    private function sendNewQuestionNotification(array $question): void
    {
        /** @var EmailFactory $emailFactory */
        $emailFactory = $this->get(EmailFactory::class);
        /** @var MailerInterface $mailer */
        $mailer = $this->get(MailerInterface::class);
        $message = $emailFactory->create()
            ->subject(sprintf(FL::getMessage('FaqOwnQuestionSubject'), $question['name']))
            ->htmlTemplate('/Faq/Layout/Templates/Mails/OwnQuestion.html.twig')
            ->context($question);
        $mailer->send($message);
    }
}
