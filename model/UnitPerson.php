<?php

/**
 * Model class representing one unit_person.
 */
final class UnitPerson extends AbstractModel {

    private static $occupant_types = array( 'owner', 'owner-non-resident', 'renter', 'child' );

    /** @var int */
    private $unit_id;
    /** @var string */
    private $sub_unit;
    /** @var int */
    private $person_id;
    private $occupant_type;
    /** @var DateTime */
    private $start_date;
    /** @var DateTime */
    private $end_date;
    /** @var string */
    private $first_name;
    /** @var string */
    private $last_name;

    //~ Getters & setters

    /**
     * @return int
     */
    public function getUnitId() {
        return $this->unit_id;
    }

    public function setUnitId($id) {
        $this->unit_id = (int) $id;
    }

    /**
     * @return string
     */
    public function getSubUnit() {
        return $this->sub_unit;
    }

    public function setSubUnit($sub_unit) {
        $this->sub_unit = $sub_unit;
    }

    /**
     * @return int
     */
    public function getPersonId() {
        return $this->person_id;
    }

    public function setPersonId($id) {
        $this->person_id = (int) $id;
    }

    /**
     * @return string
     */
    public function getOccupantType() {
        return $this->occupant_type;
    }
    
    public function setOccupantType($type) {
        $this->occupant_type = $type;
    }

    /**
     * @return DateTime
     */
    public function getStartDate() {
        return $this->start_date;
    }

    public function setStartDate(DateTime $start_date) {
        $this->start_date = $start_date;
    }

    /**
     * @return DateTime
     */
    public function getEndDate() {
        return $this->end_date;
    }

    public function setEndDate(DateTime $end_date) {
        $this->end_date = $end_date;
    }

    /**
     * @return string
     */
    public function getFirstName() {
        return $this->first_name;
    }
    
    public function setFirstName($first) {
        $this->first_name = $first;
    }

    /**
     * @return string
     */
    public function getLastName() {
        return $this->last_name;
    }
    
    public function setLastName($last) {
        $this->last_name = $last;
    }

    public static function getOccupantTypes() {
        return self::$occupant_types;
    }
}
