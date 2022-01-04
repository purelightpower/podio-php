<?php

    use PHPUnit\Framework\TestCase;
    use Podio\PodioItem;
    use Podio\PodioApp;
    use Podio\PodioItemFieldCollection;
    use Podio\PodioTextItemField;
    use Podio\PodioNumberItemField;
    use Podio\PodioCategoryItemField;
    use Podio\PodioEmailItemField;
    use Podio\PodioDateItemField;
    use Podio\PodioMoneyItemField;
    use Podio\Tools\PodioItemParser;

    class PodioItemParserTest extends TestCase {
        private PodioItemParser $parser;

        public function setUp(): void {
            $item = new PodioItem([
                'app' => new PodioApp(1234),
                'fields' => new PodioItemFieldCollection([
                    new PodioTextItemField([
                        "external_id" => "title",
                        "values" => "Hello World",
                        "label" => "Test Item Title"
                    ]),
                    new PodioNumberItemField([
                        "external_id" => "test-number",
                        "values" => 123,
                        "label" => "Test Number"
                    ]),
                    new PodioCategoryItemField([
                        "external_id" => "test-category",
                        "values" => [
                            ["id" => 1, "status" => "active", "text" => "Yes"]
                        ],
                        "label" => "Test Selection",
                        "config" => [
                            "settings" => [
                                "multiple" => false
                            ]
                        ]
                    ]),
                    new PodioEmailItemField([
                        "external_id" => "test-email",
                        "label" => "Test Email",
                        "values" => [
                            [
                                "type" => "home",
                                "value" => "fake@email.com"
                            ]
                        ]
                    ]),
                    new PodioDateItemField([
                        "external_id" => "test-date",
                        "values" => [
                            "start" => "2021-12-28 01:00:00",
                            "end" => "2021-12-29 23:59:59",
                        ],
                        "label" => "Test Date",
                        "config" => [
                            "settings" => [
                                "end" => true
                            ]
                        ]
                    ]),
                    new PodioMoneyItemField([
                        "external_id" => "test-price",
                        "values" => [
                            "value" => "123.45",
                            "currency" => "USD"
                        ],
                        "label" => "Test Price"
                    ])
                ])
            ]);
            $this->parser = new PodioItemParser($item);
        }

        public function testParser(): void {
            $this->assertEquals("Hello World", $this->parser->getFieldValueByLabel("Test Item Title"));
            $this->assertEquals(123, $this->parser->getFieldValueByLabel("Test Number"));
            $this->assertEquals("Yes", $this->parser->getFieldValueByLabel("Test Selection"));
            $this->assertEquals("fake@email.com", $this->parser->getFieldValueByLabel("Test Email"));
            $dateRange = $this->parser->getFieldValueByLabel("Test Date");
            $this->assertEquals("2021-12-28 01:00:00", $dateRange["start"]->format("Y-m-d H:i:s"));
            $this->assertEquals("2021-12-29 23:59:59", $dateRange["end"]->format("Y-m-d H:i:s"));
            $this->assertEquals(123.45, $this->parser->getFieldValueByLabel("Test Price"));
        }
    }

?>