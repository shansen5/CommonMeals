<?php

/**
 * Model class representing one person attending a meal.
 */
final class MealAttendee extends AbstractMealAttendee {

    /** @var int */
    private $person_id;
    /** @var Person */
    private $person;

    //~ Getters & setters

    /**
     * @return number
     */
    public function getPersonId() {
        return $this->person_id;
    }

    public function setPersonId( $id ) {
        $this->person_id = $id;
    }

    /**
     * @return Person
     */
    public function getPerson() {
        return $this->person;
    }

    public function setPerson( $person ) {
        $this->person = $person;
    }
}
