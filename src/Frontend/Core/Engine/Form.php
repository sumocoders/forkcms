<?php

namespace Frontend\Core\Engine;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */
use SpoonFilter;
use SpoonFormButton;
use SpoonFormDropdown;
use SpoonFormFile;
use SpoonFormPassword;
use SpoonFormRadiobutton;
use SpoonFormText;
use SpoonFormTextarea;
use SpoonFormTime;

/**
 * This is our extended version of SpoonForm.
 */
class Form extends \Common\Core\Form
{
    /**
     * Adds a single file field.
     *
     * @param string $name       Name of the element.
     * @param string $class      Class(es) that will be applied on the element.
     * @param string $classError Class(es) that will be applied on the element when an error occurs.
     *
     * @return SpoonFormFile
     */
    public function addFile($name, $class = null, $classError = null): SpoonFormFile
    {
        $name = (string) $name;
        $class = ($class !== null) ? (string) $class : 'inputFile';
        $classError = ($classError !== null) ? (string) $classError : 'inputFileError';

        // create and return a file field
        return parent::addFile($name, $class, $classError);
    }

    /**
     * Adds a single image field.
     *
     * @param string $name       The name of the element.
     * @param string $class      Class(es) that will be applied on the element.
     * @param string $classError Class(es) that will be applied on the element when an error occurs.
     *
     * @return FormImage
     */
    public function addImage($name, $class = null, $classError = null): FormImage
    {
        $name = (string) $name;
        $class = ($class !== null) ? (string) $class : 'inputFile inputImage';
        $classError = ($classError !== null) ? (string) $classError : 'inputFileError inputImageError';

        // add element
        $this->add(new FormImage($name, $class, $classError));

        return $this->getField($name);
    }

    /**
     * Adds a single password field.
     *
     * @param string $name       The name of the field.
     * @param string $value      The value for the field.
     * @param int    $maxLength  The maximum length for the field.
     * @param string $class      Class(es) that will be applied on the element.
     * @param string $classError Class(es) that will be applied on the element when an error occurs.
     * @param bool   $HTML       Will the field contain HTML?
     * @return \SpoonFormPassword
     */
    public function addPassword(
        $name,
        $value = null,
        $maxLength = null,
        $class = null,
        $classError = null,
        $HTML = false
    ) {
        $name = (string) $name;
        $value = ($value !== null) ? (string) $value : null;
        $maxLength = ($maxLength !== null) ? (int) $maxLength : null;
        $class = ($class !== null) ? (string) $class : 'form-control inputPassword';
        $classError = ($classError !== null) ? (string) $classError : 'inputTextError inputPasswordError';
        $HTML = (bool) $HTML;

        // create and return a password field
        return parent::addPassword($name, $value, $maxLength, $class, $classError, $HTML);
    }

    /**
     * Adds a single radio button.
     *
     * @param string $name       The name of the element.
     * @param array  $values     The possible values for the radio button.
     * @param string $checked    Should the element be checked?
     * @param string $class      Class(es) that will be applied on the element.
     * @param string $classError Class(es) that will be applied on the element when an error occurs.
     * @return \SpoonFormRadiobutton
     */
    public function addRadiobutton($name, array $values, $checked = null, $class = null, $classError = null)
    {
        $name = (string) $name;
        $values = (array) $values;
        $checked = ($checked !== null) ? (string) $checked : null;
        $class = ($class !== null) ? (string) $class : 'inputRadio';
        $classError = ($classError !== null) ? (string) $classError : 'inputRadioError';

        // create and return a radio button
        return parent::addRadiobutton($name, $values, $checked, $class, $classError);
    }

    /**
     * Adds a single textfield.
     *
     * @param string $name       The name of the element.
     * @param string $value      The value inside the element.
     * @param int    $maxLength  The maximum length for the value.
     * @param string $class      Class(es) that will be applied on the element.
     * @param string $classError Class(es) that will be applied on the element when an error occurs.
     * @param bool   $HTML       Will this element contain HTML?
     * @return \SpoonFormText
     */
    public function addText($name, $value = null, $maxLength = 255, $class = null, $classError = null, $HTML = false)
    {
        $name = (string) $name;
        $value = ($value !== null) ? (string) $value : null;
        $maxLength = ($maxLength !== null) ? (int) $maxLength : null;
        $class = ($class !== null) ? (string) $class : 'form-control';
        $classError = ($classError !== null) ? (string) $classError : 'inputTextError';
        $HTML = (bool) $HTML;

        // create and return a textfield
        return parent::addText($name, $value, $maxLength, $class, $classError, $HTML);
    }

