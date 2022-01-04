<?php

namespace Podio\Tests;

use PHPUnit\Framework\TestCase;
use Podio\PodioItem;
use Podio\PodioItemCollection;
use Podio\PodioObject;
use Podio\PodioDataIntegrityError;

class PodioItemCollectionTest extends TestCase
{
    public function test_cannot_add_object(): void
    {
        $this->expectException(PodioDataIntegrityError::class);
        $collection = new PodioItemCollection();
        $collection[] = new PodioObject();
    }

    public function test_can_add_item(): void
    {
        $collection = new PodioItemCollection();
        $length = count($collection);
        $collection[] = new PodioItem(['item_id' => 1, 'external_id' => 'a']);

        $this->assertCount($length + 1, $collection);
    }
}
