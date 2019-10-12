<?php

/**
 * Validator for {@link AbstractMealAttendee}.
 * @see GuestMapper
 */
final class AttendeeValidator {

    private function __construct() {
    }

    /**
     * Validate the given {@link Guest} instance.
     * @param Guest $age {@link Guest} instance to be validated
     * @return array array of {@link RError} s
     */
    public static function validateAge($age) {
        $errors = array();
        if ( !in_array( $age, AbstractMealAttendee::getAgeGroups() )) {
            $errors[] = new RError('age', 'Invalid age.' );
        }
        return $errors;
    }

}
