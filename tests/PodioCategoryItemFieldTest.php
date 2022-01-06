<?php

namespace Podio\Tests;

use PHPUnit\Framework\TestCase;
use Podio\PodioCategoryItemField;
use Podio\PodioDataIntegrityError;

class PodioCategoryItemFieldTest extends TestCase
{
    /**
     * @var \PodioCategoryItemField
     */
    private $object;

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new PodioCategoryItemField([
            '__api_values' => true,
            'field_id' => 123,
            'values' => [
                ['value' => ['id' => 1, 'text' => 'Snap']],
                ['value' => ['id' => 2, 'text' => 'Crackle']],
                ['value' => ['id' => 3, 'text' => 'Pop']],
            ],
            "config" => [
                "settings" => [
                    "multiple" => true
                ]
            ]
        ]);
    }

    public function test_can_construct_from_simple_value(): void
    {
        $object = new PodioCategoryItemField([
            'field_id' => 123,
            'values' => 4,
        ]);
        $this->assertSame([['value' => ['id' => 4]]], $object->__attribute('values'));
    }

    public function test_can_provide_value(): void
    {
        // Empty values
        $empty_values = new PodioCategoryItemField(['field_id' => 1]);
        $this->assertNull($empty_values->values);

        // Populated values
        $this->assertSame([
            ['id' => 1, 'text' => 'Snap'],
            ['id' => 2, 'text' => 'Crackle'],
            ['id' => 3, 'text' => 'Pop'],
        ], $this->object->values);
    }

    public function test_can_set_values_from_id(): void
    {
        $this->object->values = 4;
        $this->assertSame([['value' => ['id' => 4]]], $this->object->__attribute('values'));
    }

    public function test_can_set_values_from_array(): void
    {
        $this->object->values = [4];
        $this->assertSame([['value' => ['id' => 4]]], $this->object->__attribute('values'));
    }

    public function test_can_set_values_from_hash(): void
    {
        $this->object->values = [['id' => 4, 'text' => 'Captain Crunch']];
        $this->assertSame([
            [
                'value' => [
                    'id' => 4,
                    'text' => 'Captain Crunch',
                ],
            ],
        ], $this->object->__attribute('values'));
    }

    public function test_can_add_value_from_id(): void
    {
        $this->object->add_value(4);
        $this->assertSame([
            ['value' => ['id' => 1, 'text' => 'Snap']],
            ['value' => ['id' => 2, 'text' => 'Crackle']],
            ['value' => ['id' => 3, 'text' => 'Pop']],
            ['value' => ['id' => 4]],
        ], $this->object->__attribute('values'));
    }

    public function test_can_add_value_from_hash(): void
    {
        $this->object->add_value(['id' => 4, 'text' => 'Captain Crunch']);
        $this->assertSame([
            ['value' => ['id' => 1, 'text' => 'Snap']],
            ['value' => ['id' => 2, 'text' => 'Crackle']],
            ['value' => ['id' => 3, 'text' => 'Pop']],
            ['value' => ['id' => 4, 'text' => 'Captain Crunch']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_humanize_value(): void
    {
        // Empty values
        $empty_values = new PodioCategoryItemField(['field_id' => 1]);
        $this->assertSame('', $empty_values->humanized_value());

        // Populated values
        $this->assertSame('Snap;Crackle;Pop', $this->object->humanized_value());
    }

    public function test_can_convert_to_api_friendly_json(): void
    {
        // Empty values
        $empty_values = new PodioCategoryItemField(['field_id' => 1]);
        $this->assertSame('[]', $empty_values->as_json());

        // Populated values
        $this->assertSame('[1,2,3]', $this->object->as_json());
    }

    public function testGetValue(): void {
        $this->assertEquals(['Snap', 'Crackle', 'Pop'], $this->object->getValue());
    }

    public function testGetSingularValue(): void {
        $this->object->values = [
            ["value" => ["id" => 1, "text" => "Snap"]]
        ];
        $this->object->config = [
            "settings" => [
                "multiple" => false
            ]
        ];
        $this->assertEquals("Snap", $this->object->getValue());
    }
}
