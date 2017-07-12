<?php

namespace Tests\amoCRM\Entity;

use amoCRM\Entity\BaseCustomField;
use amoCRM\Entity\CustomFieldCheckbox;
use PHPUnit\Framework\TestCase;

/**
 * Class CustomFieldCheckboxTest
 * @package Tests\amoCRM\Entity
 * @covers \amoCRM\Entity\CustomFieldCheckbox
 */
final class CustomFieldCheckboxTest extends TestCase
{
    /** @var integer */
    private $default_id = 25;

    public function testIsInstanceOfBaseField()
    {
        $this->assertInstanceOf(
            BaseCustomField::class,
            new CustomFieldCheckbox($this->default_id)
        );
    }

    public function testSetValueTrue()
    {
        $cf = new CustomFieldCheckbox($this->default_id);
        $value = 1;

        $cf->setValue($value);

        $data = $cf->toAmo();
        $this->assertEquals(['id' => $this->default_id, 'values' => [['value' => $value]]], $data);
        $this->assertInternalType('integer', $data['values'][0]['value']);
    }

    public function testSetValueFalse()
    {
        $cf = new CustomFieldCheckbox($this->default_id);
        $value = 0;

        $cf->setValue($value);

        $data = $cf->toAmo();
        $this->assertEquals(['id' => $this->default_id, 'values' => [['value' => $value]]], $data);
        $this->assertInternalType('integer', $data['values'][0]['value']);
    }
}