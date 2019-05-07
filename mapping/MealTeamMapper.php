<?php

/**
 * Mapper for {@link MealTeam} 
 * @see MealTeamValidator
 */
final class MealTeamMapper {

    private function __construct() {
    }

    /**
     * Maps array to the given {@link MealTeam}.
     * <p>
     * Expected properties are:
     * <ul>
     *   <li>id</li>
     *   <li>lead1_id</li>
     *   <li>lead2_id</li>
     * </ul>
     * @param MealTeam $team
     * @param array $properties
     */

    public static function map( MealTeam $team, array $properties ) {
        if (array_key_exists('id', $properties)) {
            $team->setId($properties['id']);
        }
        if (array_key_exists('lead1_id', $properties)) {
            $team->setLead1Id($properties['lead1_id']);
        }
        if (array_key_exists('lead2_id', $properties)) {
            $team->setLead2Id($properties['lead2_id']);
        }
    }

}
