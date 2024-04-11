<?php

namespace Common\Doctrine\Type;

use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TimeType;

class UTCTimeType extends TimeType
{
    /** @var DateTimeZone */
    private static $utc;

    /** @var DateTimeZone */
    private static $defaultTimeZone;

    /**
     * @param DateTime|null $time
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($time, AbstractPlatform $platform): ?string
    {
        // Reset date part of the time object to 01/01/1970 to keep the timezone consistent
        // between writing and reading the entity from the database
        $time->setDate(1970, 1, 1);

        if ($time instanceof DateTime) {
            $time->setTimezone(self::getUtc());
        }

        return parent::convertToDatabaseValue($time, $platform);
    }

    /**
     * @param string|null|DateTime $timeString
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     *
     * @return DateTime|null
     */
    public function convertToPHPValue($timeString, AbstractPlatform $platform): ?DateTime
    {
        if (null === $timeString || $timeString instanceof DateTime) {
            return $timeString;
        }

        $time = DateTime::createFromFormat($platform->getTimeFormatString(), $timeString, self::getUtc());

        // Reset date part of the time object to 01/01/1970 to keep the timezone consistent
        // between writing and reading the entity from the database
        $time->setDate(1970, 1, 1);

        if (!$time) {
            throw ConversionException::conversionFailedFormat(
                $timeString,
                $this->getName(),
                $platform->getTimeFormatString()
            );
        }

        // set time zone
        $time->setTimezone(self::getDefaultTimeZone());

        return $time;
    }

    private static function getUtc(): DateTimeZone
    {
        if (self::$utc === null) {
            self::$utc = new DateTimeZone('UTC');
        }

        return self::$utc;
    }

    private static function getDefaultTimeZone(): DateTimeZone
    {
        if (self::$defaultTimeZone === null) {
            self::$defaultTimeZone = new DateTimeZone(date_default_timezone_get());
        }

        return self::$defaultTimeZone;
    }
}
