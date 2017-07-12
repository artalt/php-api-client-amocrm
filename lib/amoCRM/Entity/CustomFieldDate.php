<?php

namespace amoCRM\Entity;

use amoCRM\Exception;

/**
 * Class CustomFieldDate
 * @package amoCRM\Entity
 * Represents date custom field of amoCRM
 */
final class CustomFieldDate extends CustomFieldSingleValue
{
    private $date_format = 'd.m.Y';

    /**
     * @return string current used date format
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }

    /**
     * @param string $value - date in format $this->_date_format
     * @throws \amoCRM\Exception\InvalidArgumentException
     */
    public function setValue($value)
    {
        $this->setTimestamp($this->parseDate($value));
    }

    /**
     * @param string $timestamp - date timestamp
     * @throws \amoCRM\Exception\InvalidArgumentException
     */
    public function setTimestamp($timestamp)
    {
        $this->value = $this->parseNumber($timestamp, true, true);
    }

    /**
     * @param string $date
     * @return int
     * @throws Exception\InvalidArgumentException
     */
    private function parseDate($date)
    {
        $date_time = \DateTime::createFromFormat($this->date_format, $date);

        if ($date_time === false) {
            throw new Exception\InvalidArgumentException(sprintf('Invalid date "%s"', $date));
        }

        return $date_time->getTimestamp();
    }

    /**
     * @return string
     */
    protected function prepareToAmo()
    {
        return $this->value === null ? '' : date($this->date_format, $this->value);
    }
}
