<?php

    namespace Podio\FieldTools;

    use DateTime;
    use UnexpectedValueException;
    use Exception;

    class CalculationFieldRawValueConverter {
        protected mixed $rawValue;

        public function __construct(mixed $rawValue) {
            $this->rawValue = $rawValue;
        }

        public function getValue(): mixed {
            $functionName = $this->getConversionFunctionName();
            return call_user_func([self::class, $functionName]);
        }

        protected function getConversionFunctionName(): string {
            return "get" . $this->getCapitalizedType() . "Value";
        }

        protected function getCapitalizedType(): string {
            $lower = $this->getLowercaseType();
            return ucfirst($lower);
        }

        protected function getLowercaseType(): string {
            $type = gettype($this->rawValue);
            return strtolower($type);
        }

        protected function getStringValue(): string|int|float {
            if (is_numeric($this->rawValue)) {
                return $this->getStringValueAsNumber();
            } else {
                return $this->rawValue;
            }
        }

        protected function getStringValueAsNumber(): int|float {
            if (is_int($this->rawValue)) {
                return intval($this->rawValue);
            } else {
                return floatval($this->rawValue);
            }
        }

        protected function getFloatValue(): float {
            return $this->rawValue;
        }

        protected function getIntegerValue(): int {
            return $this->rawValue;
        }

        protected function getArrayValue(): mixed {
            if ($this->isDate()) {
                return $this->getDateValue();
            } else {
                throw new Exception("The Calculation Field Converter does not recognize this array pattern.");
            }
        }

        protected function isDate(): bool {
            return $this->hasStartDate();
        }

        protected function hasStartDate(): bool {
            $possibleKeys = ["start", "start_date", "start_time", "start_date_utc", "start_time_utc"];
            foreach ($possibleKeys as $possibleKey) {
                if (array_key_exists($possibleKey, $this->rawValue)) {
                    return true;
                }
            }
            return false;
        }

        /**
         * @return DateTime|DateTime[]
         */
        protected function getDateValue(): DateTime|array {
            $dateParser = new DateValueParser($this->rawValue);
            return $dateParser->getValue();
        }

        protected function getNullValue() {
            return null;
        }
    }

?>