<?php

namespace Backend\Modules\Settings\Actions;

use ForkCMS\Privacy\ConsentDialog;
use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Extensions\Engine\Model as BackendExtensionsModel;
use Backend\Modules\Settings\Engine\Model as BackendSettingsModel;
use function Symfony\Component\String\s;

/**
 * This is the index-action (default), it will display the setting-overview
 */
class Index extends BackendBaseActionIndex
{
    /**
     * The form instance
     *
     * @var BackendForm
     */
    private $form;

    /**
     * Should we show boxes for their API keys
     *
     * @var bool
     */
    private $needsGoogleMaps;
    private $needsGoogleRecaptcha;

    public function execute(): void
    {
        parent::execute();

        // get some data
        $modulesThatRequireGoogleMaps = BackendExtensionsModel::getModulesThatRequireGoogleMaps();
        $modulesThatRequireGoogleRecaptcha = BackendExtensionsModel::getModulesThatRequireGoogleRecaptcha();

        // set properties
        $this->needsGoogleMaps = (!empty($modulesThatRequireGoogleMaps));
        $this->needsGoogleRecaptcha = !empty($modulesThatRequireGoogleRecaptcha);

        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    private function loadForm(): void
    {
        // list of default domains
        $defaultDomains = [str_replace(['http://', 'www.', 'https://'], '', SITE_URL)];

        // create form
        $this->form = new BackendForm('settingsIndex');

        // general settings
        $this->form->addText(
            'site_title',
            $this->get('fork.settings')->get('Core', 'site_title_' . BL::getWorkingLanguage(), SITE_DEFAULT_TITLE)
        );

        // Google tracking settings
        $googleTrackingAnalyticsTrackingId = $this->get('fork.settings')->get(
            'Core',
            'google_tracking_google_analytics_tracking_id',
            ''
        );
        $this->form->addCheckbox(
            'google_tracking_google_analytics_tracking_id_enabled',
            ($googleTrackingAnalyticsTrackingId !== '')
        );
        $googleTrackingAnalyticsTrackingIdField = $this->form->addText(
            'google_tracking_google_analytics_tracking_id',
            $googleTrackingAnalyticsTrackingId
        );
        if ($googleTrackingAnalyticsTrackingId === '') {
            $googleTrackingAnalyticsTrackingIdField->setAttribute('disabled', 'disabled');
        }

        $googleTrackingTagManagerContainerId = $this->get('fork.settings')->get(
            'Core',
            'google_tracking_google_tag_manager_container_id',
            ''
        );
        $this->form->addCheckbox(
            'google_tracking_google_tag_manager_container_id_enabled',
            ($googleTrackingTagManagerContainerId !== '')
        );
        $googleTrackingTagManagerContainerIdField = $this->form->addText(
            'google_tracking_google_tag_manager_container_id',
            $googleTrackingTagManagerContainerId
        );
        if ($googleTrackingTagManagerContainerId === '') {
            $googleTrackingTagManagerContainerIdField->setAttribute('disabled', 'disabled');
        }

        $siteHtmlHeadValue = $this->get('fork.settings')->get(
            'Core',
            'site_html_head',
        );
        $this->form->addTextarea(
            'site_html_head',
            $siteHtmlHeadValue,
            'form-control code',
            'form-control danger code',
            true
        );
        $siteHtmlStartOfBodyValue = $this->get('fork.settings')->get(
            'Core',
            'site_html_start_of_body'
        );
        $this->form->addTextarea(
            'site_html_start_of_body',
            $siteHtmlStartOfBodyValue,
            'form-control code',
            'form-control danger code',
            true
        );
        $siteHtmlEndOfBodyValue = $this->get('fork.settings')->get(
            'Core',
            'site_html_end_of_body'
        );
        $this->form->addTextarea(
            'site_html_end_of_body',
            $siteHtmlEndOfBodyValue,
            'form-control code',
            'form-control danger code',
            true
        );

        // ckfinder
        $this->form->addText(
            'ckfinder_license_name',
            $this->get('fork.settings')->get('Core', 'ckfinder_license_name', null)
        );
        $this->form->addText(
            'ckfinder_license_key',
            $this->get('fork.settings')->get('Core', 'ckfinder_license_key', null)
        );
        $this->form->addText(
            'ckfinder_image_max_width',
            $this->get('fork.settings')->get('Core', 'ckfinder_image_max_width', 1600)
        );
        $this->form->addText(
            'ckfinder_image_max_height',
            $this->get('fork.settings')->get('Core', 'ckfinder_image_max_height', 1200)
        );

        // date & time formats
        $this->form->addDropdown(
            'time_format',
            BackendModel::getTimeFormats(),
            $this->get('fork.settings')->get('Core', 'time_format')
        );
        $this->form->addDropdown(
            'date_format_short',
            BackendModel::getDateFormatsShort(),
            $this->get('fork.settings')->get('Core', 'date_format_short')
        );
        $this->form->addDropdown(
            'date_format_long',
            BackendModel::getDateFormatsLong(),
            $this->get('fork.settings')->get('Core', 'date_format_long')
        );

        // number formats
        $this->form->addDropdown(
            'number_format',
            BackendModel::getNumberFormats(),
            $this->get('fork.settings')->get('Core', 'number_format')
        );

        // google recaptcha
        $googleRecaptchaVersions = [
            [
                'value' => 'v2invisible',
                'label' => s(BL::lbl('GoogleRecaptchaV2Invisible'))->title()->toString(),
            ],
            [
                'value' => 'v3',
                'label' => s(BL::lbl('GoogleRecaptchaV3'))->title()->toString(),
            ],
        ];
        $this->form->addRadiobutton(
            'google_recaptcha_version',
            $googleRecaptchaVersions,
            $this->get('fork.settings')->get('Core', 'google_recaptcha_version', 'v2invisible')
        );

        $activeLanguages = [];
        $redirectLanguages = [];

        // create a list of the languages
        foreach ($this->get('fork.settings')->get('Core', 'languages', ['en']) as $abbreviation) {
            // is this the default language
            $defaultLanguage = $abbreviation === SITE_DEFAULT_LANGUAGE;

            // attributes
            $activeAttributes = [];
            $activeAttributes['id'] = 'active_language_' . $abbreviation;
            $redirectAttributes = [];
            $redirectAttributes['id'] = 'redirect_language_' . $abbreviation;

            // fetch label
            $label = BL::lbl(mb_strtoupper((string) $abbreviation), 'Core');

            // default may not be unselected
            if ($defaultLanguage) {
                // add to attributes
                $activeAttributes['disabled'] = 'disabled';
                $redirectAttributes['disabled'] = 'disabled';

                // overrule in $_POST
                if (!isset($_POST['active_languages']) || !is_array($_POST['active_languages'])) {
                    $_POST['active_languages'] = [SITE_DEFAULT_LANGUAGE];
                } elseif (!in_array(
                    $abbreviation,
                    $_POST['active_languages']
                )
                ) {
                    $_POST['active_languages'][] = $abbreviation;
                }
                if (!isset($_POST['redirect_languages']) || !is_array($_POST['redirect_languages'])) {
                    $_POST['redirect_languages'] = [SITE_DEFAULT_LANGUAGE];
                } elseif (!in_array(
                    $abbreviation,
                    $_POST['redirect_languages']
                )
                ) {
                    $_POST['redirect_languages'][] = $abbreviation;
                }
            }

            // add to the list
            $activeLanguages[] = [
                'label' => $label,
                'value' => $abbreviation,
                'attributes' => $activeAttributes,
                'variables' => ['default' => $defaultLanguage],
            ];
            $redirectLanguages[] = [
                'label' => $label,
                'value' => $abbreviation,
                'attributes' => $redirectAttributes,
                'variables' => ['default' => $defaultLanguage],
            ];
        }

        $hasMultipleLanguages = BackendModel::getContainer()->getParameter('site.multilanguage');

        // create multilanguage checkbox
        $this->form->addMultiCheckbox(
            'active_languages',
            $activeLanguages,
            $this->get('fork.settings')->get('Core', 'active_languages', [$hasMultipleLanguages])
        );
        $this->form->addMultiCheckbox(
            'redirect_languages',
            $redirectLanguages,
            $this->get('fork.settings')->get('Core', 'redirect_languages', [$hasMultipleLanguages])
        );

        // api keys are not required for every module
        if ($this->needsGoogleMaps) {
            $this->form->addText(
                'google_maps_key',
                $this->get('fork.settings')->get('Core', 'google_maps_key', null)
            );
        }
        if ($this->needsGoogleRecaptcha) {
            $this->form->addText(
                'google_recaptcha_site_key',
                $this->get('fork.settings')->get('Core', 'google_recaptcha_site_key', null)
            );
            $this->form->addText(
                'google_recaptcha_secret_key',
                $this->get('fork.settings')->get('Core', 'google_recaptcha_secret_key', null)
            );
        }

        // privacy
        $this->form->addCheckbox(
            'show_consent_dialog',
            $this->get('fork.settings')->get('Core', 'show_consent_dialog', false)
        );

        foreach (ConsentDialog::getConsentLevels() as $level) {
            $checkbox = $this->form->addCheckbox(
                'privacy_consent_level_' . $level,
                $this->get('fork.settings')->get(
                    'Core',
                    'privacy_consent_level_' . $level,
                    ($level === ConsentDialog::CONSENT_FUNCTIONALITY_STORAGE)
                )
            );

            if ($level === ConsentDialog::CONSENT_FUNCTIONALITY_STORAGE) {
                $checkbox->setAttribute('disabled', 'disabled');
            }
        }
        $this->template->assign('privacy_consent_levels', ConsentDialog::getConsentLevels());
    }

    protected function parse(): void
    {
        parent::parse();

        // show options
        if ($this->needsGoogleMaps) {
            $this->template->assign('needsGoogleMaps', true);
        }
        if ($this->needsGoogleRecaptcha) {
            $this->template->assign('needsGoogleRecaptcha', true);
        }

        // parse the form
        $this->form->parse($this->template);

        // parse the warnings
        $this->parseWarnings();
    }

    /**
     * Show the warnings based on the active modules & configured settings
     */
    private function parseWarnings(): void
    {
        // get warnings
        $warnings = BackendSettingsModel::getWarnings();

        // assign warnings
        $this->template->assign('warnings', $warnings);
    }

    private function validateForm(): void
    {
        // is the form submitted?
        if ($this->form->isSubmitted()) {
            // validate required fields
            $this->form->getField('site_title')->isFilled(BL::err('FieldIsRequired'));

            // Google Tracking options
            if ($this->form->getField('google_tracking_google_analytics_tracking_id_enabled')->getChecked()) {
                $this->form->getField('google_tracking_google_analytics_tracking_id')->isFilled(
                    BL::err('FieldIsRequired')
                );
            }
            if ($this->form->getField('google_tracking_google_tag_manager_container_id_enabled')->getChecked()) {
                $this->form->getField('google_tracking_google_tag_manager_container_id')->isFilled(
                    BL::err('FieldIsRequired')
                );
            }

            // date & time
            $this->form->getField('time_format')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('date_format_short')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('date_format_long')->isFilled(BL::err('FieldIsRequired'));

            // number
            $this->form->getField('number_format')->isFilled(BL::err('FieldIsRequired'));

            if ($this->form->getField('ckfinder_image_max_width')->isFilled()) {
                $this->form->getField(
                    'ckfinder_image_max_width'
                )->isInteger(BL::err('InvalidInteger'));
            }
            if ($this->form->getField('ckfinder_image_max_height')->isFilled()) {
                $this->form->getField(
                    'ckfinder_image_max_height'
                )->isInteger(BL::err('InvalidInteger'));
            }

            // no errors ?
            if ($this->form->isCorrect()) {
                // general settings
                $this->get('fork.settings')->set(
                    'Core',
                    'site_title_' . BL::getWorkingLanguage(),
                    $this->form->getField('site_title')->getValue()
                );

                if ($this->form->getField('google_tracking_google_analytics_tracking_id_enabled')->isChecked()) {
                    $googleTrackingAnalyticsTrackingId = $this->form->getField('google_tracking_google_analytics_tracking_id')->getValue();
                } else {
                    $googleTrackingAnalyticsTrackingId = '';
                }
                $this->get('fork.settings')->set(
                    'Core',
                    'google_tracking_google_analytics_tracking_id',
                    $googleTrackingAnalyticsTrackingId
                );

                if ($this->form->getField('google_tracking_google_tag_manager_container_id_enabled')->isChecked()) {
                    $googleTrackingTagManagerContainerId = $this->form->getField('google_tracking_google_tag_manager_container_id')->getValue();
                } else {
                    $googleTrackingTagManagerContainerId = '';
                }
                $this->get('fork.settings')->set(
                    'Core',
                    'google_tracking_google_tag_manager_container_id',
                    $googleTrackingTagManagerContainerId
                );

                $this->get('fork.settings')->set(
                    'Core',
                    'site_html_head',
                    $this->form->getField('site_html_head')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'site_html_start_of_body',
                    $this->form->getField('site_html_start_of_body')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'site_html_start_of_body',
                    $this->form->getField('site_html_start_of_body')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'site_html_end_of_body',
                    $this->form->getField('site_html_end_of_body')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'site_html_end_of_body',
                    $this->form->getField('site_html_end_of_body')->getValue()
                );

                // google recaptcha settings
                $this->get('fork.settings')->set(
                    'Core',
                    'google_recaptcha_version',
                    $this->form->getField('google_recaptcha_version')->getValue()
                );

                // ckfinder settings
                $this->get('fork.settings')->set(
                    'Core',
                    'ckfinder_license_name',
                    ($this->form->getField('ckfinder_license_name')->isFilled()) ? $this->form->getField(
                        'ckfinder_license_name'
                    )->getValue() : null
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'ckfinder_license_key',
                    ($this->form->getField('ckfinder_license_key')->isFilled()) ? $this->form->getField(
                        'ckfinder_license_key'
                    )->getValue() : null
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'ckfinder_image_max_width',
                    ($this->form->getField('ckfinder_image_max_width')->isFilled()) ? $this->form->getField(
                        'ckfinder_image_max_width'
                    )->getValue() : 1600
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'ckfinder_image_max_height',
                    ($this->form->getField('ckfinder_image_max_height')->isFilled()) ? $this->form->getField(
                        'ckfinder_image_max_height'
                    )->getValue() : 1200
                );

                // api keys
                if ($this->needsGoogleMaps) {
                    $this->get('fork.settings')->set(
                        'Core',
                        'google_maps_key',
                        $this->form->getField('google_maps_key')->getValue()
                    );
                }
                if ($this->needsGoogleRecaptcha) {
                    $this->get('fork.settings')->set(
                        'Core',
                        'google_recaptcha_site_key',
                        $this->form->getField('google_recaptcha_site_key')->getValue()
                    );
                    $this->get('fork.settings')->set(
                        'Core',
                        'google_recaptcha_secret_key',
                        $this->form->getField('google_recaptcha_secret_key')->getValue()
                    );
                }

                // date & time formats
                $this->get('fork.settings')->set(
                    'Core',
                    'time_format',
                    $this->form->getField('time_format')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'date_format_short',
                    $this->form->getField('date_format_short')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'date_format_long',
                    $this->form->getField('date_format_long')->getValue()
                );

                // date & time formats
                $this->get('fork.settings')->set(
                    'Core',
                    'number_format',
                    $this->form->getField('number_format')->getValue()
                );

                // before we save the languages, we need to ensure that each language actually exists and may be chosen.
                $languages = [SITE_DEFAULT_LANGUAGE];
                $activeLanguages = array_unique(
                    array_merge($languages, $this->form->getField('active_languages')->getValue())
                );
                $redirectLanguages = array_unique(
                    array_merge($languages, $this->form->getField('redirect_languages')->getValue())
                );

                // cleanup redirect-languages, by removing the values that aren't present in the active languages
                $redirectLanguages = array_intersect($redirectLanguages, $activeLanguages);

                // save active languages
                $this->get('fork.settings')->set('Core', 'active_languages', $activeLanguages);
                $this->get('fork.settings')->set('Core', 'redirect_languages', $redirectLanguages);

                // privacy
                $this->get('fork.settings')->set(
                    'Core',
                    'show_consent_dialog',
                    $this->form->getField('show_consent_dialog')->getChecked()
                );

                foreach (ConsentDialog::getConsentLevels() as $level) {
                    $value = $this->form->getField('privacy_consent_level_' . $level)->getChecked();
                    if ($level === ConsentDialog::CONSENT_FUNCTIONALITY_STORAGE) {
                        $value = true;
                    }
                    $this->get('fork.settings')->set(
                        'Core',
                        'privacy_consent_level_' . $level,
                        $value
                    );
                }

                // assign report
                $this->template->assign('report', true);
                $this->template->assign('reportMessage', BL::msg('Saved'));
            }
        }
    }
}
