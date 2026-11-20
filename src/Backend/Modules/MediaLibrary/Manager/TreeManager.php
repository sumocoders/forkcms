<?php

namespace Backend\Modules\MediaLibrary\Manager;

use Backend\Modules\MediaLibrary\Builder\MediaFolder\MediaFolderCache;
use Backend\Core\Language\Language;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\MediaLibrary\Builder\MediaFolder\MediaFolderCacheItem;
use function Symfony\Component\String\s;

/**
 * In this file we store all generic functions that we will be using in the MediaLibrary module
 */
final class TreeManager
{
    public function __construct(protected MediaFolderCache $mediaFolderCache, private readonly string $itemAction)
    {
    }

    public function getHTML(): string
    {
        $navigationItems = $this->mediaFolderCache->get();

        $html = '<h4>' . s(Language::lbl('Folders'))->title() . '</h4>' . "\n";
        $html .= '<div class="clearfix">' . "\n";
        $html .= $this->buildNavigationTree($navigationItems);
        $html .= '</div>' . "\n";

        return $html;
    }

    /**
     * jstree 3 only finds child lists that are direct children of their list item
     */
    private function buildNavigationTree(array $navigationItems): string
    {
        // start
        $html = '  <ul>' . "\n";

        /** @var MediaFolderCacheItem $cacheItem */
        foreach ($navigationItems as $cacheItem) {
            $html .= $this->buildNavigationItem($cacheItem);
        }

        // end
        $html .= '  </ul>' . "\n";

        return $html;
    }

    private function buildNavigationItem(MediaFolderCacheItem $cacheItem): string
    {
        // define url
        $url = $this->getLink(['folder' => $cacheItem->id]);

        // start
        $html = '<li id="folder-' . $cacheItem->id . '" data-id="' . $cacheItem->id . '" rel="folder"'
            . ' data-jstree=\'{"type":"folder"}\'>' . "\n";

        // insert link
        $html .= '<a href="' . $url . '">' . htmlspecialchars((string) $cacheItem->name) . ' (' . $cacheItem->numberOfMediaItems . ')</a>' . "\n";

        if ($cacheItem->numberOfChildren > 0) {
            $html .= $this->buildNavigationTree($cacheItem->children);
        }

        return $html . '</li>' . "\n";
    }

    private function getLink($parameters = [])
    {
        return BackendModel::createUrlForAction(
            $this->itemAction,
            null,
            null,
            $parameters
        );
    }
}
