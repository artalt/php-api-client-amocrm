<?php

namespace Tests\amoCRM\Entities\Elements\CustomFields;

use amoCRM\Entities\Elements\CustomFields\BaseCustomField;
use amoCRM\Entities\Elements\CustomFields\CustomFieldText;
use PHPUnit\Framework\TestCase;

/**
 * Class CustomFieldTextTest
 * @package Tests\amoCRM\Entities\Elements\CustomFields
 * @covers \amoCRM\Entities\Elements\CustomFields\CustomFieldText
 */
final class CustomFieldTextTest extends TestCase
{
    /** @var integer */
    private $_default_id = 25;

    public function testIsInstanceOfBaseField()
    {
        $this->assertInstanceOf(
            BaseCustomField::class,
            new CustomFieldText($this->_default_id)
        );
    }

    public function testSetValueToAmo()
    {
        $cf = new CustomFieldText($this->_default_id);
        $value = 'some text';

        $cf->setValue($value);
        $this->assertEquals(['id' => $this->_default_id, 'values' => [['value' => $value]]], $cf->toAmo());
    }

    public function testSetValue()
    {
        $cf = new CustomFieldText($this->_default_id);
        $value = 1;

        $cf->setValue($value);

        $data = $cf->toAmo();
        $this->assertEquals(['id' => $this->_default_id, 'values' => [['value' => $value]]], $data);
        $this->assertInternalType('string', $data['values'][0]['value']);
    }
}