<?php

namespace Podio;

/**
 * phone ore email field
 */
abstract class PodioPhoneOrEmailItemField extends PodioItemField
{
    public function humanized_value()
    {
        if (!$this->values) {
            return '';
        }

        $values = array();
        foreach ($this->values as $value) {
            $values[] = $value['type'] . ': ' . $value['value'];
        }
        return join(';', $values);
    }

    public function api_friendly_values()
    {
        return $this->values ? $this->values : array();
    }

    public function getValue(): array|string {
        $values = $this->values;
        if (count($values) !== 1) {
            $cleanedValues = [];
            foreach ($values as $value) {
                $cleanedValues[] = $value["value"];
            }
            return $cleanedValues;
        }
        return $values[0]["value"];
    }
}
