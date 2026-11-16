<?php

namespace Frontend\Core\Engine;

use Backend\Core\Language\Language as BackendLanguage;
use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;
use function Symfony\Component\String\s;

/**
 * Twig node for writing the SEO form
 */
#[YieldReady]
class SeoFormNode extends Node
{
    /**
     * @param string $form Name of the template var holding the form this field
     *                     belongs to.
     * @param int $lineNumber Line number in the template source file.
     */
    public function __construct(private readonly string $form, int $lineNumber)
    {
        parent::__construct([], [], $lineNumber);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write('yield \'<div class="row">\';')
            ->write('yield \'<div class="col-md-12">\';')
            ->write('yield \'<div class="panel panel-default">\';')
            ->write('yield \'<div class="panel-heading">\';')
            ->write('yield "<h2 class=\"panel-title\">' . $this->lbl('Titles') . '</h2>";')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="panel-body">\';')
            ->write('yield \'<div class="form-group">\';')
            ->write('yield \'<ul class="list-unstyled checkboxTextFieldCombo">\';')
            ->write('yield \'<li class="checkbox">\';')
            ->write('yield "<p><label for=\"pageTitleOverwrite\">";')
            ->write($this->getField('page_title_overwrite'))
            ->write('yield "' . $this->lbl('PageTitle') . '</label></p>";')
            ->write($this->getError('page_title'))
            ->write('yield "<label for=\"pageTitle\" class=\"sr-only\">";')
            ->write('yield "' . $this->lbl('PageTitle') . '</label>";')
            ->write($this->getField('page_title'))
            ->write('yield "<p class=\"help-block\">' . $this->msg('HelpPageTitle') . '</p>";')
            ->write('yield \'</li>\';')
            ->write('yield \'</ul>\';')
            ->write('yield \'</div>\';');

        $compiler
            ->write('if (' . $this->hasField('navigation_title_overwrite') . ') {')
            ->write('yield \'<div class="form-group last">\';')
            ->write('yield \'<ul class="list-unstyled checkboxTextFieldCombo">\';')
            ->write('yield \'<li class="checkbox">\';')
            ->write('yield "<p><label for=\"navigationTitleOverwrite\">";')
            ->write($this->getField('navigation_title_overwrite'))
            ->write('yield "' . $this->lbl('NavigationTitle') . '</label></p>";')
            ->write($this->getError('navigation_title'))
            ->write('yield "<label for=\"navigationTitle\" class=\"sr-only\">";')
            ->write('yield "' . $this->lbl('NavigationTitle') . '</label>";')
            ->write($this->getField('navigation_title'))
            ->write('yield "<p class=\"help-block\">' . $this->msg('HelpNavigationTitle') . '</p>";')
            ->write('yield \'</li>\';')
            ->write('yield \'</ul>\';')
            ->write('yield \'</div>\';')
            ->write('}');

