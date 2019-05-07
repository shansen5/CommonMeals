<?php

/**
 * Mapper for {@link MealAttendee} 
 * @see MealAttendeeValidator
 */
final class MealAttendeeMapper {

    private function __construct() {
    }

    /**
     * Maps array to the given {@link MealAttendee}.
     * <p>
     * Expected properties are:
     * <ul>
     *   <li>id</li>
     *   <li>person_id</li>
     *   <li>meal_id</li>
     *   <li>extra_cost</li>
     *   <li>specials</li>
     * </ul>
     * @param MealAttendee $attendee
     * @param array $properties
     */

    public static function map( MealAttendee $attendee, array $properties ) {
        if (array_key_exists('id', $properties)) {
            $attendee->setId($properties['id']);
        }
        if (array_key_exists('person_id', $properties)) {
            $attendee->setPersonId($properties['person_id']);
        }
        if (array_key_exists('extra_cost', $properties)) {
            $attendee->setExtraCost($properties['extra_cost']);
        }
        if (array_key_exists('meal_id', $properties)) {
            $attendee->setMealId($properties['meal_id']);
        }
        if (array_key_exists('specials', $properties)) {
            $attendee->setSpecials(json_decode($properties['specials']));
        }
    }

}
