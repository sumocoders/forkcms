<?php

namespace Frontend\Core\Engine;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

/**
 * Twig node for writing out the compiled representation of an opeing form tag.
 */
#[YieldReady]
class FormNode extends Node
{
    /**
     * @param string $form Template variable holding the form.
     * @param int $lineNumber
     */
    public function __construct(
        private readonly string $form,
        int $lineNumber,
    ) {
        parent::__construct([], [], $lineNumber);
    }

    public function compile(Compiler $compiler): void
    {
        // Set some string representations to make the code writing via the
        // compiler a bit more readable. ("a bit")
        $form = "\$context['form_{$this->form}']";
        $formAction = $form . '->getAction()';
        $formMethod = $form . '->getMethod()';
        $formName = $form . '->getName()';
        $formToken = $form . '->getToken()';
        $formUseToken = $form . '->getUseToken()';
        $formParamsHtml = $form . '->getParametersHTML()';

        // "yield" only takes a single expression, so everything is concatenated instead of
        // being passed as the comma separated arguments "echo" used to accept.
        $openingTag = '\'<form method="\' . ' . $formMethod
            . ' . \'" action="\' . ' . $formAction
            . ' . \'"\' . ' . $formParamsHtml
            . ' . \'>\'';
        $hiddenFormName = '\'<input type="hidden" name="form" value="\' . ' . $formName
            . ' . \'" id="form\' . ucfirst(' . $formName . ')'
            . ' . \'" />\'';
        $hiddenFormToken = '\'<input type="hidden" name="form_token" value="\' . ' . $formToken
            . ' . \'" id="formToken\' . ucfirst(' . $formName . ')'
            . ' . \'" />\'';

        $compiler
            ->addDebugInfo($this)
            ->write('yield ' . $openingTag . ";\n")
            ->write('yield ' . $hiddenFormName . ";\n")
            ->write('if (' . $formUseToken . ") {\n")
            ->indent()
            ->write('yield ' . $hiddenFormToken . ";\n")
            ->outdent()
            ->write("}\n")
        ;
    }
}
