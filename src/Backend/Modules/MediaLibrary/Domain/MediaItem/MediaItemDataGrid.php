<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem;

use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Engine\DataGridFunctions as BackendDataGridFunctions;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Language;
use Backend\Modules\MediaLibrary\Actions\MediaItemMassAction;
use SpoonFormDropdown;
use function Symfony\Component\String\s;

/**
 * @TODO replace with a doctrine implementation of the data grid
 */
class MediaItemDataGrid extends DataGridDatabase
{
    public function __construct(Type $type, ?int $folderId = null, ?string $searchQuery = null)
    {
        parent::__construct(
            'SELECT i.id, i.storage_type, i.type, i.url, i.title, i.sharding_folder_name,
                COUNT(gi.mediaItemId) as num_connected, i.mime, UNIX_TIMESTAMP(i.created_on) AS createdOn
             FROM media_item AS i
             LEFT OUTER JOIN media_group_media_item as gi ON gi.mediaItemId = i.id
             WHERE i.type = ?' . $this->getWhere($folderId, $searchQuery) . ' GROUP BY i.id',
            $this->getParameters($type, $folderId, $searchQuery)
        );

        // filter on folder?
        if ($folderId !== null) {
            $this->setURL('&folder=' . $folderId, true);
        }

        if ($searchQuery) {
            $this->setURL('&query=' . urlencode($searchQuery), true);
        }

        $this->setExtras($type);
        $this->addMassActions($type);
    }

    private function addMassActions(Type $type): void
    {
        $this->setMassActionCheckboxes('check', '[id]');
        $this->setMassAction($this->getMassActionDropdown($type));
    }

    private function getColumnHeaderLabels(Type $type): array
    {
        if ($type->isMovie()) {
            return [
                'storage_type' => s(Language::lbl('MediaStorageType'))->title()->toString(),
                'url' => s(Language::lbl('MediaMovieId'))->title()->toString(),
                'title' => s(Language::lbl('MediaMovieTitle'))->title()->toString(),
            ];
        }

        return [
            'type' => '',
            'url' => s(Language::lbl('Image'))->title()->toString(),
        ];
    }

    private function getColumnsThatNeedToBeHidden(Type $type): array
    {
        if ($type->isImage()) {
            return ['storage_type', 'sharding_folder_name', 'type', 'mime'];
        }

        if ($type->isMovie()) {
            return ['sharding_folder_name', 'type', 'mime'];
        }

        return ['storage_type', 'sharding_folder_name', 'type', 'mime', 'url'];
    }

    public static function getDataGrid(Type $type, ?int $folderId = null, ?string $searchQuery = null): DataGridDatabase
    {
        return new self($type, $folderId, $searchQuery);
    }

    public static function getHtml(Type $type, ?int $folderId = null): string
    {
        return (string) new self($type, $folderId)->getContent();
    }

    private function getMassActionDropdown(Type $type): SpoonFormDropdown
    {
        $ddmMediaItemMassAction = new SpoonFormDropdown(
            'action',
            [
                MediaItemMassAction::MOVE => Language::lbl('Move'),
                MediaItemMassAction::DELETE => Language::lbl('Delete')
            ],
            MediaItemMassAction::MOVE,
            false,
            'form-control',
            'form-control danger'
        );
        $ddmMediaItemMassAction->setAttribute('id', 'mass-action-' . (string) $type);
        $ddmMediaItemMassAction->setOptionAttributes(MediaItemMassAction::MOVE, ['data-target' => '#confirmMassActionMediaItemMove']);
        $ddmMediaItemMassAction->setOptionAttributes(MediaItemMassAction::DELETE, ['data-target' => '#confirmMassActionMediaItemDelete']);

        return $ddmMediaItemMassAction;
    }

    private function getParameters(Type $type, ?int $folderId = null, ?string $searchQuery = null): array
    {
        $parameters = [(string) $type];

        if ($folderId !== null) {
            $parameters[] = $folderId;
        }

        if ($searchQuery) {
            $parameters[] = '%' . $searchQuery .'%';
        }

        return $parameters;
    }

    private function getWhere(?int $folderId = null, ?string $searchQuery = null): string
    {
        $query = ($folderId !== null) ? ' AND i.mediaFolderId = ?' : '';

        if ($searchQuery) {
            $query .= ' AND i.title LIKE ?';
        }

        return $query;
    }

    private function setExtras(Type $type, ?int $folderId = null): void
    {
        $editActionUrl = Model::createUrlForAction('MediaItemEdit');
        $this->setHeaderLabels($this->getColumnHeaderLabels($type));
        $this->setActiveTab('tab' . ucfirst((string) $type));
        $this->setColumnsHidden($this->getColumnsThatNeedToBeHidden($type));
        $this->setSortingColumns(
            [
                'createdOn',
                'url',
                'title',
                'num_connected',
                'mime',
            ],
            'title'
        );
        $this->setSortParameter('asc');
        $this->setColumnURL(
            'title',
            $editActionUrl
            . '&id=[id]'
            . ($folderId ? '&folder=' . $folderId : '')
        );

        if ($type->isMovie()) {
            $this->setColumnURL(
                'url',
                $editActionUrl
                . '&id=[id]'
                . ($folderId ? '&folder=' . $folderId : '')
            );
        }

        $this->setColumnURL(
            'num_connected',
            $editActionUrl
            . '&id=[id]'
            . ($folderId ? '&folder=' . $folderId : '')
        );

        // If we have an image, show the image
        if ($type->isImage()) {
            // Add image url
            $this->setColumnFunction(
                [BackendDataGridFunctions::class, 'showImage'],
                [
                    Model::get('media_library.storage.local')->getWebDir() . '/[sharding_folder_name]',
                    '[url]',
                    '[url]',
                    Model::createUrlForAction('MediaItemEdit') . '&id=[id]' . '&folder=' . $folderId,
                    null,
                    null,
                    'media_library_backend_thumbnail',
                ],
                'url',
                true
            );
        } else {
            $this->setColumnFunction('htmlspecialchars', ['[url]'], 'url', false);
        }

        // set column functions
        $this->setColumnFunction(
            [new BackendDataGridFunctions(), 'getLongDate'],
            ['[createdOn]'],
            'createdOn',
            true
        );

        // add edit column
        $this->addColumn(
            'edit',
            null,
            Language::lbl('Edit'),
            $editActionUrl . '&id=[id]' . '&folder=' . $folderId,
            Language::lbl('Edit')
        );

        // our JS needs to know an id, so we can highlight it
        $this->setRowAttributes(['id' => 'row-[id]']);
        $this->setColumnFunction('htmlspecialchars', ['[title]'], 'title', false);
    }
}
