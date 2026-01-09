<?php

namespace ForkCMS\Google\TagManager;

use Common\ModulesSettings;
use ForkCMS\Privacy\ConsentDialog;

class TagManager
{
    public function __construct(
        private readonly ModulesSettings $modulesSettings,
        private readonly DataLayer $dataLayer,
        private readonly ConsentDialog $consentDialog
    ) {
    }

    private function shouldAddCode(): bool
    {
        $googleAnalyticsTrackingId = $this->modulesSettings->get(
            'Core',
            'google_tracking_google_tag_manager_container_id',
            ''
        );

        return ($googleAnalyticsTrackingId !== '');
    }

    public function generateHeadCode(): string
    {
        if (!$this->shouldAddCode()) {
            return '';
        }

        $codeLines = [];

        if ($this->modulesSettings->get('Core', 'show_consent_dialog', false)) {
            // add default consent
            $codeLines = array_merge(
                $codeLines,
                [
                    '<!-- Set default consent -->',
                    '<script>',
                    'window.dataLayer = window.dataLayer || [];',
                    'function gtag() { dataLayer.push(arguments); }',
                    '',
                    'gtag(\'consent\', \'default\', {',
                ]
            );

            foreach (ConsentDialog::getConsentLevels() as $level) {
                $codeLines[] = sprintf(
                    '  \'%1$s\': \'%2$s\',',
                    $level,
                    ($level === ConsentDialog::CONSENT_FUNCTIONALITY_STORAGE) ? 'granted' : 'denied'
                );
            }

            $codeLines = array_merge(
                $codeLines,
                [
                    '});',
                    '</script>',
                    '<!-- End default consent -->',
                    '',
                ]
            );
        }

        // add Google Tag Manager
        $codeLines = array_merge(
            $codeLines,
            [
                '<!-- Google Tag Manager -->',
                '<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':',
                'new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],',
                'j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=',
                '\'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);',
                sprintf(
                    '})(window,document,\'script\',\'dataLayer\',\'%1$s\');</script>',
                    $this->modulesSettings->get('Core', 'google_tracking_google_tag_manager_container_id', null)
                ),
                '<!-- End Google Tag Manager -->',
            ]
        );

        $code = implode("\n", $codeLines) . "\n";

        if (!empty($this->dataLayer->all())) {
            $code = $this->dataLayer->generateHeadCode() . "\n" . $code;
        }

        return $code;
    }

    public function generateStartOfBodyCode(): string
    {
        if (!$this->shouldAddCode()) {
            return '';
        }

        $codeLines = [
            '<!-- Google Tag Manager (noscript) -->',
            '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%1$s%2$s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>',
            '<!-- End Google Tag Manager (noscript) -->',
        ];

        return sprintf(
            implode("\n", $codeLines) . "\n",
            $this->modulesSettings->get('Core', 'google_tracking_google_tag_manager_container_id', null),
            $this->dataLayer->generateNoScriptParameters()
        );
    }
}
