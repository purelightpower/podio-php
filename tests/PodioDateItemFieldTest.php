<?php

namespace Podio\Tests;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Podio\PodioDateItemField;

class PodioDateItemFieldTest extends TestCase
{
    /**
     * @var \PodioDateItemField
     */
    private $empty_values;

    /**
     * @var \PodioDateItemField
     */
    private $start_date;

    /**
     * @var \PodioDateItemField
     */
    private $start_datetime;

    /**
     * @var \PodioDateItemField
     */
    private $start_datetime_with_endtime_same_day;

    /**
     * @var \PodioDateItemField
     */
    private $start_date_end_date;

    /**
     * @var \PodioDateItemField
     */
    private $start_datetime_end_date;

    /**
     * @var \PodioDateItemField
     */
    private $start_datetime_end_datetime;

    /**
     * @var \PodioDateItemField
     */
    private $start_date_omitted_end;

    public function setUp(): void
    {
        parent::setUp();

        $this->empty_values = new PodioDateItemField(['field_id' => 1]);

        $this->start_date = new PodioDateItemField([
            '__api_values' => true,
            'field_id' => 2,
            'values' => [
                [
                    "start_date_utc" => "2011-05-31",
                    "end_date_utc" => "2011-05-31",
                    "start_time_utc" => null,
                    "end_time_utc" => null,
                ],
            ],
        ]);

        $this->start_datetime = new PodioDateItemField([
            '__api_values' => true,
            'field_id' => 3,
            'values' => [
                [
                    "start_date_utc" => "2011-05-31",
                    "end_date_utc" => "2011-05-31",
                    "start_time_utc" => "14:00:00",
                    "end_time_utc" => null,
                ],
            ],
        ]);

        $this->start_datetime_with_endtime_same_day = new PodioDateItemField([
            '__api_values' => true,
            'field_id' => 4,
            'values' => [
                [
                    "start_date_utc" => "2011-05-31",
                    "end_date_utc" => "2011-05-31",
                    "start_time_utc" => "14:00:00",
                    "end_time_utc" => "15:00:00",
                ],
            ],
        ]);

        $this->start_date_end_date = new PodioDateItemField([
            '__api_values' => true,
            'field_id' => 5,
            'values' => [
                [
                    "start_date_utc" => "2011-05-31",
                    "end_date_utc" => "2011-06-08",
                    "start_time_utc" => null,
                    "end_time_utc" => null,
                ],
            ],
        ]);

        $this->start_datetime_end_date = new PodioDateItemField([
            '__api_values' => true,
            'field_id' => 6,
            'values' => [
                [
                    "start_date_utc" => "2011-05-31",
                    "end_date_utc" => "2011-06-08",
                    "start_time_utc" => "14:00:00",
                    "end_time_utc" => null,
                ],
            ],
        ]);

        $this->start_datetime_end_datetime = new PodioDateItemField([
            '__api_values' => true,
            'field_id' => 7,
            'values' => [
                [
                    "start_date_utc" => "2011-05-31",
                    "end_date_utc" => "2011-06-08",
                    "start_time_utc" => "14:00:00",
                    "end_time_utc" => "14:00:00",
                ],
            ],
        ]);

        $this->start_date_omitted_end = new PodioDateItemField([
            '__api_values' => true,
            'field_id' => 8,
            'values' => [
                [
                    "start_date_utc" => "2011-05-31",
                ],
            ],
        ]);
    }

