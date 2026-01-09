<?php

namespace Frontend\Core\Engine;

use Twig\Compiler;
use Twig\Node\Node;

/**
 * Twig node for writing out the compiled version of form field.
 */
class FormFieldNode extends Node
{
    /**
     * @param string $form Name of the template var holding the form this field belongs to.
     * @param string $field Name of the field to render.
     * @param int $lineNumber
     * @param string $tag
     */
    public function __construct(
        private readonly string $form,
        private readonly string $field,
        int $lineNumber,
        string $tag,
    ) {
        parent::__construct([], [], $lineNumber, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $form = "\$context['form_{$this->form}']";
        $parseField = $form . "->getField('{$this->field}')->parse()";

        $compiler
            ->addDebugInfo($this)
            ->write("echo $parseField;\n")
        ;
    }
}
