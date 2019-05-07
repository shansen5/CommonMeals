<?php

/**
 * Validator for {@link Meal}.
 * @see MealMapper
 */
final class MealValidator {

    private function __construct() {
    }

    /**
     * Validate the given {@link Meal} instance.
     * @param Meal $unit {@link Meal} instance to be validated
     * @return array array of {@link RError} s
     */
    public static function validate(Meal $meal) {
        $errors = array();
        if (!$meal->getSummary()) {
            $errors[] = new RError('summary', 'Summary cannot be empty.' );
        }
        if (!$meal->getDateTime()) {
            $errors[] = new RError('meal_datetime', 'Meal date cannot be empty.' );
        }
        if (!$meal->getDeadline()) {
            $errors[] = new RError('deadline', 'Signup deadline cannot be empty.' );
        }
        return $errors;
    }

}
