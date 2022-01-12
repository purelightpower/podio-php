<?php

namespace Podio\Tests;

use PHPUnit\Framework\TestCase;
use Podio\PodioEmailItemField;

class PodioEmailItemFieldTest extends TestCase
{
    /**
     * @var \PodioEmailItemField
     */
    private $object;

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new PodioEmailItemField([
            '__api_values' => true,
            'values' => [
                ['type' => 'work', 'value' => 'mail@example.com'],
                ['type' => 'other', 'value' => 'info@example.com'],
            ],
        ]);
    }

    public function test_can_provide_value(): void
    {
        // Empty values
        $empty_values = new PodioEmailItemField();
        $this->assertNull($empty_values->values);

        // Populated values
        $this->assertSame([
            ['type' => 'work', 'value' => 'mail@example.com'],
            ['type' => 'other', 'value' => 'info@example.com'],
        ], $this->object->values);
    }

    public function test_can_set_value_from_hash(): void
    {
        $this->object->values = [
            ['type' => 'work', 'value' => 'other@example.com'],
            ['type' => 'other', 'value' => '42@example.com'],
        ];
        $this->assertSame([
            ['type' => 'work', 'value' => 'other@example.com'],
            ['type' => 'other', 'value' => '42@example.com'],
        ], $this->object->__attribute('values'));
    }

    public function test_can_humanize_value(): void
    {
        // Empty values
        $empty_values = new PodioEmailItemField();
        $this->assertSame('', $empty_values->humanized_value());

        // Populated values
        $this->assertSame('work: mail@example.com;other: info@example.com', $this->object->humanized_value());
    }

    public function test_can_convert_to_api_friendly_json(): void
    {
        // Empty values
        $empty_values = new PodioEmailItemField();
        $this->assertSame('[]', $empty_values->as_json());

        // Populated values
        $this->assertSame('[{"type":"work","value":"mail@example.com"},{"type":"other","value":"info@example.com"}]', $this->object->as_json());
    }

    public function testGetValue(): void {
        $this->assertEquals(["mail@example.com", "info@example.com"], $this->object->getValue());
    }

    public function testGetValueSingular(): void {
        $this->object->values = [
            ["type" => "home", "value" => "mail@example.com"]
        ];
        $this->assertEquals("mail@example.com", $this->object->getValue());
    }
}
