<?php

namespace Backend\Modules\FormBuilder\Engine;

use Backend\Core\Language\Language;

/**
 * The autocomplete values can be found on
 *
 * https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/autocomplete
 */
final class Autocomplete
{
    const string NAME = 'name';
    const string HONORIFIC_PREFIX = 'honorific-prefix';
    const string GIVEN_NAME = 'given-name';
    const string ADDITIONAL_NAME = 'additional-name';
    const string FAMILY_NAME = 'family-name';
    const string NICKNAME = 'nickname';
    const string EMAIL = 'email';
    const string USERNAME = 'username';
    const string NEW_PASSWORD = 'new-password';
    const string CURRENT_PASSWORD = 'current-password';
    const string ORGANIZATION_TITLE = 'organization-title';
    const string ORGANIZATION = 'organization';
    const string STREET_ADDRESS = 'street-address';
    const string COUNTRY = 'country';
    const string COUNTRY_NAME = 'country-name';
    const string POSTAL_CODE = 'postal-code';
    const string CC_NAME = 'cc-name';
    const string CC_GIVEN_NAME = 'cc-given-name';
    const string CC_ADDITIONAL_NAME = 'cc-additional-name';
    const string CC_FAMILY_NAME = 'cc-family-name';
    const string CC_NUMBER = 'cc-number';
    const string CC_EXP = 'cc-exp';
    const string CC_EXP_MONTH = 'cc-exp-month';
    const string CC_EXP_YEAR = 'cc-exp-year';
    const string CC_CSC = 'cc-csc';
    const string CC_TYPE = 'cc-type';
    const string TRANSACTION_CURRENCY = 'transaction-currency';
    const string TRANSACTION_AMOUNT = 'transaction-amount';
    const string LANGUAGE = 'language';
    const string BDAY = 'bday';
    const string BDAY_DAY = 'bday-day';
    const string BDAY_MONTH = 'bday-month';
    const string BDAY_YEAR = 'bday-year';
    const string SEX = 'sex';
    const string TEL = 'tel';
    const string URL = 'url';
    const string PHOTO = 'photo';

    const array POSSIBLE_VALUES = [
        self::NAME,
        self::HONORIFIC_PREFIX,
        self::GIVEN_NAME,
        self::ADDITIONAL_NAME,
        self::FAMILY_NAME,
        self::NICKNAME,
        self::EMAIL,
        self::USERNAME,
        self::NEW_PASSWORD,
        self::CURRENT_PASSWORD,
        self::ORGANIZATION_TITLE,
        self::ORGANIZATION,
        self::STREET_ADDRESS,
        self::COUNTRY,
        self::COUNTRY_NAME,
        self::POSTAL_CODE,
        self::CC_NAME,
        self::CC_GIVEN_NAME,
        self::CC_ADDITIONAL_NAME,
        self::CC_FAMILY_NAME,
        self::CC_NUMBER,
        self::CC_EXP,
        self::CC_EXP_MONTH,
        self::CC_EXP_YEAR,
        self::CC_CSC,
        self::CC_TYPE,
        self::TRANSACTION_CURRENCY,
        self::TRANSACTION_AMOUNT,
        self::LANGUAGE,
        self::BDAY,
        self::BDAY_DAY,
        self::BDAY_MONTH,
        self::BDAY_YEAR,
        self::SEX,
        self::TEL,
        self::URL,
        self::PHOTO
    ];

    public static function getValuesForDropdown(): array
    {
        // map the values to replace them with the backend translations
        // use array combine to set the keys as the autocomplete values instead of key values
        return array_map(
            fn(string $value): string => $value . ' (' . Language::getLabel('Autocomplete_' . str_replace('-', '_', $value)) . ')',
            array_combine(
                Autocomplete::POSSIBLE_VALUES,
                Autocomplete::POSSIBLE_VALUES
            )
        );
    }
}
