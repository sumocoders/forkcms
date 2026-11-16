<?php

namespace Frontend\Core\Engine;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

/**
 * Twig note for writing out the compiled version of a form field error.
 */
#[YieldReady]
class FormFieldErrorNode extends Node
{
    /**
     * @param string $form Name of the template var holding the form this field error belongs to.
     * @param string $field Name of the field of which we need to render the error.
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
        $writeErrorMessage = 'yield '
            . "\$context['form_{$this->form}']->getField('{$this->field}')->getErrors() "
            . "? '<span class=\"formError\">' "
            . ". \$context['form_{$this->form}']->getField('{$this->field}')->getErrors() "
            . ". '</span>' : '';";
        $compiler
            ->addDebugInfo($this)
            ->write($writeErrorMessage)
        ;
    }
}
