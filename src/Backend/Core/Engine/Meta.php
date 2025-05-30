<?php

namespace Backend\Core\Engine;

use Common\Doctrine\Entity\Meta as MetaEntity;
use Common\Uri as CommonUri;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Language\Language as BackendLanguage;
use ForkCMS\Utility\Thumbnails;
use SpoonFormCheckbox;

/**
 * This class represents a META-object
 *
 * @deprecated This class will be removed when all modules run on doctrine and will be replaced with the meta entity
 */
class Meta
{
    /**
     * The name of the field we should use to generate default-values
     *
     * @var string
     */
    protected $baseFieldName;

    /**
     * The callback method
     *
     * @var array
     */
    protected $callback = [];

    /**
     * The hreflang callback method
     *
     * @var array
     */
    protected $hreflangCallback = [];

    /**
     * Do we need meta custom
     *
     * @var bool
     */
    protected $custom;

    /**
     * The data, when a existing meta-record is loaded
     *
     * @var array
     */
    protected $data;

    /**
     * The form instance
     *
     * @var Form
     */
    protected $form;

    /**
     * The id, when an existing meta-record is loaded
     *
     * @var int
     */
    protected $id;

    /**
     * The URL-instance
     *
     * @var Url
     */
    protected $url;

    /**
     * @var array<string, array<string, string>>
     */
    private $hreflangFields = [];

    /**
     * @param Form $form An instance of Form, the elements will be parsed in here.
     * @param int $metaId The metaID to load.
     * @param string $baseFieldName The field where the URL should be based on.
     * @param bool $showCustomMeta Add/show custom-meta.
     *
     * @throws Exception
     */
    public function __construct(
        Form $form,
        int $metaId = null,
        string $baseFieldName = 'title',
        bool $showCustomMeta = false
    ) {
        // check if URL is available from the reference
        if (!BackendModel::getContainer()->has('url')) {
            throw new Exception('url service should be available in the container.');
        }

        // get BackendURL instance
        $this->url = BackendModel::getContainer()->get('url');

        $this->custom = $showCustomMeta;
        $this->form = $form;
        $this->baseFieldName = $baseFieldName;

        // metaId was specified, so we should load the item
        if ($metaId !== null) {
            $this->loadMeta($metaId);
        }

        // set default callback
        $this->setUrlCallback(
            'Backend\\Modules\\' . $this->url->getModule() . '\\Engine\\Model',
            'getUrl'
        );

        // load the form
        $this->loadForm();
    }

    /**
     * Generate an url, using the predefined callback.
     *
     * @param string $url The base-url to start from.
     *
     * @throws Exception When the function does not exist
     *
     * @return string
     *
     * @deprecated use the generateUrl method on the meta repository
     * This class will be removed when all modules run on doctrine
     */
    public function generateUrl(string $url): string
    {
        return Model::get('fork.repository.meta')->generateUrl(
            $url,
            $this->callback['class'],
            $this->callback['method'],
            $this->callback['parameters']
        );
    }

    public function getDescription(): ?string
    {
        // not set so return null
        if (!isset($this->data['description'])) {
            return null;
        }

        // return value
        return $this->data['description'];
    }

    public function getDescriptionOverwrite(): ?bool
    {
        // not set so return null
        if (!isset($this->data['description_overwrite'])) {
            return null;
        }

        // return value
        return (bool) $this->data['description_overwrite'];
    }

    public function getId(): ?int
    {
        // not set so return null
        if (!isset($this->data['id'])) {
            return null;
        }

        // return value
        return (int) $this->data['id'];
    }

    public function getKeywords(): ?string
    {
        // not set so return null
        if (!isset($this->data['keywords'])) {
            return null;
        }

        // return value
        return $this->data['keywords'];
    }

    public function getKeywordsOverwrite(): ?bool
    {
        // not set so return null
        if (!isset($this->data['keywords_overwrite'])) {
            return null;
        }

        // return value
        return (bool) $this->data['keywords_overwrite'];
    }

