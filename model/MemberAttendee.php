<?php

/**
 * Model class representing one person attending a meal.
 */
final class MemberAttendee extends AbstractMealAttendee {

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
        $this->setAgeGroup();
    }

    public function setAgeGroup( $age_group = "unknown" ) {
        $interval = new DateInterval( 'P8Y' );
        $interval->invert = 1;
        $young_yrs_ago = new DateTime();
        $young_yrs_ago->add( $interval );
        $interval = new DateInterval( 'P12Y' );
        $interval->invert = 1;
        $child_older_yrs_ago = new DateTime();
        $child_older_yrs_ago->add( $interval );
        $birthdate = new DateTime( $this->getPerson()->getBirthdate() );
        if ( $birthdate < $child_older_yrs_ago ) {
            parent::setAgeGroup( AbstractMealAttendee::AGE_ADULT );
        } elseif ( $birthdate < $young_yrs_ago ) {
            parent::setAgeGroup( AbstractMealAttendee::AGE_CHILD_OLDER );
        } else {
            parent::setAgeGroup( AbstractMealAttendee::AGE_CHILD_YOUNG );
        }
    }
}
