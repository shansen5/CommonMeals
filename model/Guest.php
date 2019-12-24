<?php

/**
 * Model class representing one person attending a meal.
 */
final class Guest extends AbstractMealAttendee {

    /** @var string */
    private $unit_id;

    //~ getters & setters

    /**
     * @return number
     */
    public function getUnitId() {
        return $this->unit_id;
    }

    public function setUnitId( $id ) {
        $this->unit_id = $id;
    }
}
