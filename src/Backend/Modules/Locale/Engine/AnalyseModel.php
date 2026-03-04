<?php

namespace Backend\Modules\Locale\Engine;

use Frontend\Core\Engine\Theme;
use Symfony\Component\Finder\Finder;
use Backend\Core\Engine\Model as BackendModel;

/**
 * In this file we store all generic functions that we will be using in the locale module
 */
class AnalyseModel extends Model
{
    /**
     * Get the locale that is used in the Backend but doesn't exists.
     *
     * @param string $language The language to check.
     *
     * @return array
     */
    public static function getNonExistingBackendLocale(string $language): array
    {
        $backendAnalyser = new LocaleAnalyser(
            'Backend',
            [BACKEND_MODULES_PATH, BACKEND_CORE_PATH],
            BackendModel::getModules()
        );

        return $backendAnalyser->findMissingLocale($language);
    }

    /**
     * Get the locale that is used in the Frontend but doesn't exists.
     *
     * @param string $language The language to check.
     *
     * @return array
     */
    public static function getNonExistingFrontendLocale(string $language): array
    {
        $themePath = FRONTEND_THEMES_PATH . '/' .Theme::getTheme() . '/';

        $frontendAnalyser = new LocaleAnalyser(
            'Frontend',
            [
                FRONTEND_MODULES_PATH,
                FRONTEND_CORE_PATH,
                $themePath . 'Core',
                $themePath . 'Modules'
            ],
            BackendModel::getModules()
        );

        return $frontendAnalyser->findMissingLocale($language);
    }
}
