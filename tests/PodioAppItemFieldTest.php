<?php

namespace Podio\Tests;

use PHPUnit\Framework\TestCase;
use Podio\PodioAppItemField;
use Podio\PodioCollection;
use Podio\PodioItemCollection;
use Podio\PodioItem;

class PodioAppItemFieldTest extends TestCase
{
    /**
     * @var \PodioAppItemField
     */
    private $object;

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new PodioAppItemField([
            '__api_values' => true,
            'values' => [
                ['value' => ['item_id' => 1, 'title' => 'Snap']],
                ['value' => ['item_id' => 2, 'title' => 'Crackle']],
                ['value' => ['item_id' => 3, 'title' => 'Pop']],
            ]
        ]);
    }

    public function test_can_construct_from_simple_value(): void
    {
        $object = new PodioAppItemField([
            'field_id' => 123,
            'values' => ['item_id' => 4, 'title' => 'Captain Crunch'],
        ]);
        $this->assertSame([
            ['value' => ['item_id' => 4, 'title' => 'Captain Crunch']],
        ], $object->__attribute('values'));
    }

    public function test_can_provide_value(): void
    {
        // Empty values
        $empty_values = new PodioAppItemField(['field_id' => 1]);
        $this->assertNull($empty_values->values);

        // Populated values
        $this->assertInstanceOf(PodioCollection::class, $this->object->values);
        $this->assertCount(3, $this->object->values);
        foreach ($this->object->values as $value) {
            $this->assertInstanceOf(PodioItem::class, $value);
        }
    }

    public function test_can_set_value_from_object(): void
    {
        $this->object->values = new PodioItem(['item_id' => 4, 'title' => 'Captain Crunch']);
        $this->assertSame([
            ['value' => ['item_id' => 4, 'title' => 'Captain Crunch']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_collection(): void
    {
        $this->object->values = new PodioCollection([new PodioItem(['item_id' => 4, 'title' => 'Captain Crunch'])]);

        $this->assertSame([
            ['value' => ['item_id' => 4, 'title' => 'Captain Crunch']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_hash(): void
    {
        $this->object->values = ['item_id' => 4, 'title' => 'Captain Crunch'];
        $this->assertSame([
            ['value' => ['item_id' => 4, 'title' => 'Captain Crunch']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_array_of_objects(): void
    {
        $this->object->values = [
            new PodioItem(['item_id' => 4, 'title' => 'Captain Crunch']),
            new PodioItem(['item_id' => 5, 'title' => 'Count Chocula']),
        ];
        $this->assertSame([
            ['value' => ['item_id' => 4, 'title' => 'Captain Crunch']],
            ['value' => ['item_id' => 5, 'title' => 'Count Chocula']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_set_value_from_array_of_hashes(): void
    {
        $this->object->values = [
            ['item_id' => 4, 'title' => 'Captain Crunch'],
            ['item_id' => 5, 'title' => 'Count Chocula'],
        ];
        $this->assertSame([
            ['value' => ['item_id' => 4, 'title' => 'Captain Crunch']],
            ['value' => ['item_id' => 5, 'title' => 'Count Chocula']],
        ], $this->object->__attribute('values'));
    }

    public function test_can_humanize_value(): void
    {
        // Empty values
        $empty_values = new PodioAppItemField(['field_id' => 1]);
        $this->assertSame('', $empty_values->humanized_value());

        // Populated values
        $this->assertSame('Snap;Crackle;Pop', $this->object->humanized_value());
    }

    public function test_can_convert_to_api_friendly_json(): void
    {
        // Empty values
        $empty_values = new PodioAppItemField(['field_id' => 1]);
        $this->assertSame('[]', $empty_values->as_json());

        // Populated values
        $this->assertSame('[1,2,3]', $this->object->as_json());
    }

    public function testGetValue(): void {
        $this->object->config = [
            'settings' => [
                'multiple' => true
            ]
        ];
        $this->object->values = [
            new PodioItem(['item_id' => 4, 'title' => 'Captain Crunch']),
            new PodioItem(['item_id' => 5, 'title' => 'Count Chocula']),
        ];
        $value = $this->object->getValue();
        $this->assertInstanceOf(PodioItemCollection::class, $value);
        $this->assertEquals(4, $value->offsetGet(0)->item_id);
        $this->assertEquals("Captain Crunch", $value->offsetGet(0)->title);
        $this->assertEquals(5, $value->offsetGet(1)->item_id);
        $this->assertEquals("Count Chocula", $value->offsetGet(1)->title);
    }

    public function testGetValueWithSingle(): void {
        $this->object->config = [
            'settings' => [
                'multiple' => false
            ]
        ];
        $this->object->values = new PodioItem(['item_id' => 4, 'title' => 'Captain Crunch']);
        $value = $this->object->getValue();
        $this->assertInstanceOf(PodioItem::class, $value);
        $this->assertEquals(4, $value->item_id);
        $this->assertEquals("Captain Crunch", $value->title);
    }
}