    /**
     * Adds a single textarea.
     *
     * @param string $name       The name of the element.
     * @param string $value      The value inside the element.
     * @param string $class      Class(es) that will be applied on the element.
     * @param string $classError Class(es) that will be applied on the element when an error occurs.
     * @param bool   $HTML       Will the element contain HTML?
     * @return \SpoonFormTextarea
     */
    public function addTextarea($name, $value = null, $class = null, $classError = null, $HTML = false)
    {
        $name = (string) $name;
        $value = ($value !== null) ? (string) $value : null;
        $class = ($class !== null) ? (string) $class : 'form-control';
        $classError = ($classError !== null) ? (string) $classError : 'textareaError';
        $HTML = (bool) $HTML;

        // create and return a textarea
        return parent::addTextarea($name, $value, $class, $classError, $HTML);
    }

    /**
     * Adds a single time field.
     *
     * @param string $name       The name of the element.
     * @param string $value      The value inside the element.
     * @param string $class      Class(es) that will be applied on the element.
     * @param string $classError Class(es) that will be applied on the element when an error occurs.
     * @return \SpoonFormTime
     */
    public function addTime($name, $value = null, $class = null, $classError = null)
    {
        $name = (string) $name;
        $value = ($value !== null) ? (string) $value : null;
        $class = ($class !== null) ? (string) $class : 'form-control inputTime';
        $classError = ($classError !== null) ? (string) $classError : 'inputTextError inputTimeError';

        // create and return a time field
        return parent::addTime($name, $value, $class, $classError);
    }

