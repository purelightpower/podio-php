<?php

namespace Podio\Tests;

use PHPUnit\Framework\TestCase;
use Podio\PodioAppField;
use Podio\PodioFieldCollection;
use Podio\PodioObject;
use Podio\PodioDataIntegrityError;

class PodioFieldCollectionTest extends TestCase
{
    /**
     * @var \PodioFieldCollection
     */
    private $collection;

    public function setUp(): void
    {
        parent::setUp();

        $this->collection = new PodioFieldCollection([
            new PodioAppField(['field_id' => 1, 'external_id' => 'a', 'type' => 'text', 'label' => 'Field A']),
            new PodioAppField(['field_id' => 2, 'external_id' => 'b', 'type' => 'number', 'label' => 'Number B']),
            new PodioAppField(['field_id' => 3, 'external_id' => 'c', 'type' => 'calculation', 'label' => 'Field C']),
        ]);
    }

    public function test_can_get_by_external_id(): void
    {
        $field = $this->collection["b"];
        $this->assertSame(2, $field->field_id);
    }

    public function test_can_get_by_external_id_using_get(): void
    {
        $field = $this->collection->get("b");
        $this->assertSame(2, $field->field_id);
    }

    public function test_can_get_by_field_id(): void
    {
        $field = $this->collection->get(2);
        $this->assertSame(2, $field->field_id);
    }

    public function test_can_add_field(): void
    {
        $length = count($this->collection);
        $this->collection[] = new PodioAppField(['field_id' => 4, 'external_id' => 'd']);

        $this->assertCount($length + 1, $this->collection);
    }

    public function test_cannot_add_object(): void
    {
        $this->expectException(PodioDataIntegrityError::class);
        $this->collection[] = new PodioObject();
    }

    public function test_can_replace_field(): void
    {
        $length = count($this->collection);
        $this->collection[] = new PodioAppField(['field_id' => 3, 'external_id' => 'd']);

        $this->assertCount($length, $this->collection);
        $this->assertSame('d', $this->collection->get(3)->external_id);
    }

    public function test_can_remove_field_by_external_id(): void
    {
        $length = count($this->collection);
        unset($this->collection["b"]);

        $this->assertCount($length - 1, $this->collection);
    }

    public function test_can_check_existence_by_external_id(): void
    {
        $this->assertTrue(isset($this->collection["b"]));
        $this->assertFalse(isset($this->collection["d"]));
    }

    public function test_can_list_external_ids(): void
    {
        $this->assertSame(["a", "b", "c"], $this->collection->external_ids());
    }

    public function test_can_list_readonly_fields(): void
    {
        $readonly = $this->collection->readonly_fields();

        $this->assertInstanceOf(PodioFieldCollection::class, $readonly);
        $this->assertSame(count($readonly), 1);
    }

    public function testLabelGet(): void {
        $field = $this->collection->labelGet("Number B");
        $this->assertSame(2, $field->field_id);
    }

    public function testGetAllWithLabel(): void {
        $fields = $this->collection->getAllWithLabel("Number B");
        $this->assertSame(2, $fields->offsetGet(0)->field_id);
    }

    public function testRegexLabelGet(): void {
        $field = $this->collection->regexLabelGet("/^Number B$/");
        $this->assertSame(2, $field->field_id);
    }

    public function testGetAllWithRegexLabel(): void {
        $fields = $this->collection->getAllWithRegexLabel("/^Field \w{1}$/");
        $this->assertSame(1, $fields->offsetGet(0)->field_id);
        $this->assertSame(3, $fields->offsetGet(1)->field_id);
    }
}