    public function getTitle(): ?string
    {
        // not set so return null
        if (!isset($this->data['title'])) {
            return null;
        }

        // return value
        return $this->data['title'];
    }

    public function getTitleOverwrite(): ?bool
    {
        // not set so return null
        if (!isset($this->data['title_overwrite'])) {
            return null;
        }

        // return value
        return (bool) $this->data['title_overwrite'];
    }

    public function getUrl(): ?string
    {
        // not set so return null
        if (!isset($this->data['url'])) {
            return null;
        }

        // return value
        return urldecode($this->data['url']);
    }

    public function getUrlOverwrite(): ?bool
    {
        // not set so return null
        if (!isset($this->data['url_overwrite'])) {
            return null;
        }

        // return value
        return (bool) $this->data['url_overwrite'];
    }

    public function getCanonicalUrl(): ?string
    {
        if (!isset($this->data['data']) || !is_array($this->data['data'])) {
            return null;
        }

        // not set so return null
        if (!array_key_exists('canonical_url', $this->data['data'])) {
            return null;
        }

        // return value
        return urldecode($this->data['data']['canonical_url']);
    }

    public function getCanonicalUrlOverwrite(): bool
    {
        if (!isset($this->data['data']) || !is_array($this->data['data'])) {
            return false;
        }

        // not set so return null
        if (!array_key_exists('canonical_url_overwrite', $this->data['data'])) {
            return false;
        }

        // return value
        return (bool) $this->data['data']['canonical_url_overwrite'];
    }

    public function getOgImage(): ?string
    {
        return $this->data['og_image'] ?? null;
    }

    /**
     * If the fields are disabled we don't have any values in the post.
     * When an error occurs in the other fields of the form the meta-fields would be cleared
     * therefore we alter the POST so it contains the initial values.
     */
    private function loadValuesOfDisabledFields(): void
    {
        if (!isset($_POST['page_title'])) {
            $_POST['page_title'] = $this->data['title'] ?? null;
        }
        if (!isset($_POST['meta_description'])) {
            $_POST['meta_description'] = $this->data['description'] ?? null;
        }
        if (!isset($_POST['meta_keywords'])) {
            $_POST['meta_keywords'] = $this->data['keywords'] ?? null;
        }
        if (!isset($_POST['url'])) {
            $_POST['url'] = $this->data['url'] ?? null;
        }
        if (!isset($_POST['canonical_url'])) {
            $_POST['canonical_url'] = $this->data['canonical_url'] ?? null;
        }
        if ($this->custom && !isset($_POST['meta_custom'])) {
            $_POST['meta_custom'] = $this->data['custom'] ?? null;
        }
        if (!isset($_POST['seo_index'])) {
            $_POST['seo_index'] = $this->data['seo_index'] ?? 'none';
        }
        if (!isset($_POST['seo_follow'])) {
            $_POST['seo_follow'] = $this->data['seo_follow'] ?? 'none';
        }
    }

