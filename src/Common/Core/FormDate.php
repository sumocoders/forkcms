<?php

namespace Common\Core;

use DateTimeImmutable;
use SpoonFilter;
use SpoonFormDate;
use SpoonFormException;

/**
 * This is our extended version of \SpoonFormDate
 */
class FormDate extends SpoonFormDate
{
    private bool $nativeDateInput = false;

    /**
     * Render as a native <input type="date">, the browser provides the date picker.
     * The mask should be Y-m-d, the format a date input submits.
     */
    public function useNativeDateInput(): void
    {
        $this->nativeDateInput = true;
    }

    #[\Override]
    public function parse($template = null)
    {
        if (!$this->nativeDateInput) {
            return parent::parse($template);
        }

        if ($this->attributes['name'] == '') {
            throw new SpoonFormException('A name is required for a date field. Please provide a valid name.');
        }

        $output = '<input type="date" value="' . SpoonFilter::htmlspecialchars($this->getValue()) . '"';
        $output .= $this->getAttributesHTML(
            [
                '[id]' => $this->attributes['id'],
                '[name]' => $this->attributes['name'],
                '[value]' => $this->getValue(),
            ]
        ) . ' />';

        if ($template !== null) {
            $fieldName = SpoonFilter::toCamelCase($this->attributes['name']);
            $template->assign('txt' . $fieldName, $output);
            $template->assign(
                'txt' . $fieldName . 'Error',
                ($this->errors != '') ? '<span class="formError">' . $this->errors . '</span>' : ''
            );
        }

        return $output;
    }

    /**
     * Checks if this field is correctly submitted.
     *
     * @param string $error The error message to set.
     *
     * @return bool
     */
    public function isValid($error = null): bool
    {
        // SpoonFormDate only parses masks with the year at the end, so a native Y-m-d value is checked here
        $return = $this->nativeDateInput ? $this->isValidNativeDate($error) : parent::isValid($error);

        if ($return === false) {
            return false;
        }

        $longMask = str_replace(['d', 'm', 'y', 'Y'], ['dd', 'mm', 'yy', 'yyyy'], $this->mask);

        $data = $this->getMethod(true);

        $year = (mb_strpos($longMask, 'yyyy') !== false) ? mb_substr(
            (string) $data[$this->attributes['name']],
            mb_strpos($longMask, 'yyyy'),
            4
        ) : mb_substr((string) $data[$this->attributes['name']], mb_strpos($longMask, 'yy'), 2);
        $month = mb_substr((string) $data[$this->attributes['name']], mb_strpos($longMask, 'mm'), 2);
        $day = mb_substr((string) $data[$this->attributes['name']], mb_strpos($longMask, 'dd'), 2);

        // validate datefields that have a from-date set
        if (mb_strpos((string) $this->attributes['class'], 'inputDatefieldFrom') !== false) {
            $fromDateChunks = explode('-', (string) $this->attributes['data-startdate']);
            $fromDateTimestamp = mktime(12, 00, 00, $fromDateChunks[1], $fromDateChunks[2], $fromDateChunks[0]);

            $givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

            if ($givenDateTimestamp < $fromDateTimestamp) {
                if ($error !== null) {
                    $this->setError($error);
                }

                return false;
            }
        } elseif (mb_strpos((string) $this->attributes['class'], 'inputDatefieldTill') !== false) {
            $tillDateChunks = explode('-', (string) $this->attributes['data-enddate']);
            $tillDateTimestamp = mktime(12, 00, 00, $tillDateChunks[1], $tillDateChunks[2], $tillDateChunks[0]);

            $givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

            if ($givenDateTimestamp > $tillDateTimestamp) {
                if ($error !== null) {
                    $this->setError($error);
                }

                return false;
            }
        } elseif (mb_strpos((string) $this->attributes['class'], 'inputDatefieldRange') !== false) {
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

    #[\Override]
    public function getTimestamp(
        $year = null,
        $month = null,
        $day = null,
        $hour = null,
        $minute = null,
        $second = null
    ) {
        $submittedDate = $this->nativeDateInput && $this->isFilled() ? $this->getSubmittedNativeDate() : null;
        if ($submittedDate === null) {
            return parent::getTimestamp($year, $month, $day, $hour, $minute, $second);
        }

        return mktime(
            $hour !== null ? (int) $hour : (int) date('H'),
            $minute !== null ? (int) $minute : (int) date('i'),
            $second !== null ? (int) $second : (int) date('s'),
            $month !== null ? (int) $month : (int) $submittedDate->format('n'),
            $day !== null ? (int) $day : (int) $submittedDate->format('j'),
            $year !== null ? (int) $year : (int) $submittedDate->format('Y')
        );
    }

    private function isValidNativeDate(?string $error): bool
    {
        if ($this->isFilled() && $this->getSubmittedNativeDate() !== null) {
            return true;
        }

        if ($error !== null) {
            $this->setError($error);
        }

        return false;
    }

    private function getSubmittedNativeDate(): ?DateTimeImmutable
    {
        $data = $this->getMethod(true);
        $value = $data[$this->getName()] ?? null;
        if (!is_string($value)) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        // reject overflowing dates like 2026-02-31, createFromFormat silently rolls them over
        return $date !== false && $date->format('Y-m-d') === $value ? $date : null;
    }
}
