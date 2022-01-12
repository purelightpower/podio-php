<?php

namespace Podio\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Podio\PodioItem;
use Podio\PodioTextItemField;
use Podio\PodioItemFieldCollection;
use Podio\PodioMissingRelationshipError;
use Podio\PodioDataIntegrityError;

class PodioItemFieldTest extends TestCase
{
    public function test_save_should_throw_error_if_relationship_to_item_missing(): void
    {
        $this->expectException(PodioMissingRelationshipError::class);
        $itemField = new PodioTextItemField();
        $itemField->save();
    }

    public function test_save_should_throw_error_if_external_id_missing(): void
    {
        $this->expectException(PodioDataIntegrityError::class);
        $itemField = new PodioTextItemField();
        // assure relationship to item is present:
        new PodioItem(['fields' => new PodioItemFieldCollection([$itemField])]);

        $itemField->save();
    }
}
