<?php

namespace amoCRM\Entity;

use amoCRM\Exceptions;

/**
 * Class CustomFieldSelect
 * @package amoCRM\Entity
 * Represents custom field with select from options
 */
class CustomFieldSelect extends CustomFieldSingleValue
{
    /** @var array */
    protected $enums;

    /**
     * CustomFieldSelect constructor.
     * @param integer $id
     * @param array $enums
     * @throws \amoCRM\Exceptions\InvalidArgumentException
     */
    public function __construct($id, $enums)
    {
        parent::__construct($id);
        foreach ($enums as $enum => $value) {
            $this->enums[$this->parseNumber($enum)] = (string)$value;
        }
    }

    /**
     * @param string $value
     * @throws Exceptions\InvalidArgumentException
     */
    public function setValue($value)
    {
        $enum = array_search((string)$value, $this->enums, true);

        if ($enum === false) {
            throw new Exceptions\InvalidArgumentException(sprintf('"%s" is not in enums', $value));
        }

        $this->setEnum($enum);
    }

    /**
     * @param integer $enum
     * @throws Exceptions\InvalidArgumentException
     */
    public function setEnum($enum)
    {
        $this->value = $this->parseNumber($enum, true);

        if (!isset($this->enums[$this->value])) {
            throw new Exceptions\InvalidArgumentException(sprintf('"%s" is not enum of this field', $enum));
        }
    }
}