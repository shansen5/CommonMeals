<?php

/**
 * Abstract model class 
 */
abstract class AbstractMealAttendee extends AbstractModel {
   
    const AGE_ADULT = 'adult';
    const AGE_CHILD_YOUNG = 'child-young';
    const AGE_CHILD_OLDER = 'child-older';

    const VEGETARIAN = "vegetarian";
    const GLUTEN_FREE = "gluten";
    const DAIRY_FREE = "dairy";

    /** @var int */
    private $meal_id;
    /** @var string */
    private $specials;
    /** @var number */
    private $extra_cost;
    /** @var bool */
    private $specials_veg;
    private $specials_gf;
    private $specials_df;
    private $specials_other;

    private $age_group;

    public static function getAgeGroups() {
        return array( self::AGE_ADULT, self::AGE_CHILD_YOUNG, self::AGE_CHILD_OLDER, );
    }

    /**
     * Create new {@link Item} with default properties set.
     */
    public function __construct() {
    }

    //~ Getters & setters

    /**
     * @return number
     */
    public function getMealId() {
        return $this->meal_id;
    }

    public function setMealId( $id ) {
        $this->meal_id = $id;
    }

    /**
     * @return number
     */
    public function getExtraCost() {
        return $this->extra_cost;
    }

    public function setExtraCost( $cost ) {
        $this->extra_cost = $cost;
    }

    /**
     * @return string
     */
    public function getSpecials() {
        return $this->specials;
    }

    public function setSpecials( $json_specials ) {
        if ( ! $json_specials ) {
            return;
        }
        $this->specials = $json_specials;
        $specials = json_decode( $json_specials );
        $this->specials_veg = false;
        $this->specials_df = false;
        $this->specials_gf = false;
        $this->specials_other = "";
        foreach( $specials as $special ) {
            switch( $special ) {
                case "veg":
                $this->specials_veg = true;
                break;
            case "gf":
                $this->specials_gf = true;
                break;
            case "df":
                $this->specials_df = true;
                break;
            default:
                $this->specials_other = $special;
            }
        }
    }

    public function getSpecialsVeg() {
        return $this->specials_veg;
    }
    public function getSpecialsGF() {
        return $this->specials_gf;
    }
    public function getSpecialsDF() {
        return $this->specials_df;
    }
    public function getSpecialsOther() {
        return $this->specials_other;
    }

    /**
     * @return string
     */
    public function getAgeGroup() {
        return $this->age_group;
    }

    public function setAgeGroup( $age ) {
        AttendeeValidator::validateAge( $age );
        $this->age_group = $age;
    }
}
