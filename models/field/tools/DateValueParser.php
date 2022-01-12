<?php

    namespace Podio\FieldTools;

    use DateTime;
    use UnexpectedValueException;

    class DateValueParser {
        protected array $values;

        public function __construct(array $values) {
            $this->values = $values;
        }

        /**
         * @return DateTime|DateTime[]
         */
        public function getValue(): DateTime|array {
            $startDate = $this->getStartDateValue();
            if ($this->hasEndDate()) {
                $endDate = $this->getEndDateValue();
                return ["start" => $startDate, "end" => $endDate];
            }
            return $startDate;
        }

        protected function getStartDateValue(): DateTime {
            if (array_key_exists("start", $this->values) && !empty($this->values["start"])) {
                if (is_a($this->values["start"], DateTime::class)) {
                    return $this->values["start"];
                } else {
                    $startType = gettype($this->values["start"]);
                    if ($startType === "string") {
                        return new DateTime($this->values["start"]);
                    } else {
                        throw new UnexpectedValueException("The start property in the date array must either be a string or a DateTime object, instead a $startType was given.");
                    }
                }
            } else if (array_key_exists("start_date", $this->values) && !empty($this->values["start_date"])) {
                if (is_a($this->values["start_date"], DateTime::class)) {
                    return $this->values["start_date"];
                } else {
                    $startDateType = gettype($this->values["start_date"]);
                    if ($startDateType === "string") {
                        if (array_key_exists("start_time", $this->values)) {
                            $startTimeType = gettype($this->values["start_time"]);
                            if ($startTimeType === "string") {
                                return new DateTime($this->values["start_date"] . " " . $this->values["start_time"]);
                            } else {
                                throw new UnexpectedValueException("The start_time property in the date array must be a string, instead a $startTimeType was given.");
                            }
                        } else {
                            return new DateTime($this->values["start_date"]);
                        }
                    } else {
                        throw new UnexpectedValueException("The start_date property in the date array must either be a string or a DateTime object, instead a $startDateType was given.");
                    }
                }
            } else if (array_key_exists("start_utc", $this->values) && !empty($this->values["start_utc"])) {
                if (is_a($this->values["start_utc"], DateTime::class)) {
                    return $this->values["start_utc"];
                } else {
                    $utcStartType = gettype($this->values["start_utc"]);
                    if ($utcStartType === "string") {
                        return new DateTime($this->values["start_utc"]);
                    } else {
                        throw new UnexpectedValueException("The start_utc property in the date array must either be a string or a DateTime object, instead a $utcStartType was given.");
                    }
                }
            } else if (array_key_exists("start_date_utc", $this->values) && !empty($this->values["start_date_utc"])) {
                if (is_a($this->values["start_date_utc"], DateTime::class)) {
                    return $this->values["start_date_utc"];
                } else {
                    $utcStartDateType = gettype($this->values["start_date_utc"]);
                    if ($utcStartDateType === "string") {
                        if (array_key_exists("start_time_utc", $this->values)) {
                            $utcStartTimeType = gettype($this->values["start_time_utc"]);
                            if ($utcStartTimeType === "string") {
                                return new DateTime($this->values["start_date_utc"] . " " . $this->values["start_time_utc"]);
                            } else {
                                throw new UnexpectedValueException("The start_time property in the date array must be a string, instead a $utcStartTimeType was given.");
                            }
                        } else {
                            return new DateTime($this->values["start_date_utc"]);
                        }
                    } else {
                        throw new UnexpectedValueException("The start_date_utc property in the date array must either be a string or a DateTime object, instead a $utcStartDateType was given.");
                    }
                }
            } else {
                throw new UnexpectedValueException("The date value does not contain valid data for a start date.");
            }
        }

        protected function hasEndDate(): bool {
            $allKeys = array_keys($this->values);
            $endKeys = ["end", "end_date", "end_utc", "end_date_utc"];
            foreach ($endKeys as $endKey) {
                if (in_array($endKey, $allKeys) && !empty($this->values[$endKey])) {
                    return true;
                }
            }
            return false;
        }

        protected function getEndDateValue(): DateTime {
            if (array_key_exists("end", $this->values) && !empty($this->values["end"])) {
                if (is_a($this->values["end"], DateTime::class)) {
                    return $this->values["end"];
                } else {
                    $endType = gettype($this->values["end"]);
                    if ($endType === "string") {
                        return new DateTime($this->values["end"]);
                    } else {
                        throw new UnexpectedValueException("The end property in the date array must either be a string or a DateTime object, instead a $startType was given.");
                    }
                }
            } else if (array_key_exists("end_date", $this->values) && !empty($this->values["end_date"])) {
                if (is_a($this->values["end_date"], DateTime::class)) {
                    return $this->values["end_date"];
                } else {
                    $endDateType = gettype($this->values["end_date"]);
                    if ($endDateType === "string") {
                        if (array_key_exists("end_time", $this->values)) {
                            $endTimeType = gettype($this->values["end_time"]);
                            if ($endTimeType === "string") {
                                return new DateTime($this->values["end_date"] . " " . $this->values["end_time"]);
                            } else {
                                throw new UnexpectedValueException("The end_time property in the date array must be a string, instead a $endTimeType was given.");
                            }
                        } else {
                            return new DateTime($this->values["end_date"]);
                        }
                    } else {
                        throw new UnexpectedValueException("The end_date property in the date array must either be a string or a DateTime object, instead a $endDateType was given.");
                    }
                }
            } else if (array_key_exists("end_utc", $this->values) && !empty($this->values["end_utc"])) {
                if (is_a($this->values["end_utc"], DateTime::class)) {
                    return $this->values["end_utc"];
                } else {
                    $utcEndType = gettype($this->values["end_utc"]);
                    if ($utcEndType === "string") {
                        return new DateTime($this->values["end_utc"]);
                    } else {
                        throw new UnexpectedValueException("The end_utc property in the date array must either be a string or a DateTime object, instead a $utcEndType was given.");
                    }
                }
            } else if (array_key_exists("end_date_utc", $this->values) && !empty($this->values["end_date_utc"])) {
                if (is_a($this->values["end_date_utc"], DateTime::class)) {
                    return $this->values["end_date_utc"];
                } else {
                    $utcEndDateType = gettype($this->values["end_date_utc"]);
                    if ($utcEndDateType === "string") {
                        if (array_key_exists("end_time_utc", $this->values)) {
                            $utcEndTimeType = gettype($this->values["end_time_utc"]);
                            if ($utcEndTimeType === "string") {
                                return new DateTime($this->values["end_date_utc"] . " " . $this->values["end_time_utc"]);
                            } else {
                                throw new UnexpectedValueException("The end_time property in the date array must be a string, instead a $utcEndTimeType was given.");
                            }
                        } else {
                            return new DateTime($this->values["end_date_utc"]);
                        }
                    } else {
                        throw new UnexpectedValueException("The end_date_utc property in the date array must either be a string or a DateTime object, instead a $utcEndDateType was given.");
                    }
                }
            } else {
                throw new UnexpectedValueException("The date value does not contain valid data for a end date.");
            }
        }
    }

?>