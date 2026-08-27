<?php

namespace Console\Mailmotor;

use Backend\Modules\Pages\Engine\Model as BackendPagesModel;
use ForkCMS\App\BaseModel;
use Common\ModulesSettings;
use SpoonDatabase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Removes every trace of the Mailmotor module from the database.
 *
 * The Mailmotor module and the mailmotor/* composer packages were removed from Fork CMS.
 * The module never created tables of its own, so all of its data lives in the core tables.
 * Those rows have to be cleaned up or the frontend will try to render pages that point at
 * a module which no longer exists on disk.
 */
#[AsCommand(
    name: 'forkcms:mailmotor:remove',
    description: 'Remove all leftover Mailmotor data from the database'
)]
final class RemoveMailmotorCommand extends Command
{
    private const MODULE = 'Mailmotor';

    /**
     * Backend/Core locale labels that were only ever used by the Mailmotor module.
     */
    private const CORE_LOCALE_LABELS = [
        'Mailmotor',
        'MailmotorClicks',
        'MailmotorGroups',
        'MailmotorLatestMailing',
        'MailmotorOpened',
        'MailmotorSendDate',
        'MailmotorSent',
        'MailmotorStatistics',
        'MailmotorSubscriptions',
        'MailmotorUnsubscriptions',
    ];

    /**
     * Frontend/Core locale items that were only ever used by the Mailmotor templates.
     */
    private const FRONTEND_LOCALE_NAMES = [
        'AlreadySubscribed',
        'AlreadyUnsubscribed',
        'EmailAlreadySubscribedInMailingList',
        'EmailIsAlreadyUnsubscribedInMailingList',
        'MailTitleSubscribeSubscriber',
        'MailTitleUnsubscribeSubscriber',
        'SubscribeFailed',
        'SubscribeSuccess',
        'SubscribeSuccessForDoubleOptIn',
        'SubscribeToNewsletter',
        'UnsubscribeFailed',
        'UnsubscribeFromNewsletter',
        'UnsubscribeSuccess',
    ];

    private SymfonyStyle $formatter;

    private bool $dryRun = false;

    public function __construct(
        private readonly SpoonDatabase $database,
        private readonly ModulesSettings $modulesSettings,
        private readonly KernelInterface $kernel
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Only report what would be removed')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Do not ask for confirmation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->formatter = new SymfonyStyle($input, $output);
        $this->dryRun = (bool) $input->getOption('dry-run');

        // the page removal below goes through the backend model, which expects a container
        BaseModel::setContainer($this->kernel->getContainer());

        $this->formatter->title('Removing Mailmotor');
        $this->reportCounts();

        if ($this->dryRun) {
            $this->formatter->note('Dry run, nothing was changed.');

            return Command::SUCCESS;
        }

        if (!$input->getOption('force') && !$this->formatter->confirm('Remove all of this?', false)) {
            $this->formatter->warning('Aborted, nothing was changed.');

            return Command::SUCCESS;
        }

        $this->removePages();
        $this->removeExtras();
        $this->removeFormBuilderFields();
        $this->removeSettings();
        $this->removeNavigation();
        $this->removeRights();
        $this->removeDashboardSequences();
        $this->removeLocale();
        $this->removeModule();

        $this->formatter->success('Mailmotor has been removed.');
        $this->formatter->note('Run "php bin/console forkcms:cache:clear" to refresh the caches.');

        return Command::SUCCESS;
    }

    private function reportCounts(): void
    {
        $rows = [
            ['pages using a Mailmotor block', count($this->getPagesWithMailmotorBlock())],
            ['modules_extras', $this->countRows('modules_extras', 'module = ?', [self::MODULE])],
            ['forms_fields (type mailmotor)', count($this->getMailmotorFormFieldIds())],
            ['modules_settings', count($this->modulesSettings->getForModule(self::MODULE))],
            ['backend_navigation', $this->countRows('backend_navigation', 'url LIKE ?', ['mailmotor/%'])],
            ['groups_rights_actions', $this->countRows('groups_rights_actions', 'module = ?', [self::MODULE])],
            ['groups_rights_modules', $this->countRows('groups_rights_modules', 'module = ?', [self::MODULE])],
            ['locale', $this->countRows('locale', 'module = ?', [self::MODULE])],
            ['modules', $this->countRows('modules', 'name = ?', [self::MODULE])],
        ];

        $this->formatter->table(['what', 'rows'], $rows);
    }