    /**
     * Generates an example template, based on the elements already added.
     *
     * @return string
     */
    public function getTemplateExample(): string
    {
        // start form
        $value = "\n";
        $value .= '{form:' . $this->getName() . "}\n";

        /**
         * At first all the hidden fields need to be added to this form, since
         * they're not shown and are best to be put right beneath the start of the form tag.
         */
        foreach ($this->getFields() as $object) {
            // is a hidden field
            if (($object instanceof \SpoonFormHidden) && $object->getName() != 'form') {
                $value .= "\t" . '{$hid' . str_replace('[]', '', SpoonFilter::toCamelCase($object->getName())) . "}\n";
            }
        }

        /**
         * Add all the objects that are NOT hidden fields. Based on the existance of some methods
         * errors will or will not be shown.
         */
        foreach ($this->getFields() as $object) {
            // NOT a hidden field
            if (!($object instanceof \SpoonFormHidden)) {
                if ($object instanceof SpoonFormButton) {
                    $value .= "\t" . '<p>' . "\n";
                    $value .= "\t\t" . '{$btn' . SpoonFilter::toCamelCase($object->getName()) . '}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                } elseif ($object instanceof \SpoonFormCheckbox) {
                    $value .= "\t" . '<p{option:chk' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:chk' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}>' . "\n";
                    $value .= "\t\t" . '<label for="' . $object->getAttribute('id') . '">' .
                              SpoonFilter::toCamelCase($object->getName()) . '</label>' . "\n";
                    $value .= "\t\t" . '{$chk' . SpoonFilter::toCamelCase($object->getName()) .
                              '} {$chk' . SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                } elseif ($object instanceof \SpoonFormMultiCheckbox) {
                    $value .= "\t" . '<div{option:chk' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:chk' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}>' . "\n";
                    $value .= "\t\t" . '<p class="label">' . SpoonFilter::toCamelCase($object->getName()) .
                              '</p>' . "\n";
                    $value .= "\t\t" . '{$chk' . SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t\t" . '<ul class="inputList">' . "\n";
                    $value .= "\t\t\t" . '{iteration:' . $object->getName() . '}' . "\n";
                    $value .= "\t\t\t\t" . '<li><label for="{$' . $object->getName() . '.id}">{$' .
                              $object->getName() . '.chk' . SpoonFilter::toCamelCase($object->getName()) .
                              '} {$' . $object->getName() . '.label}</label></li>' . "\n";
                    $value .= "\t\t\t" . '{/iteration:' . $object->getName() . '}' . "\n";
                    $value .= "\t\t" . '</ul>' . "\n";
                    $value .= "\t" . '</div>' . "\n\n";
                } elseif ($object instanceof SpoonFormDropdown) {
                    $value .= "\t" . '<p{option:ddm' .
                              str_replace(
                                  '[]',
                                  '',
                                  SpoonFilter::toCamelCase($object->getName())
                              ) . 'Error} class="errorArea"{/option:ddm' .
                              str_replace(
                                  '[]',
                                  '',
                                  SpoonFilter::toCamelCase($object->getName())
                              ) . 'Error}>' . "\n";
                    $value .= "\t\t" . '<label for="' . $object->getAttribute('id') . '">' .
                              str_replace(
                                  '[]',
                                  '',
                                  SpoonFilter::toCamelCase($object->getName())
                              ) . '</label>' . "\n";
                    $value .= "\t\t" . '{$ddm' . str_replace('[]', '', SpoonFilter::toCamelCase($object->getName())) .
                              '} {$ddm' .
                              str_replace(
                                  '[]',
                                  '',
                                  SpoonFilter::toCamelCase($object->getName())
                              ) . 'Error}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                } elseif ($object instanceof \SpoonFormImage) {
                    $value .= "\t" . '<p{option:file' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:file' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}>' . "\n";
                    $value .= "\t\t" . '<label for="' . $object->getAttribute('id') . '">' .
                              SpoonFilter::toCamelCase($object->getName()) . '</label>' . "\n";
                    $value .= "\t\t" . '{$file' . SpoonFilter::toCamelCase($object->getName()) .
                              '} <span class="helpTxt">{$msgHelpImageField}</span> {$file' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                } elseif ($object instanceof SpoonFormFile) {
                    $value .= "\t" . '<p{option:file' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:file' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}>' . "\n";
                    $value .= "\t\t" . '<label for="' . $object->getAttribute('id') . '">' .
                              SpoonFilter::toCamelCase($object->getName()) . '</label>' . "\n";
                    $value .= "\t\t" . '{$file' . SpoonFilter::toCamelCase($object->getName()) .
                              '} {$file' . SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                } elseif ($object instanceof SpoonFormRadiobutton) {
                    $value .= "\t" . '<div{option:rbt' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:rbt' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}>' . "\n";
                    $value .= "\t\t" . '<p class="label">' . SpoonFilter::toCamelCase($object->getName()) .
                              '</p>' . "\n";
                    $value .= "\t\t" . '{$rbt' . SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t\t" . '<ul class="inputList">' . "\n";
                    $value .= "\t\t\t" . '{iteration:' . $object->getName() . '}' . "\n";
                    $value .= "\t\t\t\t" . '<li><label for="{$' . $object->getName() . '.id}">{$' .
                              $object->getName() . '.rbt' . SpoonFilter::toCamelCase($object->getName()) .
                              '} {$' . $object->getName() . '.label}</label></li>' . "\n";
                    $value .= "\t\t\t" . '{/iteration:' . $object->getName() . '}' . "\n";
                    $value .= "\t\t" . '</ul>' . "\n";
                    $value .= "\t" . '</div>' . "\n\n";
                } elseif ($object instanceof \SpoonFormDate) {
                    $value .= "\t" . '<p{option:txt' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:txt' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error}>' . "\n";
                    $value .= "\t\t" . '<label for="' . $object->getAttribute('id') . '">' .
                              SpoonFilter::toCamelCase($object->getName()) . '</label>' . "\n";
                    $value .= "\t\t" . '{$txt' . SpoonFilter::toCamelCase($object->getName()) .
                              '} <span class="helpTxt">{$msgHelpDateField}</span> {$txt' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                } elseif ($object instanceof SpoonFormTime) {
                    $value .= "\t" . '<p{option:txt' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:txt' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error}>' . "\n";
                    $value .= "\t\t" . '<label for="' . $object->getAttribute('id') . '">' .
                              SpoonFilter::toCamelCase($object->getName()) . '</label>' . "\n";
                    $value .= "\t\t" . '{$txt' . SpoonFilter::toCamelCase($object->getName()) .
                              '} <span class="helpTxt">{$msgHelpTimeField}</span> {$txt' .
                              SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                } elseif (($object instanceof SpoonFormPassword) ||
                          ($object instanceof SpoonFormTextarea) ||
                          ($object instanceof SpoonFormText)
                ) {
                    $value .= "\t" . '<p{option:txt' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error} class="errorArea"{/option:txt' . SpoonFilter::toCamelCase($object->getName()) .
                              'Error}>' . "\n";
                    $value .= "\t\t" . '<label for="' . $object->getAttribute('id') . '">' .
                              SpoonFilter::toCamelCase($object->getName()) . '</label>' . "\n";
                    $value .= "\t\t" . '{$txt' . SpoonFilter::toCamelCase($object->getName()) .
                              '} {$txt' . SpoonFilter::toCamelCase($object->getName()) . 'Error}' . "\n";
                    $value .= "\t" . '</p>' . "\n\n";
                }
            }
        }

        return $value . '{/form:' . $this->getName() . '}';
    }

    /**
     * Fetches all the values for this form as key/value pairs
     *
     * @param mixed $excluded Which elements should be excluded?
     *
     * @return array
     */
    public function getValues($excluded = ['form', 'save', '_utf8']): array
    {
        return parent::getValues($excluded);
    }

    /**
     * Parse the form
     *
     * @param TwigTemplate $tpl The template instance wherein the form will be parsed.
     */
    public function parse($tpl): void
    {
        parent::parse($tpl);
        $this->validate();

        // if the form is submitted but there was an error, assign a general error
        if ($this->isSubmitted() && !$this->isCorrect()) {
            $tpl->assign('formError', true);
        }
    }
}
