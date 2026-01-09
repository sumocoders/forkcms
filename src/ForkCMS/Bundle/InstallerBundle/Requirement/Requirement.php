<?php

namespace ForkCMS\Bundle\InstallerBundle\Requirement;

final class Requirement
{
    private function __construct(
        private readonly string $name,
        private readonly RequirementStatus $status,
        private readonly string $message,
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
