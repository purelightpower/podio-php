<?php

namespace Podio;

use OutOfBoundsException;
use UnexpectedValueException;

/**
 * @see https://developers.podio.com/doc/items
 */
abstract class PodioItemField extends PodioObject
{
    public function __construct($attributes = array(), $force_type = null)
    {
        $this->property('field_id', 'integer', array('id' => true));
        $this->property('type', 'string');
        $this->property('external_id', 'string');
        $this->property('label', 'string');
        $this->property('values', 'array');
        $this->property('config', 'hash');
        $this->property('status', 'string');

        $this->init($attributes);

        $this->set_type_from_class_name();
    }

    /**
     * Saves the value of the field
     */
    public function save($options = array())
    {
        $relationship = $this->relationship();
        if (!$relationship) {
            throw new PodioMissingRelationshipError('{"error_description":"Field is missing relationship to item", "request": {}}', null, null);
        }
        if (!$this->id && !$this->external_id) {
            throw new PodioDataIntegrityError('Field must have id or external_id set.');
        }
        $attributes = $this->as_json(false);
        return self::update($relationship['instance']->id, $this->id ? $this->id : $this->external_id, $attributes, $options);
    }

    /**
     * Calling parent so we get all field attributes printed instead of only api_friendly_values
     */
    public function __toString()
    {
        return print_r(parent::as_json(false), true);
    }

    /**
     * Overwrites normal as_json to use api_friendly_values
     */
    public function as_json($encoded = true)
    {
        $result = $this->api_friendly_values();
        return $encoded ? json_encode($result) : $result;
    }

    /**
     * @see https://developers.podio.com/doc/items/update-item-field-values-22367
     */
    public static function update($item_id, $field_id, $attributes = array(), $options = array())
    {
        $url = Podio::url_with_options("/item/{$item_id}/value/{$field_id}", $options);
        return Podio::put($url, $attributes)->json_body();
    }

    /**
     * @see https://developers.podio.com/doc/calendar/get-item-field-calendar-as-ical-10195681
     */
    public static function ical($item_id, $field_id)
    {
        return Podio::get("/calendar/item/{$item_id}/field/{$field_id}/ics/")->body;
    }

    /**
     * @see https://developers.podio.com/doc/calendar/get-item-field-calendar-as-ical-10195681
     */
    public static function ical_field($item_id, $field_id)
    {
        return Podio::get("/calendar/item/{$item_id}/field/{$field_id}/ics/")->body;
    }

    public function set_type_from_class_name()
    {
        switch (get_class($this)) {
			case PodioTextItemField::class:
				$this->type = 'text';
				break;
			case PodioEmbedItemField::class:
				$this->type = 'embed';
				break;
			case PodioLocationItemField::class:
				$this->type = 'location';
				break;
			case PodioDateItemField::class:
				$this->type = 'date';
				break;
			case PodioContactItemField::class:
				$this->type = 'contact';
				break;
			case PodioAppItemField::class:
				$this->type = 'app';
				break;
			case PodioCategoryItemField::class:
				$this->type = 'category';
				break;
			case PodioImageItemField::class:
				$this->type = 'image';
				break;
			case PodioFileItemField::class:
				$this->type = 'file';
				break;
			case PodioNumberItemField::class:
				$this->type = 'number';
				break;
			case PodioProgressItemField::class:
				$this->type = 'progress';
				break;
			case PodioDurationItemField::class:
				$this->type = 'duration';
				break;
			case PodioCalculationItemField::class:
				$this->type = 'calculation';
				break;
			case PodioMoneyItemField::class:
				$this->type = 'money';
				break;
			case PodioPhoneItemField::class:
				$this->type = 'phone';
				break;
			case PodioEmailItemField::class:
				$this->type = 'email';
				break;
			case PodioTagItemField::class:
				$this->type = 'tag';
				break;
			default:
				break;
      	}
    }

    public function hasMultiple(): bool {
        try {
			$hasMultiple = $this->getConfigProperty("settings.multiple");
			$type = gettype($hasMultiple);
			if ($type === "boolean") {
				return $hasMultiple;
			} else {
				throw new UnexpectedValueException("The config.settings.multiple property for the field is supposed to be a boolean value. A $type type was returned.");
			}
		} catch (OutOfBoundsException) {
			return false;
		} catch (UnexpectedValueException $error) {
			throw new PodioDataIntegrityError("\"error_description\": \"{$error->getMessage()}\", \"response\": {}");
		}
    }

	protected function getConfigProperty(string $key): mixed {
		$properties = explode(".", $key);
		$currentKey = "config";
		$currentLevel = $this->__get("config");
		foreach ($properties as $property) {
			$levelType = gettype($currentLevel);
			if ($levelType === "array") {
				if (array_key_exists($property, $currentLevel)) {
					$currentLevel = $currentLevel[$property];
				} else {
					throw new OutOfBoundsException("There is no config property named $key for this field.");
				}
			} else if ($levelType === "object") {
				if (property_exists($currentLevel, $property)) {
					$currentLevel = $currentLevel->{$property};
				} else {
					throw new OutOfBoundsException("There is no config property named $key for this field.");
				}
			} else {
				throw new UnexpectedValueException("The $currentKey property for the field is supposed to be an object or an array. Instead a $levelType was provided.");
			}
			$currentKey .= ".$property";
		}
		return $currentLevel;
	}

	protected function configIsArray(): bool {
		$type = gettype($this->__get("config"));
		return $type === "array";
	}

	protected function configIsObject(): bool {
		$type = gettype($this->__get("config"));
		return $type === "object";
	}

	abstract function getValue(): mixed;
}
