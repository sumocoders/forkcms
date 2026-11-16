<?php

namespace Common\Core;

use SpoonFormDate;

/**
 * This is our extended version of \SpoonFormDate
 */
class FormDate extends SpoonFormDate
{
    /**
     * Checks if this field is correctly submitted.
     *
     * @param string $error The error message to set.
     *
     * @return bool
     */
    public function isValid($error = null): bool
    {
        $return = parent::isValid($error);

        if ($return === false) {
            return false;
        }

        $longMask = str_replace(['d', 'm', 'y', 'Y'], ['dd', 'mm', 'yy', 'yyyy'], $this->mask);

        $data = $this->getMethod(true);

        $year = (str_contains($longMask, 'yyyy')) ? mb_substr(
            (string) $data[$this->attributes['name']],
            mb_strpos($longMask, 'yyyy'),
            4
        ) : mb_substr((string) $data[$this->attributes['name']], mb_strpos($longMask, 'yy'), 2);
        $month = mb_substr((string) $data[$this->attributes['name']], mb_strpos($longMask, 'mm'), 2);
        $day = mb_substr((string) $data[$this->attributes['name']], mb_strpos($longMask, 'dd'), 2);

        // validate datefields that have a from-date set
        if (str_contains((string) $this->attributes['class'], 'inputDatefieldFrom')) {
            $fromDateChunks = explode('-', (string) $this->attributes['data-startdate']);
            $fromDateTimestamp = mktime(12, 00, 00, $fromDateChunks[1], $fromDateChunks[2], $fromDateChunks[0]);

            $givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

            if ($givenDateTimestamp < $fromDateTimestamp) {
                if ($error !== null) {
                    $this->setError($error);
                }

                return false;
            }
        } elseif (str_contains((string) $this->attributes['class'], 'inputDatefieldTill')) {
            $tillDateChunks = explode('-', (string) $this->attributes['data-enddate']);
            $tillDateTimestamp = mktime(12, 00, 00, $tillDateChunks[1], $tillDateChunks[2], $tillDateChunks[0]);

            $givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

            if ($givenDateTimestamp > $tillDateTimestamp) {
                if ($error !== null) {
                    $this->setError($error);
                }

                return false;
            }
        } elseif (str_contains((string) $this->attributes['class'], 'inputDatefieldRange')) {
            $fromDateChunks = explode('-', (string) $this->attributes['data-startdate']);
            $fromDateTimestamp = mktime(12, 00, 00, $fromDateChunks[1], $fromDateChunks[2], $fromDateChunks[0]);

            $tillDateChunks = explode('-', (string) $this->attributes['data-enddate']);
            $tillDateTimestamp = mktime(12, 00, 00, $tillDateChunks[1], $tillDateChunks[2], $tillDateChunks[0]);

            $givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

            if ($givenDateTimestamp < $fromDateTimestamp || $givenDateTimestamp > $tillDateTimestamp) {
                if ($error !== null) {
                    $this->setError($error);
                }

                return false;
            }
        }

        return true;
    }
}