    private function countRows(string $table, string $where, array $parameters): int
    {
        return (int) $this->database->getVar(
            'SELECT COUNT(*) FROM ' . $table . ' WHERE ' . $where,
            $parameters
        );
    }

    private function getExtraIds(): array
    {
        return array_map(
            'intval',
            (array) $this->database->getColumn(
                'SELECT id FROM modules_extras WHERE module = ?',
                [self::MODULE]
            )
        );
    }

    /**
     * @return array[] every row has an "id" and a "language"
     */
    private function getPagesWithMailmotorBlock(): array
    {
        $extraIds = $this->getExtraIds();
        if (empty($extraIds)) {
            return [];
        }

        return (array) $this->database->getRecords(
            'SELECT DISTINCT p.id, p.language
             FROM pages AS p
             INNER JOIN pages_blocks AS b ON b.revision_id = p.revision_id
             WHERE b.extra_id IN (' . implode(',', $extraIds) . ')'
        );
    }

    private function removePages(): void
    {
        $pages = $this->getPagesWithMailmotorBlock();
        $parentIds = [];

        foreach ($pages as $page) {
            $parentId = (int) $this->database->getVar(
                'SELECT parent_id FROM pages WHERE id = ? AND language = ? LIMIT 1',
                [$page['id'], $page['language']]
            );

            if (BackendPagesModel::delete((int) $page['id'], $page['language'])) {
                $parentIds[$parentId . '|' . $page['language']] = [$parentId, $page['language']];
            }
        }

        $this->formatter->text(sprintf('Removed %d page(s).', count($pages)));

        $this->removeChildlessParents($parentIds);

        // blocks that were placed on a page by hand rather than by the installer
        foreach ($this->getExtraIds() as $extraId) {
            $this->database->delete('pages_blocks', 'extra_id = ?', [$extraId]);
        }
    }

    /**
     * The installer created a "Newsletters" parent page purely to hold the subscribe pages.
     * Remove it when nothing is left underneath it.
     */
    private function removeChildlessParents(array $parents): void
    {
        foreach ($parents as [$parentId, $language]) {
            if ($parentId <= 0) {
                continue;
            }

            $children = (int) $this->database->getVar(
                'SELECT COUNT(*) FROM pages WHERE parent_id = ? AND language = ? AND status = ?',
                [$parentId, $language, 'active']
            );

            if ($children > 0) {
                continue;
            }

            if (BackendPagesModel::delete($parentId, $language)) {
                $this->formatter->text(sprintf('Removed the now empty parent page %d (%s).', $parentId, $language));
            }
        }
    }

    private function removeExtras(): void
    {
        $this->database->delete('modules_extras', 'module = ?', [self::MODULE]);
    }

    private function getMailmotorFormFieldIds(): array
    {
        return array_map(
            'intval',
            (array) $this->database->getColumn('SELECT id FROM forms_fields WHERE type = ?', ['mailmotor'])
        );
    }

    /**
     * FormBuilder had a "mailmotor" field type plus a "subscribe with this address" flag on
     * text fields. Both are gone from the code, so the stored data has to follow.
     */
    private function removeFormBuilderFields(): void
    {
        $fieldIds = $this->getMailmotorFormFieldIds();

        if (!empty($fieldIds)) {
            $placeholders = implode(',', $fieldIds);
            $this->database->delete('forms_fields_validation', 'field_id IN (' . $placeholders . ')');
            $this->database->delete('forms_fields', 'id IN (' . $placeholders . ')');
        }

        $this->formatter->text(sprintf('Removed %d mailmotor form field(s).', count($fieldIds)));
        $this->formatter->text(
            sprintf('Cleaned the subscribe flag from %d text field(s).', $this->cleanTextFieldSettings())
        );
    }

