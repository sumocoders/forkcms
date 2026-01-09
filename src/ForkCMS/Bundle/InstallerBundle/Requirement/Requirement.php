<?php

namespace ForkCMS\Bundle\InstallerBundle\Requirement;

final readonly class Requirement
{
    private function __construct(
        private string $name,
        private RequirementStatus $status,
        private string $message,
    ) {
    }

    public static function check(
        string $name,
        bool $requirementIsMet,
        string $requirementIsMetMessage,
        string $requirementNotMetMessage,
        RequirementStatus $requirementNotMetStatus
    ): self {
        return new self(
            $name,
            $requirementIsMet ? RequirementStatus::success() : $requirementNotMetStatus,
            $requirementIsMet ? $requirementIsMetMessage : $requirementNotMetMessage
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): RequirementStatus
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