    public function test_can_construct_from_simple_value(): void
    {
        $object = new PodioDateItemField([
            'field_id' => 123,
            'values' => ['start' => '2012-12-24'],
        ]);
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-24',
                'end_time_utc' => null,
            ],
        ], $object->__attribute('values'));
    }

    public function test_can_provide_values(): void
    {
        $this->assertNull($this->empty_values->values);

        $this->assertIsArray($this->start_date->values);
        $this->assertSame('2011-05-31 00:00:00', $this->start_date->values['start']->format('Y-m-d H:i:s'));
        $this->assertNull($this->start_date->values['end']);

        $this->assertIsArray($this->start_datetime->values);
        $this->assertSame('2011-05-31 14:00:00', $this->start_datetime->values['start']->format('Y-m-d H:i:s'));
        $this->assertNull($this->start_datetime->values['end']);

        $this->assertIsArray($this->start_datetime_with_endtime_same_day->values);
        $this->assertSame('2011-05-31 14:00:00', $this->start_datetime_with_endtime_same_day->values['start']->format('Y-m-d H:i:s'));
        $this->assertSame('2011-05-31 15:00:00', $this->start_datetime_with_endtime_same_day->values['end']->format('Y-m-d H:i:s'));

        $this->assertIsArray($this->start_date_end_date->values);
        $this->assertSame('2011-05-31 00:00:00', $this->start_date_end_date->values['start']->format('Y-m-d H:i:s'));
        $this->assertSame('2011-06-08 00:00:00', $this->start_date_end_date->values['end']->format('Y-m-d H:i:s'));

        $this->assertIsArray($this->start_datetime_end_date->values);
        $this->assertSame('2011-05-31 14:00:00', $this->start_datetime_end_date->values['start']->format('Y-m-d H:i:s'));
        $this->assertSame('2011-06-08 00:00:00', $this->start_datetime_end_date->values['end']->format('Y-m-d H:i:s'));

        $this->assertIsArray($this->start_datetime_end_datetime->values);
        $this->assertSame('2011-05-31 14:00:00', $this->start_datetime_end_datetime->values['start']->format('Y-m-d H:i:s'));
        $this->assertSame('2011-06-08 14:00:00', $this->start_datetime_end_datetime->values['end']->format('Y-m-d H:i:s'));

        $this->assertIsArray($this->start_date_omitted_end->values);
        $this->assertSame('2011-05-31 00:00:00', $this->start_date_omitted_end->values['start']->format('Y-m-d H:i:s'));
        $this->assertNull($this->start_date_omitted_end->values['end']);
    }

    public function test_can_provide_start_datetime(): void
    {
        $this->assertNull($this->empty_values->start);
        $this->assertInstanceOf(DateTime::class, $this->start_date->start);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime->start);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_with_endtime_same_day->start);
        $this->assertInstanceOf(DateTime::class, $this->start_date_end_date->start);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_date->start);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_datetime->start);
    }

    public function test_can_provide_start_date(): void
    {
        $this->assertNull($this->empty_values->start_date);
        $this->assertInstanceOf(DateTime::class, $this->start_date->start_date);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime->start_date);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_with_endtime_same_day->start_date);
        $this->assertInstanceOf(DateTime::class, $this->start_date_end_date->start_date);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_date->start_date);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_datetime->start_date);
    }

    public function test_can_provide_start_time(): void
    {
        $this->assertNull($this->empty_values->start_time);
        $this->assertNull($this->start_date->start_time);
        $this->assertSame('14:00:00', $this->start_datetime->start_time->format('H:i:s'));
        $this->assertSame('14:00:00', $this->start_datetime_with_endtime_same_day->start_time->format('H:i:s'));
        $this->assertNull($this->start_date_end_date->start_time);
        $this->assertSame('14:00:00', $this->start_datetime_end_date->start_time->format('H:i:s'));
        $this->assertSame('14:00:00', $this->start_datetime_end_datetime->start_time->format('H:i:s'));
    }

    public function test_can_provide_end_datetime(): void
    {
        $this->assertNull($this->empty_values->end);
        $this->assertNull($this->start_date->end);
        $this->assertNull($this->start_datetime->end);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_with_endtime_same_day->end);
        $this->assertInstanceOf(DateTime::class, $this->start_date_end_date->end);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_date->end);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_datetime->end);
    }

    public function test_can_provide_end_date(): void
    {
        $this->assertNull($this->empty_values->end_date);
        $this->assertNull($this->start_date->end_date);
        $this->assertNull($this->start_datetime->end_date);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_with_endtime_same_day->end_date);
        $this->assertInstanceOf(DateTime::class, $this->start_date_end_date->end_date);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_date->end_date);
        $this->assertInstanceOf(DateTime::class, $this->start_datetime_end_datetime->end_date);
    }

    public function test_can_provide_end_time(): void
    {
        $this->assertNull($this->empty_values->end_time);
        $this->assertNull($this->start_date->end_time);
        $this->assertNull($this->start_datetime->end_time);
        $this->assertSame('15:00:00', $this->start_datetime_with_endtime_same_day->end_time->format('H:i:s'));
        $this->assertNull($this->start_date_end_date->end_time);
        $this->assertNull($this->start_datetime_end_date->end_time);
        $this->assertSame('14:00:00', $this->start_datetime_end_datetime->end_time->format('H:i:s'));
    }

    public function test_can_provide_sameday(): void
    {
        $this->assertTrue($this->empty_values->same_day());
        $this->assertTrue($this->start_date->same_day());
        $this->assertTrue($this->start_datetime->same_day());
        $this->assertTrue($this->start_datetime_with_endtime_same_day->same_day());
        $this->assertFalse($this->start_date_end_date->same_day());
        $this->assertFalse($this->start_datetime_end_date->same_day());
        $this->assertFalse($this->start_datetime_end_datetime->same_day());
    }

    public function test_can_provide_allday(): void
    {
        $this->assertFalse($this->empty_values->all_day());
        $this->assertTrue($this->start_date->all_day());
        $this->assertFalse($this->start_datetime->all_day());
        $this->assertFalse($this->start_datetime_with_endtime_same_day->all_day());
        $this->assertTrue($this->start_date_end_date->all_day());
        $this->assertFalse($this->start_datetime_end_date->all_day());
        $this->assertFalse($this->start_datetime_end_datetime->all_day());
    }

    public function test_can_set_value_from_strings(): void
    {
        $object = new PodioDateItemField(['field_id' => 8]);

        $object->values = [
            'start' => '2012-12-24',
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-24',
                'end_time_utc' => null,
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => '2012-12-24 14:00:00',
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-24',
                'end_time_utc' => null,
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => '2012-12-24 14:00:00',
            'end' => '2012-12-24 15:00:00',
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-24',
                'end_time_utc' => '15:00:00',
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => '2012-12-24',
            'end' => '2012-12-25',
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-25',
                'end_time_utc' => null,
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => '2012-12-24 14:00:00',
            'end' => '2012-12-25',
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-25',
                'end_time_utc' => null,
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => '2012-12-24 14:00:00',
            'end' => '2012-12-25 15:00:00',
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-25',
                'end_time_utc' => '15:00:00',
            ],
        ], $object->__attribute('values'));
    }

    public function test_can_set_value_from_objects(): void
    {
        $tz = new DateTimeZone('UTC');
        $object = new PodioDateItemField(['field_id' => 8]);

        $object->values = [
            'start' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-24 00:00:00', $tz),
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '00:00:00',
                'end_date_utc' => '2012-12-24',
                'end_time_utc' => null,
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-24 14:00:00', $tz),
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-24',
                'end_time_utc' => null,
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-24 14:00:00', $tz),
            'end' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-24 15:00:00', $tz),
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-24',
                'end_time_utc' => '15:00:00',
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-24 00:00:00', $tz),
            'end' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-25 00:00:00', $tz),
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '00:00:00',
                'end_date_utc' => '2012-12-25',
                'end_time_utc' => '00:00:00',
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-24 14:00:00', $tz),
            'end' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-25 00:00:00', $tz),
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-25',
                'end_time_utc' => '00:00:00',
            ],
        ], $object->__attribute('values'));

        $object->values = [
            'start' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-24 14:00:00', $tz),
            'end' => DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-25 15:00:00', $tz),
        ];
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-24',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-25',
                'end_time_utc' => '15:00:00',
            ],
        ], $object->__attribute('values'));
    }

    public function test_can_set_start_from_string(): void
    {
        $this->start_date->start = '2012-12-30 14:00:00';
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-30',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => null,
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_start_date_from_string(): void
    {
        $this->start_datetime->start_date = '2012-12-30';
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-30',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => null,
            ],
        ], $this->start_datetime->__attribute('values'));
    }

    public function test_can_set_start_time_from_string(): void
    {
        $this->start_date->start_time = '14:00:00';
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2011-05-31',
                'end_time_utc' => null,
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_start_from_object(): void
    {
        $tz = new DateTimeZone('UTC');

        $this->start_date->start = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 14:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-30',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => null,
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_start_from_object_in_timezone(): void
    {
        $tz = new DateTimeZone('America/Los_Angeles');

        $this->start_date->start = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 14:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-30',
                'start_time_utc' => '22:00:00',
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => null,
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_remove_start_time(): void
    {
        $this->start_datetime->start_time = null;
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => null,
                'end_date_utc' => '2011-05-31',
                'end_time_utc' => null,
            ],
        ], $this->start_datetime->__attribute('values'));
    }

    public function test_can_remove_start_date(): void
    {
        $this->start_datetime->start_date = null;
        $this->assertSame([
            [
                'start_date_utc' => null,
                'start_time_utc' => '14:00:00',
                'end_date_utc' => null,
                'end_time_utc' => null,
            ],
        ], $this->start_datetime->__attribute('values'));
    }

    public function test_can_set_start_date_from_object(): void
    {
        $tz = new DateTimeZone('UTC');

        $this->start_date->start_date = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 14:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2012-12-30',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => null,
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_start_time_from_object(): void
    {
        $tz = new DateTimeZone('UTC');

        $this->start_date->start_time = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 14:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2011-05-31',
                'end_time_utc' => null,
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_end_from_string(): void
    {
        $this->start_date->end = '2012-12-30 14:00:00';
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => '14:00:00',
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_end_date_from_string(): void
    {
        $this->start_datetime->end_date = '2012-12-31';
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2012-12-31',
                'end_time_utc' => null,
            ],
        ], $this->start_datetime->__attribute('values'));
    }

    public function test_can_set_end_time_from_string(): void
    {
        $this->start_datetime->end_time = '15:00:00';
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2011-05-31',
                'end_time_utc' => '15:00:00',
            ],
        ], $this->start_datetime->__attribute('values'));
    }

    public function test_can_remove_end_time(): void
    {
        $this->start_datetime_end_datetime->end_time = null;
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2011-06-08',
                'end_time_utc' => null,
            ],
        ], $this->start_datetime_end_datetime->__attribute('values'));
    }

    public function test_can_remove_end_date(): void
    {
        $this->start_datetime_end_datetime->end_date = null;
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2011-05-31',
                'end_time_utc' => '14:00:00',
            ],
        ], $this->start_datetime_end_datetime->__attribute('values'));
    }

    public function test_can_set_end_from_object(): void
    {
        $tz = new DateTimeZone('UTC');

        $this->start_date->end = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 14:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => '14:00:00',
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_end_from_object_in_timezone(): void
    {
        $tz = new DateTimeZone('America/Los_Angeles');

        $this->start_date->end = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 14:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => '22:00:00',
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_end_date_from_object(): void
    {
        $tz = new DateTimeZone('UTC');

        $this->start_date->end_date = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 14:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => null,
                'end_date_utc' => '2012-12-30',
                'end_time_utc' => null,
            ],
        ], $this->start_date->__attribute('values'));
    }

    public function test_can_set_end_time_from_object(): void
    {
        $tz = new DateTimeZone('UTC');

        $this->start_datetime->end_time = DateTime::createFromFormat('Y-m-d H:i:s', '2012-12-30 15:00:00', $tz);
        $this->assertSame([
            [
                'start_date_utc' => '2011-05-31',
                'start_time_utc' => '14:00:00',
                'end_date_utc' => '2011-05-31',
                'end_time_utc' => '15:00:00',
            ],
        ], $this->start_datetime->__attribute('values'));
    }

    public function test_can_humanize_value(): void
    {
        $this->assertSame('', $this->empty_values->humanized_value());
        $this->assertSame('2011-05-31', $this->start_date->humanized_value());
        $this->assertSame('2011-05-31 14:00', $this->start_datetime->humanized_value());
        $this->assertSame('2011-05-31 14:00 - 15:00', $this->start_datetime_with_endtime_same_day->humanized_value());
        $this->assertSame('2011-05-31 - 2011-06-08', $this->start_date_end_date->humanized_value());
        $this->assertSame('2011-05-31 14:00 - 2011-06-08', $this->start_datetime_end_date->humanized_value());
        $this->assertSame('2011-05-31 14:00 - 2011-06-08 14:00', $this->start_datetime_end_datetime->humanized_value());
    }

    public function test_can_convert_to_api_friendly_json(): void
    {
        $this->assertSame('[]', $this->empty_values->as_json());
        $this->assertSame('{"start_date":"2011-05-31","end_date":null}', $this->start_date->as_json());
        $this->assertSame('{"start_utc":"2011-05-31 14:00:00","end_date":null}', $this->start_datetime->as_json());
        $this->assertSame('{"start_utc":"2011-05-31 14:00:00","end_utc":"2011-05-31 15:00:00"}', $this->start_datetime_with_endtime_same_day->as_json());
        $this->assertSame('{"start_date":"2011-05-31","end_date":"2011-06-08"}', $this->start_date_end_date->as_json());
        $this->assertSame('{"start_utc":"2011-05-31 14:00:00","end_date":"2011-06-08"}', $this->start_datetime_end_date->as_json());
        $this->assertSame('{"start_utc":"2011-05-31 14:00:00","end_utc":"2011-06-08 14:00:00"}', $this->start_datetime_end_datetime->as_json());
    }

    public function testGetValueWithStartOnly(): void {
        $date = $this->start_date_omitted_end->getValue();
        $this->assertEquals("2011-05-31", $date->format("Y-m-d"));
    }

    public function testGetValueWithDatetimeStartAndEnd(): void {
        $dates = $this->start_datetime_end_datetime->getValue();
        $this->assertEquals("2011-05-31 14:00:00", $dates["start"]->format("Y-m-d H:i:s"));
        $this->assertEquals("2011-06-08 14:00:00", $dates["end"]->format("Y-m-d H:i:s"));
    }
}
