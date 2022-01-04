<?php

    class PodioItemParser {
        const TEXT_TYPE = "text";
        const NUMBER_TYPE = "number";
        const EMAIL_TYPE = "email";
        const PHONE_TYPE = "phone";
        const CATEGORY_TYPE = "category";
        const DATE_TYPE = "date";
        const CURRENCY_TYPE = "money";
        const CONTACT_TYPE = "contact";
        const RELATIONSHIP_TYPE = "app";
        const ADDRESS_TYPE = "location";

        protected PodioItem $item;

        public function __construct(PodioItem $item) {
            $this->item = $item;
        }

        /**
         * @return PodioItemField[]
         */
        public function getFieldsByLabels(array $labels): array {
            $fields = [];
            foreach ($labels as $label) {
                try {
                    array_push($fields, $this->getFieldByLabel($label));
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
            return $fields;
        }

        /**
         * @return PodioItemField[]
         */
        public function getFieldsByRegexLabels(array $regexPatterns): array {
            $fields = [];
            foreach ($regexPatterns as $regexPattern) {
                try {
                    array_push($fields, $this->getFieldByRegexLabel($regexPattern));
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
            return $fields;
        }

        public function getFieldValueByLabel(string $label): mixed {
            $field = $this->getFieldByLabel($label);
            return self::getFieldValue($field);
        }

        public function getFieldByLabel(string $label): PodioItemField {
            if (!empty($this->item->fields)) {
                foreach ($this->item->fields as $fieldId => $field) {
                    if ($field->label == $label) {
                        return $field;
                    }
                }
            }
            throw new Exception("No field labeled \"$label\" could be found in the PodioItem {$this->item->item_id}.");
        }

        public function getOptionalFieldByLabel(string $label): PodioItemField|null {
            try {
                return $this->getFieldByLabel($label);
            } catch (Exception) {
                return null;
            }
        }

        public function getOptionalFieldValueByLabel(string $label): mixed {
            try {
                return $this->getFieldValueByLabel($label);
            } catch (Exception) {
                return null;
            }
        }

        public function getFieldByRegexLabel(string $regexPattern): PodioItemField {
            foreach ($this->item->fields as $fieldId => $field) {
                if (preg_match($regexPattern, $field->label)) {
                    return $field;
                }
            }
            throw new Exception("No field label in the PodioItem {$this->item->item_id} matches the regex pattern \"$regexPattern\".");
        }

        public static function getFieldValue(PodioItemField $field): mixed {
            switch ($field->type) {
                case self::TEXT_TYPE:
                    return self::getTextValue($field);
                case self::NUMBER_TYPE:
                    return self::getNumberValue($field);
                case self::EMAIL_TYPE:
                    return self::getEmailValue($field);
                case self::PHONE_TYPE:
                    return self::getPhoneValue($field);
                case self::CATEGORY_TYPE:
                    return self::getCategoryValue($field);
                case self::DATE_TYPE:
                    return self::getDateValue($field);
                case self::ADDRESS_TYPE:
                    return self::getAddressValue($field);
                case self::CURRENCY_TYPE:
                    return self::getCurrencyValue($field);
                case self::CONTACT_TYPE:
                    return self::getContactValue($field);
                case self::RELATIONSHIP_TYPE:
                    return self::getRelationshipValue($field);
                default:
                    return $field->values;
            }
        }

        public static function getTextValue(PodioTextItemField $field): string {
            return $field->values;
        }

        public static function getNumberValue(PodioItemField $field): float {
            return floatval($field->values);
        }

        public static function getEmailValue(PodioEmailItemField $field): string|array {
            if (count($field->values) > 1) {
                $emails = [];
                foreach ($field->values as $value) {
                    $type = gettype($value);
                    if ($type === "array") {
                        $emails[] = $value["value"];
                    } else if ($type === "object") {
                        $emails[] = $value->value;
                    }
                }
                return $emails;
            } else {
                $type = gettype($field->values[0]);
                if ($type === "array") {
                    return $field->values[0]["value"];
                } else if ($type === "object") {
                    return $field->values[0]->value;
                }
            }
        }

        public static function getPhoneValue(PodioPhoneItemField $field): string {
            if (count($field->values) > 1) {
                $phoneNumbers = [];
                foreach ($field->values as $value) {
                    $type = gettype($value);
                    if ($type === "array") {
                        $phoneNumbers[] = $value["value"];
                    } else if ($type === "object") {
                        $phoneNumbers[] = $value->value;
                    }
                }
                return $phoneNumbers;
            } else {
                $type = gettype($field->values[0]);
                if ($type === "array") {
                    return $field->values[0]["value"];
                } else if ($type === "object") {
                    return $field->values[0]->value;
                }
            }
        }

        public static function getCategoryValue(PodioCategoryItemField $field): string|array {
            if (count($field->values) > 0) {
                if (self::fieldHasMultiple($field)) {
                    $values = [];
                    foreach ($field->values as $option) {
                        array_push($values, $option["text"]);
                    }
                    return $values;
                }
                return $field->values[0]["text"];
            }
            return "";
        }

        public static function getDateValue(PodioDateItemField $field): DateTime|array|null {
            if (self::dateFieldHasEndDate($field)) {
                return $field->values;
            } else {
                return $field->start;
            }
        }

        private static function dateFieldHasEndDate(PodioDateItemField $field): bool {
            $configType = gettype($field->config);
            if ($configType === "array") {
                return $field->config["settings"]["end"];
            } else if ($configType === "object") {
                return $field->config->settings["end"];
            }
            throw new \Exception("The config property in this PodioItemField is not an acceptable type: $configType.");
        }

        public static function getAddressValue(PodioLocationItemField $field): string {
            return $field->text;
        }

        public static function getCurrencyValue(PodioMoneyItemField $field): float {
            return floatval($field->amount);
        }

        /**
         * @param PodioContactItemField $field
         * @return PodioContact[]
         */
        public static function getContactValue(PodioContactItemField $field): array {
            return $field->values->_get_items();
        }

        /**
         * @param PodioAppItemField $field
         * @return PodioItem|PodioItem[]
         */
        public static function getRelationshipValue(PodioAppItemField $field): PodioItem|array|null {
            if (self::fieldHasMultiple($field)) {
                return $field->values->_get_items();
            } else {
                return $field->values->offsetGet(0);
            }
        }

        private static function fieldHasMultiple(PodioItemField $field): bool {
            $configType = gettype($field->config);
            if ($configType === "array") {
                return $field->config["settings"]["multiple"];
            } else if ($configType === "object") {
                return $field->config->settings["multiple"];
            }
            throw new \Exception("The config property in this PodioItemField is not an acceptable type: $configType.");
        }
    }

?>
