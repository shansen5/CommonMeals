<?php

/**
 * Mapper for {@link Meal} 
 * @see MealValidator
 */
final class MealMapper {

    private function __construct() {
    }

    /**
     * Maps array to the given {@link Neal}.
     * <p>
     * Expected properties are:
     * <ul>
     *   <li>id</li>
     *   <li>meal_team_id</li>
     *   <li>summary</li>
     *   <li>details</li>
     *   <li>meal_datetime</li>
     *   <li>tags</li>
     *   <li>person_id</li>
     *   <li>deadline</li>
     * </ul>
     * @param Meal $meal
     * @param array $properties
     */

    public static function map( Meal $meal, array $properties ) {
        if (array_key_exists('id', $properties)) {
            $meal->setId($properties['id']);
        }
        if (array_key_exists('meal_team_id', $properties)) {
            $meal->setMealTeamId($properties['meal_team_id']);
        }
        if (array_key_exists('summary', $properties)) {
            $meal->setSummary($properties['summary']);
        }
        if (array_key_exists('details', $properties)) {
            $meal->setDetails($properties['details']);
        }
        if (array_key_exists('meal_datetime', $properties)) {
            $meal->setDateTime($properties['meal_datetime']);
        }
        if (array_key_exists('deadline', $properties)) {
            $meal->setDeadline($properties['deadline']);
        }
        if (array_key_exists('meal_date', $properties)) {
            $meal->setDate($properties['meal_date']);
        }
        if (array_key_exists('meal_time', $properties)) {
            $meal->setTime($properties['meal_time']);
        }
        if (array_key_exists('deadline_date', $properties)) {
            $meal->setDeadlineDate($properties['deadline_date']);
        }
        if (array_key_exists('deadline_time', $properties)) {
            $meal->setDeadlineTime($properties['deadline_time']);
        }
        if (array_key_exists('tags', $properties)) {
            $meal->setTags($properties['tags']);
        }
        if (array_key_exists('meal_cost', $properties)) {
            $meal->setMealCost($properties['meal_cost']);
        }
        if (array_key_exists('meal_cost_1', $properties)) {
            $meal->setMealCost1($properties['meal_cost_1']);
        }
        if (array_key_exists('meal_cost_2', $properties)) {
            $meal->setMealCost2($properties['meal_cost_2']);
        }
        if (array_key_exists('sign_up_limit', $properties)) {
            $meal->setSignUpLimit($properties['sign_up_limit']);
        }
        if (array_key_exists('person_id', $properties)) {
            $meal->setPersonId($properties['person_id']);
        }
    }

}
