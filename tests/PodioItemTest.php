<?php

namespace Podio\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Podio\PodioApp;
use Podio\PodioItem;
use Podio\PodioItemFieldCollection;
use Podio\PodioTextItemField;
use Podio\PodioMissingRelationshipError;

class PodioItemTest extends TestCase
{
    public function test_create_item(): void
    {
        $item = new PodioItem([
            'app' => new PodioApp(1234),
            'fields' => new PodioItemFieldCollection([
                new PodioTextItemField(["external_id" => "title", "values" => "TEST"]),
            ])
        ]);

        $this->assertEquals(1234, $item->app->id);
    }


    public function test_save_should_throw_error_if_app_id_missing(): void
    {
        $this->expectException(PodioMissingRelationshipError::class);
        $item = new PodioItem([
            'fields' => new PodioItemFieldCollection([
                new PodioTextItemField(["external_id" => "title", "values" => "TEST"]),
            ])
        ]);
        $item->save();
    }
}
