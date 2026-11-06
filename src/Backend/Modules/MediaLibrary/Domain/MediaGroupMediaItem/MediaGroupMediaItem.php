<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

/**
 * MediaGroup MediaItem
 */
#[ORM\Entity(repositoryClass: MediaGroupMediaItemRepository::class)]
class MediaGroupMediaItem implements JsonSerializable
{
    /**
     * @var Uuid
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private $id;

    /**
     * @var MediaGroup
     */
    #[ORM\JoinColumn(name: 'mediaGroupId', referencedColumnName: 'id', nullable: false, onDelete: 'cascade')]
    #[ORM\ManyToOne(targetEntity: MediaGroup::class, cascade: ['persist'], inversedBy: 'connectedItems')]
    protected $group;

    /**
     * @var MediaItem
     */
    #[ORM\JoinColumn(name: 'mediaItemId', referencedColumnName: 'id', nullable: false, onDelete: 'cascade')]
    #[ORM\ManyToOne(targetEntity: MediaItem::class, cascade: ['persist'], fetch: 'EAGER', inversedBy: 'groups')]
    protected $item;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime')]
    protected $createdOn;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    protected $sequence;

    private function __construct(
        MediaGroup $group,
        MediaItem $item,
        int $sequence
    ) {
        $this->id = Uuid::v4();
        $this->group = $group;
        $this->item = $item;
        $this->createdOn = new DateTime();
        $this->sequence = $sequence;
    }

    public static function create(
        MediaGroup $group,
        MediaItem $item,
        int $sequence
    ): self {
        return new self(
            $group,
            $item,
            $sequence
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toRfc4122(),
            'item' => $this->item,
            'createdOn' => $this->createdOn->getTimestamp(),
            'sequence' => $this->sequence,
        ];
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getGroup(): MediaGroup
    {
        return $this->group;
    }

    public function getItem(): MediaItem
    {
        return $this->item;
    }

    public function getCreatedOn(): DateTime
    {
        return $this->createdOn;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }
}
