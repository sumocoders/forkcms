<?php

namespace Frontend\Core\Engine;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

/**
 * Twig node for writing out the compiled version of form field.
 */
#[YieldReady]
class FormFieldNode extends Node
{
    /**
     * @param string $form Name of the template var holding the form this field belongs to.
     * @param string $field Name of the field to render.
     * @param int $lineNumber
     */
    public function __construct(
        private readonly string $form,
        private readonly string $field,
        int $lineNumber,
    ) {
        parent::__construct([], [], $lineNumber);
    }

    public function compile(Compiler $compiler): void
    {
        $form = "\$context['form_{$this->form}']";
        $parseField = $form . "->getField('{$this->field}')->parse()";

        $compiler
            ->addDebugInfo($this)
            ->write("yield $parseField;\n")
        ;
    }
}
