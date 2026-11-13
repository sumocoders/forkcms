<?php

namespace Frontend\Modules\MediaGalleries\Widgets;

use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGallery;
use Backend\Modules\MediaGalleries\Domain\MediaGallery\MediaGalleryRepository;
use Frontend\Core\Engine\Base\Widget as BackendBaseWidget;

/**
 * This will show a MediaGallery.
 */
class Gallery extends BackendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        /** @var MediaGallery|null $mediaGallery */
        $mediaGallery = $this->getMediaGallery();

        if (!$mediaGallery instanceof MediaGallery) {
            return;
        }

        // Note: we must assign the "widget" first, before assigning variables
        $this->template->assign(
            'widget',
            $this->get('media_library.helper.frontend')->parseWidget(
                $mediaGallery->getAction(),
                $mediaGallery->getMediaGroup()->getId()
            )
        );

        $this->template->assign('mediaGallery', $mediaGallery);
    }

    private function getMediaGallery(): ?MediaGallery
    {
        $galleryId = $this->getGalleryId();
        if ($galleryId === null) {
            return null;
        }

        try {
            /** @var MediaGallery $mediaGallery */
            $mediaGallery = $this->get(MediaGalleryRepository::class)->findOneById($galleryId);
        } catch (\Exception) {
            return null;
        }

        // Only return MediaGallery if the gallery should be visible
        return $mediaGallery->isVisible() ? $mediaGallery : null;
    }

    /**
     * Galleries saved before the id became a string hold a serialized Uuid object. Widget data is unserialized
     * without allowing objects, so that Uuid arrives as __PHP_Incomplete_Class and we read its protected uid.
     */
    private function getGalleryId(): ?string
    {
        $galleryId = $this->data['gallery_id'] ?? null;

        if ($galleryId instanceof \__PHP_Incomplete_Class) {
            $galleryId = ((array) $galleryId)["\0*\0uid"] ?? null;
        }

        return is_string($galleryId) ? $galleryId : null;
    }
}