        $compiler
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="row">\';')
            ->write('yield \'<div class="col-md-12">\';')
            ->write('yield \'<div class="panel panel-default">\';')
            ->write('yield \'<div class="panel-heading">\';')
            ->write('yield "<h2 class=\"panel-title\">' . $this->lbl('MetaInformation') . '</h2>";')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="panel-body">\';')
            ->write('yield \'<div class="form-group">\';')
            ->write('yield \'<ul class="list-unstyled checkboxTextFieldCombo">\';')
            ->write('yield \'<li class="checkbox">\';')
            ->write('yield "<p><label for=\"metaDescriptionOverwrite\">";')
            ->write($this->getField('meta_description_overwrite'))
            ->write('yield "' . $this->lbl('Description') . '</label></p>";')
            ->write($this->getError('meta_description'))
            ->write('yield "<label for=\"metaDescription\" class=\"sr-only\">";')
            ->write('yield "' . $this->lbl('Description') . '</label>";')
            ->write($this->getField('meta_description'))
            ->write('yield "<p class=\"help-block\">' . $this->msg('HelpMetaDescription') . '</p>";')
            ->write('yield \'</li>\';')
            ->write('yield \'</ul>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="form-group">\';')
            ->write('yield \'<ul class="list-unstyled checkboxTextFieldCombo">\';')
            ->write('yield \'<li class="checkbox">\';')
            ->write('yield "<p><label for=\"metaKeywordsOverwrite\">";')
            ->write($this->getField('meta_keywords_overwrite'))
            ->write('yield "' . $this->lbl('Keywords') . '</label></p>";')
            ->write($this->getError('meta_keywords'))
            ->write('yield "<label for=\"metaKeywords\" class=\"sr-only\">";')
            ->write('yield "' . $this->lbl('Keywords') . '</label>";')
            ->write($this->getField('meta_keywords'))
            ->write('yield "<p class=\"help-block\">' . $this->msg('HelpMetaKeywords') . '</p>";')
            ->write('yield \'</li>\';')
            ->write('yield \'</ul>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="form-group last">\';')
            ->write('yield "<label for=\"metaDescriptionOverwrite\">' . $this->lbl('ExtraMetaTags') . '</label>";')
            ->write($this->getError('meta_custom'))
            ->write($this->getField('meta_custom'))
            ->write('yield "<p class=\"help-block\">' . $this->msg('HelpMetaCustom') . '</p>";')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="row">\';')
            ->write('yield \'<div class="col-md-12">\';')
            ->write('yield \'<div class="panel panel-default">\';')
            ->write('yield \'<div class="panel-heading">\';')
            ->write('yield "<h2 class=\"panel-title\">' . $this->lbl('URL') . '</h2>";')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="panel-body">\';')
            ->write('yield \'<div class="form-group">\';')
            ->write('yield \'<ul class="list-unstyled checkboxTextFieldCombo">\';')
            ->write('yield \'<li class="checkbox">\';')
            ->write('yield "<p><label for=\"urlOverwrite\">";')
            ->write($this->getField('url_overwrite'))
            ->write('yield "' . $this->lbl('URL') . '</label></p>";')
            ->write('yield \'<div class="form-inline">\';');

        $compiler
            ->write('yield \'<span id="urlFirstPart">\';')
            ->write('if (' . $this->hasVariable('detailURL') . ') {')
            ->write($this->getVariable('detailURL'))
            ->write('yield "/";')
            ->write('} else {')
            ->write('yield "' . SITE_URL . '/' . ' ' . '";')
            ->write('}')
            ->write('yield \'</span>\';');

        $compiler
            ->write($this->getError('url'))
            ->write('yield "<label for=\"url\" class=\"sr-only\">";')
            ->write('yield "' . $this->lbl('CustomURL') . '</label>";')
            ->write($this->getField('url'))
            ->write('yield \'</div>\';')
            ->write('yield "<p class=\"help-block\">' . $this->msg('HelpMetaURL') . '</p>";')
            ->write('yield \'</li>\';')
            ->write('yield \'</ul>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="form-group last">\';')
            ->write('yield \'<ul class="list-unstyled checkboxTextFieldCombo">\';')
            ->write('yield \'<li class="checkbox">\';')
            ->write('yield "<p><label for=\"canonicalUrlOverwrite\">";')
            ->write($this->getField('canonical_url_overwrite'))
            ->write('yield "' . $this->lbl('CanonicalURL') . '</label></p>";')
            ->write($this->getError('canonical_url'))
            ->write('yield "<label for=\"canonicalUrl\" class=\"sr-only\">";')
            ->write('yield "' . $this->lbl('URL') . '</label>";')
            ->write($this->getField('canonical_url'))
            ->write('yield \'</li>\';')
            ->write('yield \'</ul>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="row">\';')
            ->write('yield \'<div class="col-md-12">\';')
            ->write('yield \'<div class="panel panel-default">\';')
            ->write('yield \'<div class="panel-heading">\';')
            ->write('yield "<h2 class=\"panel-title\">' . $this->lbl('SEO') . '</h2>";')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="panel-body">\';')
            ->write('yield \'<div class="col-md-6">\';')
            ->write('yield \'<div class="form-inline">\';')
            ->write('yield \'<div class="form-group last">\';')
            ->write('yield "<p><b>' . $this->lbl('Index') . '</b></p>";');

        $compiler
            ->write('if (' . $this->hasError('seo_index') . ') {')
            ->write('yield \'<div class="alert alert-danger">\';')
            ->write($this->getError('seo_index'))
            ->write('yield \'</div>\';')
            ->write('}');

        $compiler
            ->write('yield \'<ul class="list-unstyled inputListHorizontal">\';')
            ->write($this->loopTroughField('seo_index', '$index'))
            ->write('yield \'<li class="radio">\';')
            ->write('yield "<label for=\"" . $index["id"] . "\">" . $index["rbtSeoIndex"] . $index["label"] . "</label>";')
            ->write('yield \'</li>\';')
            ->write('}')
            ->write('yield \'</ul>\';');

        $compiler
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="col-md-6">\';')
            ->write('yield \'<div class="form-inline">\';')
            ->write('yield \'<div class="form-group last">\';')
            ->write('yield "<p><b>' . $this->lbl('Follow') . '</b></p>";');

        $compiler
            ->write('if (' . $this->hasError('seo_follow') . ') {')
            ->write('yield \'<div class="alert alert-danger">\';')
            ->write($this->getError('seo_follow'))
            ->write('yield \'</div>\';')
            ->write('}');

        $compiler
            ->write('yield \'<ul class="list-unstyled inputListHorizontal">\';')
            ->write($this->loopTroughField('seo_follow', '$follow'))
            ->write('yield \'<li class="radio">\';')
            ->write('yield "<label for=\"" . $follow["id"] . "\">" . $follow["rbtSeoFollow"] . $follow["label"] . "</label>";')
            ->write('yield \'</li>\';')
            ->write('}')
            ->write('yield \'</ul>\';');

        $compiler
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')

            ->write('yield \'<div class="panel panel-default">\';')
            ->write('yield \'<div class="panel-heading">\';')
            ->write('yield "<h2 class=\"panel-title\">' . $this->lbl('Languages') . '</h2>";')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="panel-body">\';')
            ->write($this->loopTroughField('spoonHreflangFields', '$hreflangField'))
            ->write('yield \'<div class="form-group">\';')
            ->write('yield "<label for=\"hreflang" . $hreflangField["language"] . "\">" . $hreflangField["label"] . "</label>";')
            ->write('yield $hreflangField["field"];')
            ->write('yield \'</div>\';')
            ->write('}')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')

            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';');

        // Og:image
        $compiler
            ->write('yield \'<div class="row">\';')
            ->write('yield \'<div class="col-md-12">\';')
            ->write('yield \'<div class="panel panel-default">\';')
            ->write('yield \'<div class="panel-heading">\';')
            ->write('yield "<h2 class=\"panel-title\">' . $this->lbl('OgImage') . '</h2>";')
            ->write('yield \'</div>\';')
            ->write('yield \'<div class="panel-body">\';');

        $compiler
            ->write('if (' . $this->hasVariable('og_image') . ') {')
            ->write('yield \'<img class="img-responsive img-thumbnail" src="/src/Frontend/Files/Pages/images/source/\';')
            ->write($this->getVariable('og_image'))
            ->write('yield \'">\';')
            ->write('}');

        $compiler
            ->write('if (' . $this->hasField('og_image_delete') . ') {')
            ->write('yield \'<div class="form-group">\';')
            ->write('yield \'<ul class="list-unstyled checkboxTextFieldCombo">\';')
            ->write('yield \'<li class="checkbox">\';')
            ->write('yield "<p><label for=\"ogImageDelete\">";')
            ->write($this->getField('og_image_delete'))
            ->write('yield "' . $this->lbl('Delete') . '</label></p>";')
            ->write('yield \'</li>\';')
            ->write('yield \'</ul>\';')
            ->write('yield \'</div>\';')
            ->write('}');

        $compiler
            ->write('yield \'<div class="form-group last">\';')
            ->write('yield "<label for=\"ogImage\">' . $this->lbl('OgImage') . '</label>";')
            ->write($this->getError('og_image'))
            ->write($this->getField('og_image'))
            ->write('yield "<p class=\"help-block\">' . $this->msg('HelpOgImage') . '</p>";')
            ->write('yield \'</div>\';');

        $compiler
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';')
            ->write('yield \'</div>\';');
        // End og:image

        $compiler
            ->write($this->getField('meta_id'))
            ->write($this->getField('base_field_name'))
            ->write($this->getField('custom'))
            ->write($this->getField('class_name'))
            ->write($this->getField('method_name'))
            ->write($this->getField('parameters'));
    }

    private function lbl(string $label): string
    {
        return s(BackendLanguage::getLabel($label))->title()->toString();
    }

    private function msg(string $message): string
    {
        return BackendLanguage::getMessage($message);
    }

    private function hasVariable(string $variable): string
    {
        return "isset(\$context['{$variable}']) && !empty(\$context['{$variable}'])";
    }

    private function loopTroughField(string $variable, string $as): string
    {
        return "foreach (\$context['{$variable}'] as {$as}) {";
    }

    private function getVariable(string $variable): string
    {
        return "yield \$context['{$variable}'];";
    }

    private function getField(string $fieldName): string
    {
        $form = "\$context['form_{$this->form}']";

        return 'yield ' . $form . "->getField('" . $fieldName . "')->parse();";
    }

    private function hasField(string $fieldName): string
    {
        $form = "\$context['form_{$this->form}']";

        return $form . "->existsField('" . $fieldName . "')";
    }

    private function hasError(string $fieldName): string
    {
        return "\$context['form_{$this->form}']->getField('" . $fieldName . "')->getErrors() ";
    }

    private function getError(string $fieldName): string
    {
        return "yield \$context['form_{$this->form}']->getField('" . $fieldName . "')->getErrors() "
            . "? '<span class=\"formError\">' "
            . ". \$context['form_{$this->form}']->getField('" . $fieldName . "')->getErrors() "
            . ". '</span>' : '';";
    }
}