    /**
     * Add all element into the form
     */
    protected function loadForm(): void
    {
        // is the form submitted?
        if ($this->form->isSubmitted()) {
            $this->loadValuesOfDisabledFields();
        }

        // add page title elements into the form
        $this->form->addCheckbox(
            'page_title_overwrite',
            isset($this->data['title_overwrite']) && $this->data['title_overwrite']
        );
        $this->form->addText('page_title', $this->data['title'] ?? null);

        // add meta description elements into the form
        $this->form->addCheckbox(
            'meta_description_overwrite',
            isset($this->data['description_overwrite']) && $this->data['description_overwrite']
        );
        $this->form->addText(
            'meta_description',
            $this->data['description'] ?? null
        );

        // add meta keywords elements into the form
        $this->form->addCheckbox(
            'meta_keywords_overwrite',
            isset($this->data['keywords_overwrite']) && $this->data['keywords_overwrite']
        );
        $this->form->addText('meta_keywords', $this->data['keywords'] ?? null);

        // add URL elements into the form
        $this->form->addCheckbox(
            'url_overwrite',
            isset($this->data['url_overwrite']) && $this->data['url_overwrite']
        );
        $this->form->addText('url', isset($this->data['url']) ? urldecode($this->data['url']) : null);

        // add canonical URL elements into the form
        $this->form->addCheckbox(
            'canonical_url_overwrite',
            $this->getCanonicalUrlOverwrite()
        );
        $this->form->addText(
            'canonical_url',
            $this->getCanonicalUrl()
        );

        // advanced SEO
        $indexValues = [
            ['value' => 'none', 'label' => BackendLanguage::getLabel('None')],
            ['value' => 'index', 'label' => 'index'],
            ['value' => 'noindex', 'label' => 'noindex'],
        ];
        $this->form->addRadiobutton(
            'seo_index',
            $indexValues,
            $this->data['seo_index'] ?? 'none'
        );
        $followValues = [
            ['value' => 'none', 'label' => BackendLanguage::getLabel('None')],
            ['value' => 'follow', 'label' => 'follow'],
            ['value' => 'nofollow', 'label' => 'nofollow'],
        ];
        $this->form->addRadiobutton(
            'seo_follow',
            $followValues,
            $this->data['seo_follow'] ?? 'none'
        );

        // should we add the meta-custom field
        if ($this->custom) {
            // add meta custom element into the form
            $this->form->addTextarea('meta_custom', $this->data['custom'] ?? null);
        }

        // Og:image
        $this->form->addImage('og_image');
        if ($this->data['og_image'] ?? null) {
            $this->form->addCheckbox('og_image_delete');
        }

        $this->form->addHidden('meta_id', $this->id);
        $this->form->addHidden('base_field_name', $this->baseFieldName);
        $this->form->addHidden('custom', $this->custom);
        $this->form->addHidden('class_name', $this->callback['class']);
        $this->form->addHidden('method_name', $this->callback['method']);
        $this->form->addHidden('parameters', \SpoonFilter::htmlspecialchars(serialize($this->callback['parameters'])));

        if (!empty($this->hreflangCallback)) {
            foreach (BackendLanguage::getActiveLanguages() as $language) {
                if ($language !== BackendLanguage::getWorkingLanguage()) {
                    $values = $this->getHreflangValues($language);
                    $field = $this->form->addDropdown(
                        'hreflang_' . $language,
                        $values,
                        $this->data['data']['hreflang_' . $language] ?? null,
                    );
                    $field->setDefaultElement('');
                    $this->hreflangFields[$language] = [
                        'language' => $language,
                        'field' => $field->parse(),
                        'label' => strtoupper($language),
                    ];
                }
            }
        }
    }

    /**
     * Load a specific meta-record
     *
     * @param int $id The id of the record to load.
     *
     * @throws Exception If no meta-record exists with the provided id
     */
    protected function loadMeta(int $id): void
    {
        $this->id = $id;

        $this->data = (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT *
             FROM meta AS m
             WHERE m.id = ?',
            [$this->id]
        );

        // validate meta-record
        if (empty($this->data)) {
            throw new Exception('Meta-record doesn\'t exist.');
        }

        // unserialize data
        if (isset($this->data['data'])) {
            $this->data['data'] = @unserialize($this->data['data'], ['allowed_classes' => false]);
        }
    }

    /**
     * Saves the meta object
     *
     * @param bool $update Should we update the record or insert a new one.
     *
     * @throws Exception If no meta id was provided.
     *
     * @return int
     *
     * @deprecated just use the entity for doctrine.
     *             This class will be removed when all modules run on doctrine and will be replaced with the meta entity
     */
    public function save(bool $update = false): int
    {
        $this->validate();

        $this->data['og_image'] = $this->handleOgImage();

        //serialize data for save
        if (!empty($this->data['data'])) {
            $this->data['data'] = serialize($this->data['data']);
        }

        if (empty($this->data['data'])) {
            $this->data['data'] = null;
        }

        // build meta
        $database = BackendModel::getContainer()->get('database');

        if ($this->id !== null && $update === true) {
            $database->update('meta', $this->data, 'id = ?', [$this->id]);

            return $this->id;
        }

        unset($this->data['id']);

        return (int) $database->insert('meta', $this->data);
    }

