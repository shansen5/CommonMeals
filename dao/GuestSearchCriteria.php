<?php


/**
 * Search criteria for {@link GuestDao}.
 * <p>
 * Can be easily extended without changing the {@link GuestDao} API.
 */
final class GuestSearchCriteria extends AbstractSearchCriteria {
    private $id = 0;
    private $meal_id;
    private $unit_id;

    public function hasFilter() {
        if ( $this->id || $this->meal_id || $this->unit_id ) {
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

    public function setUnitId( $id ) {
        $this->unit_id = $id;
    }
 
    public function getUnitId() {
        return $this->unit_id;
    }
}
