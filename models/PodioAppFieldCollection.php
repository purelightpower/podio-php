<?php

namespace Podio;

/**
 * Collection for managing a list of PodioAppField objects.
 */
class PodioAppFieldCollection extends PodioFieldCollection
{
    /**
     * Constructor. Pass in either decoded JSON from an API request
     * or an array of PodioAppField objects.
     */
    public function __construct($attributes)
    {

    // Make default array into array of proper objects
        $fields = array();

        foreach ($attributes as $field_attributes) {
            $field = is_object($field_attributes) ? $field_attributes : new PodioAppField($field_attributes);
            $fields[] = $field;
        }

        // Add to internal storage
        parent::__construct($fields);
    }

    /**
     * Array access. Add field to collection.
     */
    public function offsetSet(mixed $offset, mixed $field): void
    {
        if (!is_a($field, PodioAppField::class)) {
            throw new PodioDataIntegrityError("Objects in PodioAppFieldCollection must be of class PodioAppField");
        }

        parent::offsetSet($offset, $field);
    }

    public function labelGet(string $label): PodioAppField
    {
        $object = parent::labelGet($label);
        if (is_a($object, PodioAppField::class)) {
            return $object;
        }
        return new PodioAppField($object);
    }

    public function getExternalIdByLabel(string $label): string
    {
        $field = $this->labelGet($label);
        return $field->external_id;
    }
}