    /**
     * Set the callback to calculate an unique URL
     * REMARK: this method has to be public and static
     * REMARK: if you specify arguments they will be appended
     *
     * @param string $className Name of the class to use.
     * @param string $methodName Name of the method to use.
     * @param array $parameters Parameters to parse, they will be passed after ours.
     */
    public function setUrlCallback(string $className, string $methodName, array $parameters = []): void
    {
        // store in property
        $this->callback = ['class' => $className, 'method' => $methodName, 'parameters' => $parameters];

        // re-load the form
        $this->loadForm();
    }

    public function setHreflangCallback(string $className, string $methodName, array $parameters = []): void
    {
        $this->hreflangCallback = [
            'class' => $className,
            'method' => $methodName,
            'parameters' => $parameters,
        ];

        // re-load the form
        $this->loadForm();
    }

    /**
     * Validates the form
     * It checks if there is a value when a checkbox is checked
     */
    public function validate(): void
    {
        // page title overwrite is checked
        if ($this->form->getField('page_title_overwrite')->isChecked()) {
            $this->form->getField('page_title')->isFilled(BackendLanguage::err('FieldIsRequired'));
        }

        // meta description overwrite is checked
        if ($this->form->getField('meta_description_overwrite')->isChecked()) {
            $this->form->getField('meta_description')->isFilled(BackendLanguage::err('FieldIsRequired'));
        }

        // meta keywords overwrite is checked
        if ($this->form->getField('meta_keywords_overwrite')->isChecked()) {
            $this->form->getField('meta_keywords')->isFilled(BackendLanguage::err('FieldIsRequired'));
        }

        // URL overwrite is checked
        if ($this->form->getField('url_overwrite')->isChecked()) {
            $this->form->getField('url')->isFilled(BackendLanguage::err('FieldIsRequired'));
            $url = \SpoonFilter::htmlspecialcharsDecode($this->form->getField('url')->getValue());
            $generatedUrl = $this->generateUrl($url);

            // check if urls are different
            if (CommonUri::getUrl($url) !== $generatedUrl) {
                $this->form->getField('url')->addError(
                    BackendLanguage::err('URLAlreadyExists')
                );
            }
        }

        // Canonical URL overwrite is checked
        if ($this->form->getField('canonical_url_overwrite')->isChecked()) {
            $this->form->getField('canonical_url')->isFilled(BackendLanguage::err('FieldIsRequired'));
        }

        // if the form was submitted correctly the data array should be populated
        if (!$this->form->isCorrect()) {
            return;
        }

        $this->data['keywords'] = $this->form->getField('meta_keywords_overwrite')->getActualValue(
            $this->form->getField('meta_keywords')->getValue(),
            $this->form->getField($this->baseFieldName)->getValue()
        );
        $this->data['keywords_overwrite'] = $this->form->getField('meta_keywords_overwrite')->isChecked();
        $this->data['description'] = $this->form->getField('meta_description_overwrite')->getActualValue(
            $this->form->getField('meta_description')->getValue(),
            $this->form->getField($this->baseFieldName)->getValue()
        );
        $this->data['description_overwrite'] = $this->form->getField('meta_description_overwrite')->isChecked();
        $this->data['title'] = $this->form->getField('page_title_overwrite')->getActualValue(
            $this->form->getField('page_title')->getValue(),
            $this->form->getField($this->baseFieldName)->getValue()
        );
        $this->data['title_overwrite'] = $this->form->getField('page_title_overwrite')->isChecked();
        $this->data['url'] = $this->generateUrl(
            $this->form->getField('url_overwrite')->getActualValue(
                \SpoonFilter::htmlspecialcharsDecode($this->form->getField('url')->getValue()),
                \SpoonFilter::htmlspecialcharsDecode($this->form->getField($this->baseFieldName)->getValue())
            )
        );
        $this->data['url_overwrite'] = $this->form->getField('url_overwrite')->isChecked();

        if ($this->form->getField('canonical_url_overwrite')->isChecked()) {
            $this->data['data']['canonical_url'] = $this->form->getField('canonical_url_overwrite')->getActualValue(
                \SpoonFilter::htmlspecialcharsDecode($this->form->getField('canonical_url')->getValue()),
                null
            );
            $this->data['data']['canonical_url_overwrite'] = true;
        } else {
            unset($this->data['data']['canonical_url']);
            unset($this->data['data']['canonical_url_overwrite']);
        }

        if (!empty($this->hreflangCallback)) {
            foreach (BackendLanguage::getActiveLanguages() as $language) {
                if ($language !== BackendLanguage::getWorkingLanguage()) {
                    $field = $this->form->getField('hreflang_' . $language);
                    if ($field->isFilled()) {
                        $this->data['data']['hreflang_' . $language] = $field->getValue();
                    }
                }
            }
        }

        $this->data['custom'] = $this->custom && $this->form->getField('meta_custom')->isFilled()
            ? $this->form->getField('meta_custom')->getValue() : null;
        $this->data['seo_index'] = $this->form->getField('seo_index')->getValue();
        $this->data['seo_follow'] = $this->form->getField('seo_follow')->getValue();

        if ($this->data['seo_index'] === 'none') {
            unset($this->data['seo_index']);
        }
        if ($this->data['seo_follow'] === 'none') {
            unset($this->data['seo_follow']);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMetaEntity(): MetaEntity
    {
        $this->validate();

        return MetaEntity::fromBackendMeta($this);
    }

    private function handleOgImage(): ?string
    {
        $imagePath = FRONTEND_FILES_PATH . '/Pages/images';
        /** @var Thumbnails $thumbnails */
        $thumbnails = BackendModel::getContainer()->get(Thumbnails::class);

        if ($this->form->existsField('og_image_delete')) {
            /** @var SpoonFormCheckbox $deleteField */
            $deleteField = $this->form->getField('og_image_delete');
            if ($deleteField->isChecked()) {
                $thumbnails->delete($imagePath, $this->data['og_image']);
                $this->data['og_image'] = null;
            }
        }

        /** @var FormImage $imageField */
        $imageField = $this->form->getField('og_image');
        if (!$imageField->isFilled()) {
            return $this->data['og_image'] ?? null;
        }

        $thumbnails->delete($imagePath, $this->data['og_image']);

        $extension = $imageField->getExtension();
        $filename = $this->data['title'] . '_og_image_' . time() . '.' . $extension;

        $imageFullPath = $imagePath . '/source/' . $filename;
        $imageField->moveFile($imageFullPath);
        $thumbnails->generate($imagePath, $imageFullPath);

        return $filename;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getHreflangFields(): array
    {
        return $this->hreflangFields;
    }

    /**
     * @param string $language
     *
     * @return array<string, string>
     */
    private function getHreflangValues(string $language): array
    {
        $class = $this->hreflangCallback['class'];
        $method = $this->hreflangCallback['method'];
        $parameters = $this->hreflangCallback['parameters'] ?? [];

        // check if the class is a service
        if (Model::getContainer()->has($class)) {
            $class = Model::getContainer()->get($class);
        }

        // validate (check if the function exists)
        if (!is_callable([$class, $method])) {
            throw new Exception('The hreflang callback-method doesn\'t exist.');
        }

        array_unshift($parameters, $language);

        return call_user_func_array([$class, $method], $parameters);
    }
}
