<?php

namespace ForkCMS\Bundle\InstallerBundle\Requirement;

final class RequirementCategory
{
    /** @var Requirement[] */
    private $requirements;

    public function __construct(private string $name, Requirement ...$requirements)
    {
        $this->requirements = $requirements;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Requirement[]
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * @return Requirement[]
     */
    public function getWarnings(): array
    {
        return array_filter(
            $this->requirements,
            fn(Requirement $requirement) => $requirement->getStatus()->isWarning()
        );
    }

    /**
     * @return Requirement[]
     */
    public function getErrors(): array
    {
        return array_filter(
            $this->requirements,
            fn(Requirement $requirement) => $requirement->getStatus()->isError()
        );
    }

    public function hasWarnings(): bool
    {
        return count($this->getWarnings()) > 0;
    }

    public function hasErrors(): bool
    {
        return count($this->getErrors()) > 0;
    }
}
