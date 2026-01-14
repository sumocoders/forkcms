<?php

namespace Backend\Modules\ContentBlocks\Domain\ContentBlock;

use Backend\Core\Engine\Model;
use Common\Locale;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="content_blocks")
 * @ORM\Entity(repositoryClass="ContentBlockRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ContentBlock
{
    const DEFAULT_TEMPLATE = 'Default.html.twig';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="revision_id")
     */
    private $revisionId;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="created_on")
     */
    private $createdOn;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="edited_on")
     */
    private $editedOn;

    private function __construct(
        /**
         * @ORM\Column(type="integer")
         */
        private int $id,
        /**
         * @ORM\Column(type="integer", name="user_id")
         */
        private int $userId,
        /**
         * @ORM\Column(type="integer", name="extra_id")
         */
        private int $extraId,
        /**
         * @ORM\Column(type="string", length=255, options={"default" = "Default.html.twig"})
         */
        private string $template,
        /**
         * @ORM\Column(type="locale", name="language")
         */
        private Locale $locale,
        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $title,
        /**
         * @ORM\Column(type="text", nullable=true)
         */
        private string $text,
        /**
         * @ORM\Column(type="boolean", name="hidden", options={"default" = false})
         */
        private bool $isHidden,
        /**
         * @ORM\Column(type="content_blocks_status", options={"default" = "active"})
         */
        private Status $status,
    ) {
    }

    public static function fromDataTransferObject(ContentBlockDataTransferObject $dataTransferObject)
    {
        if ($dataTransferObject->hasExistingContentBlock()) {
            $dataTransferObject->getContentBlockEntity()->status = Status::archived();
        }

        return self::create($dataTransferObject);
    }

    private static function create(ContentBlockDataTransferObject $dataTransferObject): self
    {
        return new self(
            $dataTransferObject->id,
            $dataTransferObject->userId,
            $dataTransferObject->extraId,
            $dataTransferObject->template,
            $dataTransferObject->locale,
            $dataTransferObject->title,
            $dataTransferObject->text,
            !$dataTransferObject->isVisible,
            Status::active()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRevisionId(): int
    {
        return $this->revisionId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getExtraId(): int
    {
        return $this->extraId;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getCreatedOn(): DateTime
    {
        return $this->createdOn;
    }

    public function getEditedOn(): DateTime
    {
        return $this->editedOn;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdOn = $this->editedOn = new DateTime();
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist()
    {
        $this->updateWidget();
    }

    /**
     * Update the widget so it shows the correct title and has the correct template
     */
    private function updateWidget()
    {
        $editUrl = Model::createUrlForAction('Edit', 'ContentBlocks', (string) $this->locale) . '&id=' . $this->id;

        // update data for the extra
        // @TODO replace this with an implementation with doctrine
        $extras = Model::getExtras([$this->extraId]);
        $extra = reset($extras);
        $data = [
            'id' => $this->id,
            'language' => (string) $this->locale,
            'edit_url' => $editUrl,
        ];
        if (isset($extra['data'])) {
            $data += (array) $extra['data'];
        }
        $data['custom_template'] = $this->template;
        $data['extra_label'] = $this->title;

        Model::updateExtra($this->extraId, 'data', $data);
    }

    public function archive()
    {
        $this->status = Status::archived();
    }

    public function getDataTransferObject(): ContentBlockDataTransferObject
    {
        return new ContentBlockDataTransferObject($this);
    }
}
