<?php

namespace Podio\Tests;

use PHPUnit\Framework\TestCase;
use Podio\PodioCollection;
use Podio\PodioContact;
use Podio\PodioContactItemField;

class PodioContactItemFieldTest extends TestCase
{
    /**
     * @var \PodioContactItemField
     */
    private $object;

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new PodioContactItemField([
            '__api_values' => true,
            'values' => [
                ['value' => ['profile_id' => 1, 'name' => 'Snap']],
                ['value' => ['profile_id' => 2, 'name' => 'Crackle']],
                ['value' => ['profile_id' => 3, 'name' => 'Pop']],
            ],
        ]);
    }

    public function test_can_construct_from_simple_value(): void
    {
        $object = new PodioContactItemField([
            'field_id' => 123,
            'values' => ['profile_id' => 4, 'name' => 'Captain Crunch'],
        ]);
        $this->assertSame([
            ['value' => ['profile_id' => 4, 'name' => 'Captain Crunch']],
        ], $object->__attribute('values'));
    }

    public function test_can_provide_value(): void
    {
        // Empty values
        $empty_values = new PodioContactItemField(['field_id' => 1]);
        $this->assertNull($empty_values->values);

        // Populated values
        $this->assertInstanceOf(PodioCollection::class, $this->object->values);
        $this->assertCount(3, $this->object->values);
        foreach ($this->object->values as $value) {
            $this->assertInstanceOf(PodioContact::class, $value);
        }
    }

    public function test_can_set_value_from_object(): void
    {
        $this->object->values = new PodioContact(['profile_id' => 4, 'name' => 'Captain Crunch']);
        $this->assertSame([
            ['value' => ['profile_id' => 4, 'name' => 'Captain Crunch']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_collection(): void
    {
        $this->object->values = new PodioCollection([
            new PodioContact([
                'profile_id' => 4,
                'name' => 'Captain Crunch',
            ]),
        ]);

        $this->assertSame([
            ['value' => ['profile_id' => 4, 'name' => 'Captain Crunch']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_hash(): void
    {
        $this->object->values = ['profile_id' => 4, 'name' => 'Captain Crunch'];
        $this->assertSame([
            ['value' => ['profile_id' => 4, 'name' => 'Captain Crunch']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_array_of_objects(): void
    {
        $this->object->values = [
            new PodioContact(['profile_id' => 4, 'name' => 'Captain Crunch']),
            new PodioContact(['profile_id' => 5, 'name' => 'Count Chocula']),
        ];
        $this->assertSame([
            ['value' => ['profile_id' => 4, 'name' => 'Captain Crunch']],
            ['value' => ['profile_id' => 5, 'name' => 'Count Chocula']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_array_of_hashes(): void
    {
        $this->object->values = [
            ['profile_id' => 4, 'name' => 'Captain Crunch'],
            ['profile_id' => 5, 'name' => 'Count Chocula'],
        ];
        $this->assertSame([
            ['value' => ['profile_id' => 4, 'name' => 'Captain Crunch']],
            ['value' => ['profile_id' => 5, 'name' => 'Count Chocula']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_humanize_value(): void
    {
        // Empty values
        $empty_values = new PodioContactItemField(['field_id' => 1]);
        $this->assertSame('', $empty_values->humanized_value());

        // Populated values
        $this->assertSame('Snap;Crackle;Pop', $this->object->humanized_value());
    }

    public function test_can_convert_to_api_friendly_json(): void
    {
        // Empty values
        $empty_values = new PodioContactItemField(['field_id' => 1]);
        $this->assertSame('[]', $empty_values->as_json());

        // Populated values
        $this->assertSame('[1,2,3]', $this->object->as_json());
    }

    public function testGetValue(): void {
        $value = $this->object->getValue();
        $this->assertEquals(1, $value->offsetGet(0)->profile_id);
        $this->assertEquals("Snap", $value->offsetGet(0)->name);
        $this->assertEquals(2, $value->offsetGet(1)->profile_id);
        $this->assertEquals("Crackle", $value->offsetGet(1)->name);
        $this->assertEquals(3, $value->offsetGet(2)->profile_id);
        $this->assertEquals("Pop", $value->offsetGet(2)->name);
    }

    public function testGetValueWithSingle(): void {
        $object =  new PodioContactItemField([
            'field_id' => 123,
            'values' => ['profile_id' => 4, 'name' => 'Captain Crunch'],
        ]);
        $value = $object->getValue();
        $this->assertEquals(4, $value->profile_id);
        $this->assertEquals("Captain Crunch", $value->name);
    }
}
