<?php

namespace Podio\Tests;

use PHPUnit\Framework\TestCase;
use Podio\PodioProgressItemField;

class PodioProgressItemFieldTest extends TestCase
{
    /**
     * @var \PodioProgressItemField
     */
    private $object;

    /**
     * @var \PodioProgressItemField
     */
    private $empty_values;

    /**
     * @var \PodioProgressItemField
     */
    private $zero_value;

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new PodioProgressItemField([
            '__api_values' => true,
            'field_id' => 123,
            'values' => [
                ['value' => 55],
            ],
        ]);
        $this->empty_values = new PodioProgressItemField(['field_id' => 1]);
        $this->zero_value = new PodioProgressItemField([
            '__api_values' => true,
            'field_id' => 2,
            'values' => [['value' => 0]],
        ]);
    }

    public function test_can_construct_from_simple_value(): void
    {
        $object = new PodioProgressItemField([
            'field_id' => 123,
            'values' => 75,
        ]);
        $this->assertSame(75, $object->values);
    }

    public function test_can_provide_value(): void
    {
        $this->assertNull($this->empty_values->values);
        $this->assertSame(55, $this->object->values);
        $this->assertSame(0, $this->zero_value->values);
    }

    public function test_can_set_value(): void
    {
        $this->object->values = 75;
        $this->assertSame([['value' => 75]], $this->object->__attribute('values'));

        $this->object->values = 0;
        $this->assertSame(0, $this->zero_value->values);
    }

    public function test_can_humanize_value(): void
    {
        $this->assertSame('', $this->empty_values->humanized_value());
        $this->assertSame('55%', $this->object->humanized_value());
        $this->assertSame('0%', $this->zero_value->humanized_value());
    }

    public function test_can_convert_to_api_friendly_json(): void
    {
        $this->assertSame('null', $this->empty_values->as_json());
        $this->assertSame('55', $this->object->as_json());
        $this->assertSame('0', $this->zero_value->as_json());
    }

    public function testGetValue(): void {
        $this->assertEquals(55, $this->object->getValue());
    }
}
