<?php

namespace Common\Doctrine\Entity;

use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Core\Engine\Model as BackendModel;
use Common\Doctrine\ValueObject\SEOFollow;
use Common\Doctrine\ValueObject\SEOIndex;
use Doctrine\ORM\Mapping as ORM;
use ForkCMS\Utility\Thumbnails;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="meta", indexes={@ORM\Index(name="idx_url", columns={"url"})})
 * @ORM\Entity(repositoryClass="Common\Doctrine\Repository\MetaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Meta
{
    /**
     * @var string|null
     *
     * Only can be string during persisting or updating in the database as it then contains the serialised value
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $data;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, name="og_image", nullable=true)
     */
    private $ogImage;

    /**
     * @var string|null
     */
    private $oldOgImage;

    public function __construct(
        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $keywords,
        /**
         * @ORM\Column(type="boolean", name="keywords_overwrite", options={"default" = false})
         */
        private bool $keywordsOverwrite,
        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $description,
        /**
         * @ORM\Column(type="boolean", name="description_overwrite", options={"default" = false})
         */
        private bool $descriptionOverwrite,
        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $title,
        /**
         * @ORM\Column(type="boolean", name="title_overwrite", options={"default" = false})
         */
        private bool $titleOverwrite,
        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $url,
        /**
         * @ORM\Column(type="boolean", name="url_overwrite", options={"default" = false})
         */
        private bool $urlOverwrite,
        ?string $canonicalUrl,
        bool $canonicalUrlOverwrite,
        /**
         * @ORM\Column(type="text", nullable=true)
         */
        private ?string $custom = null,
        /**
         * @ORM\Column(type="seo_follow", name="seo_follow", nullable=true)
         */
        private ?SEOFollow $seoFollow = null,
        /**
         * @ORM\Column(type="seo_index", name="seo_index", nullable=true)
         */
        private ?SEOIndex $seoIndex = null,
        private array $unserialisedData = [],
        private ?UploadedFile $uploadedOgImage = null,
        private bool $deleteOgImage = false,
        /**
         *
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         * @ORM\Column(type="integer")
         */
        private ?int $id = null,
    ) {
        if ($canonicalUrlOverwrite) {
            $this->unserialisedData['canonical_url'] = $canonicalUrl;
            $this->unserialisedData['canonical_url_overwrite'] = $canonicalUrlOverwrite;
        } else {
            unset($this->unserialisedData['canonical_url']);
            unset($this->unserialisedData['canonical_url_overwrite']);
        }
    }

    public function update(
        string $keywords,
        bool $keywordsOverwrite,
        string $description,
        bool $descriptionOverwrite,
        string $title,
        bool $titleOverwrite,
        string $url,
        bool $urlOverwrite,
        ?string $canonicalUrl = null,
        bool $canonicalUrlOverwrite = false,
        ?string $custom = null,
        ?SEOFollow $seoFollow = null,
        ?SEOIndex $seoIndex = null,
        array $unserialisedData = [],
        ?UploadedFile $uploadedOgImage = null,
        bool $deleteOgImage = false,
    ) {
        $this->keywords = $keywords;
        $this->keywordsOverwrite = $keywordsOverwrite;
        $this->description = $description;
        $this->descriptionOverwrite = $descriptionOverwrite;
        $this->title = $title;
        $this->titleOverwrite = $titleOverwrite;
        $this->url = $url;
        $this->urlOverwrite = $urlOverwrite;
        $this->custom = $custom;
        $this->unserialisedData = $unserialisedData;
        $this->seoFollow = $seoFollow;
        $this->seoIndex = $seoIndex;
        $this->uploadedOgImage = $uploadedOgImage;
        $this->deleteOgImage = $deleteOgImage;
        if ($deleteOgImage) {
            $this->oldOgImage = $this->ogImage;
            $this->ogImage = null;
        }

        if ($canonicalUrlOverwrite) {
            $this->unserialisedData['canonical_url'] = $canonicalUrl;
            $this->unserialisedData['canonical_url_overwrite'] = $canonicalUrlOverwrite;
        } else {
            unset($this->unserialisedData['canonical_url']);
            unset($this->unserialisedData['canonical_url_overwrite']);
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function serialiseData()
    {
        if (!empty($this->unserialisedData)) {
            $this->data = serialize($this->unserialisedData);

            return;
        }

        $this->data = null;
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     * @ORM\PostLoad
     */
    public function unSerialiseData()
    {
        if ($this->data === null) {
            $this->unserialisedData = [];

            return;
        }

        $this->unserialisedData = unserialize($this->data, ['allowed_classes' => false]);
    }

    public static function fromBackendMeta(BackendMeta $meta): self
    {
        $metaData = $meta->getData();

        return new self(
            $metaData['keywords'],
            $metaData['keywords_overwrite'],
            $metaData['description'],
            $metaData['description_overwrite'],
            $metaData['title'],
            $metaData['title_overwrite'],
            $metaData['url'],
            $metaData['url_overwrite'],
            $metaData['canonical_url'],
            $metaData['canonical_url_overwrite'],
            $metaData['custom'],
            array_key_exists('SEOFollow', $metaData) ? SEOFollow::fromString((string) $metaData['SEOFollow']) : null,
            array_key_exists('SEOIndex', $metaData) ? SEOIndex::fromString((string) $metaData['SEOIndex']) : null,
            $metaData['data'] ?? [],
            id: $meta->getId()
        );
    }

    /**
     * Used in the transformer of the Symfony form type for this entity
     *
     * @param array $metaData
     *
     * @return self
     */
    public static function updateWithFormData(array $metaData): self
    {
        return new self(
            $metaData['keywords'],
            $metaData['keywordsOverwrite'],
            $metaData['description'],
            $metaData['descriptionOverwrite'],
            $metaData['title'],
            $metaData['titleOverwrite'],
            $metaData['url'],
            $metaData['urlOverwrite'],
            $metaData['canonical_url'],
            $metaData['canonical_url_overwrite'],
            $metaData['custom'] ?? null,
            SEOFollow::fromString((string) $metaData['SEOFollow']),
            SEOIndex::fromString((string) $metaData['SEOIndex']),
            id: (int) $metaData['id']
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function isKeywordsOverwrite(): bool
    {
        return $this->keywordsOverwrite;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isDescriptionOverwrite(): bool
    {
        return $this->descriptionOverwrite;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isTitleOverwrite(): bool
    {
        return $this->titleOverwrite;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isUrlOverwrite(): bool
    {
        return $this->urlOverwrite;
    }

    public function getCanonicalUrl(): ?string
    {
        if (array_key_exists('canonical_url', $this->unserialisedData)) {
            return $this->unserialisedData['canonical_url'];
        }

        return null;
    }

    public function isCanonicalUrlOverwrite(): bool
    {
        if (array_key_exists('canonicalUrlOverwrite', $this->unserialisedData)) {
            return (bool) $this->unserialisedData['canonical_url_overwrite'];
        }

        return false;
    }

    public function getCustom(): ?string
    {
        return $this->custom;
    }

    public function getData(): array
    {
        return $this->unserialisedData;
    }

    public function hasSEOIndex(): bool
    {
        return $this->seoIndex instanceof SEOIndex && !$this->seoIndex->isNone();
    }

    public function getSEOIndex(): ?SEOIndex
    {
        if (!$this->hasSEOIndex()) {
            return null;
        }

        return $this->seoIndex;
    }

    public function hasSEOFollow(): bool
    {
        return $this->seoFollow instanceof SEOFollow && !$this->seoFollow->isNone();
    }

    public function getSEOFollow(): ?SEOFollow
    {
        if (!$this->hasSEOFollow()) {
            return null;
        }

        return $this->seoFollow;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function getUploadedOgImage(): ?UploadedFile
    {
        return $this->uploadedOgImage;
    }

    public function isDeleteOgImage(): bool
    {
        return $this->deleteOgImage;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prepareOgImage(): void
    {
        $this->oldOgImage = $this->ogImage;

        if ($this->uploadedOgImage instanceof UploadedFile) {
            $extension = $this->uploadedOgImage->getClientOriginalExtension();
            $filename = $this->getTitle() . '_og_image_' . time() . '.' . $extension;

            $this->ogImage = $filename;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadOgImage(): void
    {
        $imagePath = FRONTEND_FILES_PATH . '/Pages/images';
        /** @var Thumbnails $thumbnails */
        $thumbnails = BackendModel::getContainer()->get(Thumbnails::class);

        if ($this->deleteOgImage) {
            $thumbnails->delete($imagePath, $this->oldOgImage);
        }

        if (!$this->uploadedOgImage instanceof UploadedFile) {
            return;
        }

        $thumbnails->delete($imagePath, $this->oldOgImage);

        $filename = $this->ogImage;

        $imageFullPath = $imagePath . '/source/' . $filename;

        $this->uploadedOgImage->move($imagePath . '/source', $filename);
        $thumbnails->generate($imagePath, $imageFullPath);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeOgImage(): void
    {
        $imagePath = FRONTEND_FILES_PATH . '/Pages/images';
        /** @var Thumbnails $thumbnails */
        $thumbnails = BackendModel::getContainer()->get(Thumbnails::class);

        $thumbnails->delete($imagePath, $this->oldOgImage);
    }
}