    private function cleanTextFieldSettings(): int
    {
        $fields = (array) $this->database->getRecords(
            'SELECT id, settings FROM forms_fields WHERE settings LIKE ?',
            ['%use_to_subscribe_with_mailmotor%']
        );

        $cleaned = 0;
        foreach ($fields as $field) {
            $settings = @unserialize((string) $field['settings'], ['allowed_classes' => false]);
            if (!is_array($settings) || !array_key_exists('use_to_subscribe_with_mailmotor', $settings)) {
                continue;
            }

            unset($settings['use_to_subscribe_with_mailmotor']);
            $this->database->update('forms_fields', ['settings' => serialize($settings)], 'id = ?', [$field['id']]);
            ++$cleaned;
        }

        return $cleaned;
    }

    /**
     * Deleted through the settings service so the settings cache is invalidated too. The
     * module stores per-language "list_id_<language>" keys, so the key list is read back
     * rather than hardcoded.
     */
    private function removeSettings(): void
    {
        foreach (array_keys($this->modulesSettings->getForModule(self::MODULE)) as $key) {
            $this->modulesSettings->delete(self::MODULE, $key);
        }
    }

    private function removeNavigation(): void
    {
        $parentIds = array_map(
            'intval',
            (array) $this->database->getColumn(
                'SELECT parent_id FROM backend_navigation WHERE url LIKE ?',
                ['mailmotor/%']
            )
        );

        $this->database->delete('backend_navigation', 'url LIKE ?', ['mailmotor/%']);

        foreach (array_unique($parentIds) as $parentId) {
            $children = $this->countRows('backend_navigation', 'parent_id = ?', [$parentId]);
            if ($children === 0) {
                $this->database->delete('backend_navigation', 'id = ?', [$parentId]);
            }
        }
    }

    private function removeRights(): void
    {
        $this->database->delete('groups_rights_actions', 'module = ?', [self::MODULE]);
        $this->database->delete('groups_rights_modules', 'module = ?', [self::MODULE]);
    }

    /**
     * The dashboard sequence is a serialized array, so it has to be rewritten through
     * unserialize/serialize rather than with a string replace.
     */
    private function removeDashboardSequences(): void
    {
        $rows = (array) $this->database->getRecords(
            'SELECT group_id, value FROM groups_settings WHERE name = ?',
            ['dashboard_sequence']
        );

        foreach ($rows as $row) {
            $sequence = @unserialize((string) $row['value'], ['allowed_classes' => false]);
            if (!is_array($sequence) || !array_key_exists(self::MODULE, $sequence)) {
                continue;
            }

            unset($sequence[self::MODULE]);
            $this->database->update(
                'groups_settings',
                ['value' => serialize($sequence)],
                'group_id = ? AND name = ?',
                [$row['group_id'], 'dashboard_sequence']
            );
        }
    }

    private function removeLocale(): void
    {
        $this->database->delete('locale', 'module = ?', [self::MODULE]);

        $this->database->delete(
            'locale',
            'module = ? AND application = ? AND type = ? AND name IN (' .
            implode(',', array_fill(0, count(self::CORE_LOCALE_LABELS), '?')) . ')',
            array_merge(['Core', 'Backend', 'lbl'], self::CORE_LOCALE_LABELS)
        );

        $this->database->delete(
            'locale',
            'module = ? AND application = ? AND name IN (' .
            implode(',', array_fill(0, count(self::FRONTEND_LOCALE_NAMES), '?')) . ')',
            array_merge(['Core', 'Frontend'], self::FRONTEND_LOCALE_NAMES)
        );
    }

    private function removeModule(): void
    {
        $this->database->delete('modules', 'name = ?', [self::MODULE]);
    }
}
