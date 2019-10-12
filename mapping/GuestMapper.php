<?php

/**
 * Mapper for {@link Guest} 
 * @see GuestValidator
 */
final class GuestMapper {

    private function __construct() {
    }

    /**
     * Maps array to the given {@link Guest}.
     * <p>
     * Expected properties are:
     * <ul>
     *   <li>id</li>
     *   <li>guest_unit_id</li>
     *   <li>guest_age</li>
     *   <li>meal_id</li>
     *   <li>extra_cost</li>
     *   <li>specials</li>
     * </ul>
     * @param Guest $attendee
     * @param array $properties
     */

    public static function map( Guest $attendee, array $properties ) {
        if (array_key_exists('id', $properties)) {
            $attendee->setId($properties['id']);
        }
        if (array_key_exists('guest_unit_id', $properties)) {
            $attendee->setUnitId($properties['guest_unit_id']);
        }
        if (array_key_exists('guest_age', $properties)) {
            $attendee->setAgeGroup($properties['guest_age']);
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
