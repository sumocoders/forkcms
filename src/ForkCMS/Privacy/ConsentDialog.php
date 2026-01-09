<?php

namespace ForkCMS\Privacy;

use Common\Core\Cookie;
use Common\ModulesSettings;

class ConsentDialog
{
    const CONSENT_AD_STORAGE = 'ad_storage';
    const CONSENT_AD_USER_DATA = 'ad_user_data';
    const CONSENT_AD_PERSONALIZATION = 'ad_personalization';
    const CONSENT_ANALYTICS_STORAGE = 'analytics_storage';
    const CONSENT_FUNCTIONALITY_STORAGE = 'functionality_storage';
    const CONSENT_PERSONALIZATION_STORAGE = 'personalization_storage';
    const CONSENT_SECURITY_STORAGE = 'security_storage';

    public function __construct(private ModulesSettings $settings, private Cookie $cookie)
    {
    }

    public static function getConsentLevels(): array
    {
        return [
            self::CONSENT_FUNCTIONALITY_STORAGE,
            self::CONSENT_AD_STORAGE,
            self::CONSENT_AD_USER_DATA,
            self::CONSENT_AD_PERSONALIZATION,
            self::CONSENT_ANALYTICS_STORAGE,
            self::CONSENT_PERSONALIZATION_STORAGE,
            self::CONSENT_SECURITY_STORAGE,
        ];
    }

    public function isDialogEnabled(): bool
    {
        return $this->settings->get('Core', 'show_consent_dialog', false);
    }

    public function shouldDialogBeShown(): bool
    {
        // the cookiebar is hidden within the settings, so don't show it
        if (!$this->isDialogEnabled()) {
            return false;
        }

        // no levels mean there should not be any consent
        if (empty($this->getLevels())) {
            return false;
        }

        // if the hash in the cookie is the same as the current has it means the user
        // has already stored their preferences
        if ($this->cookie->get('privacy_consent_hash', '') === $this->getLevelsHash()) {
            return false;
        }

        return true;
    }

    public function getLevels(bool $includeFunctional = false): array
    {
        $levels = [];
        foreach (self::getConsentLevels() as $level) {
            if ($level === self::CONSENT_FUNCTIONALITY_STORAGE && !$includeFunctional) {
                continue;
            }
            $defaultValue = $level === self::CONSENT_FUNCTIONALITY_STORAGE;
            if ($this->settings->get('Core', 'privacy_consent_level_' . $level, $defaultValue)) {
                $levels[] = $level;
            }
        }

        return $levels;
    }

    public function getLevelsHash(): string
    {
        $levels = $this->getLevels(true);
        sort($levels);

        return md5(implode('|', $levels));
    }

    public function getVisitorChoices(): array
    {
        $choices = [
            self::CONSENT_FUNCTIONALITY_STORAGE => true,
        ];
        $levels = $this->getLevels(false);
        foreach ($levels as $level) {
            $choices[$level] = $this->cookie->get('privacy_consent_level_' . $level . '_granted', '0') === '1';
        }

        return $choices;
    }

    public function getJsData(): array
    {
        return [
            'possibleLevels' => $this->getLevels(true),
            'levelsHash' => $this->getLevelsHash(),
            'visitorChoices' => $this->getVisitorChoices(),
        ];
    }

    public function hasAgreedTo(string $level): bool
    {
        $choices = $this->getVisitorChoices();
        if (!array_key_exists($level, $choices)) {
            return false;
        }

        return $choices[$level];
    }
}
