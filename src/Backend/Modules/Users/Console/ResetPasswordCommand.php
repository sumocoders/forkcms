<?php

namespace Backend\Modules\Users\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Backend\Modules\Users\Engine\Model as BackendUsersModel;
use Backend\Core\Engine\User as BackendUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'forkcms:users:reset-password', description: 'Reset a users password')]
class ResetPasswordCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, '(Optional) The users email-address')
            ->addArgument('password', InputArgument::OPTIONAL, '(Optional) The desired new password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $questionEmailAddress = new Question('Please provide the users email-address: ');
        $questionEmailAddressAnswer = $input->getArgument('email');

        $questionEmailAddressAnswer ??= $helper->ask($input, $output, $questionEmailAddress);

        $subjectUserId = BackendUsersModel::getIdByEmail(
            $questionEmailAddressAnswer
        );

        if ($subjectUserId === false) {
            $output->writeln(
                sprintf('<error>User not found with email-address "%s".</error>', $questionEmailAddressAnswer)
            );

            return Command::FAILURE;
        }

        $questionPassword = new Question('Please provide the users new desired password: ');
        $questionPassword->setHidden(true);
        $questionPasswordAnswer = $input->getArgument('password');

        $questionPasswordAnswer ??= $helper->ask($input, $output, $questionPassword);

        BackendUsersModel::updatePassword(
            new BackendUser($subjectUserId, $questionEmailAddressAnswer),
            $questionPasswordAnswer
        );

        $output->writeln('Password has been updated');

        return Command::SUCCESS;
    }
}
