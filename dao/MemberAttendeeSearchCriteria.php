<?php


/**
 * Search criteria for {@link MemberAttendeeDao}.
 * <p>
 * Can be easily extended without changing the {@link MemberAttendeeDao} API.
 */
final class MemberAttendeeSearchCriteria extends AbstractSearchCriteria {
    private $id = 0;
    private $meal_id;
    private $person_id;

    public function hasFilter() {
        if ( $this->id || $this->meal_id || $this->person_id ) {
            return true;
        }
        return false;
    }

    public function setMealId( $id ) {
        $this->meal_id = $id;
    }
 
    public function getMealId() {
        return $this->meal_id;
    }

    public function setPersonId( $id ) {
        $this->person_id = $id;
    }
 
    public function getPersonId() {
        return $this->person_id;
    }
}
