<?php

/**
 * Model class representing one meal.
 */
final class Meal extends AbstractModel {

    /** @var number */
    private $meal_team_id;
    /** @var string */
    private $summary;
    /** @var string */
    private $details;
    /** @var DateTime */
    private $meal_datetime;
    /** @var string */
    private $tags;
    /** @var number */
    private $person_id;
    /** @var DateTime */
    private $deadline;
    /** @var number */
    private $meal_cost;
    /** @var number */
    private $meal_cost_1;
    /** @var number */
    private $meal_cost_2;
    /** @var number */
    private $sign_up_limit;

    private $team_lead;

    //~ Getters & setters

    /**
     * @return number
     */
    public function getMealTeamId() {
        return $this->meal_team_id;
    }

    public function setMealTeamId( $id ) {
        $this->meal_team_id = $id;
    }

    /**
     * @return number
     */
    public function getMealCost() {
        return $this->meal_cost;
    }

    public function setMealCost( $cost ) {
        $this->meal_cost = $cost;
    }

    /**
     * Meal cost for ages 0-5
     * @return number
     */
    public function getMealCost1() {
        return $this->meal_cost_1;
    }

    public function setMealCost1( $cost ) {
        $this->meal_cost_1 = $cost;
    }

    /**
     * Meal cost for ages 6-12
     * @return number
     */
    public function getMealCost2() {
        return $this->meal_cost_2;
    }

    public function setMealCost2( $cost ) {
        $this->meal_cost_2 = $cost;
    }

    /**
     * Limit on number of meal attendees
     * @return number
     */
    public function getSignUpLimit() {
        return $this->sign_up_limit;
    }

    public function setSignUpLimit( $limit ) {
        $this->sign_up_limit = $limit;
    }

    /**
     * @return summary
     */
    public function getSummary() {
        return $this->summary;
    }

    public function setSummary( $summary ) {
        $this->summary  = $summary ;
    }

    /**
     * @return string
     */
    public function getDetails() {
        return $this->details;
    }
    
    public function setDetails( $details ) {
        $this->details = $details;
    }

    /**
     * @return DateTime
     */
    public function getDateTime() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        return $this->meal_datetime->format( 'Y-m-d H:i');
    }

    public function getDate() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        return $this->meal_datetime->format( 'Y-m-d');
    }

    public function getTime() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        return $this->meal_datetime->format( 'H:i');
    }

    public function setDateTime( $meal_datetime ) {
        $this->meal_datetime = new DateTime( $meal_datetime );
    }

    public function setDate( $meal_date ) {
        $this->meal_datetime = new DateTime( $meal_date );
    }

    public function setTime( $meal_time ) {
        $this->meal_datetime = new DateTime(
            $this->meal_datetime->format( 'Y-m-d' ) . $meal_time
        );
    }

    public function setDeadlineDate( $deadline_date ) {
        $this->deadline = new DateTime( $deadline_date );
    }

    public function setDeadlineTime( $deadline_time ) {
        $this->deadline = new DateTime(
            $this->deadline->format( 'Y-m-d' ) . $deadline_time
        );
    }

    public function getDeadlineDate() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'Y-m-d');
    }

    public function getDeadlineTime() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'H:i');
    }

    /**
     * @return DateTime
     */
    public function getDeadline() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'Y-m-d H:i');
    }

    public function getDeadlineDatetime() {
        return $this->deadline;
    }

    public function setDeadline( $deadline ) {
        $this->deadline = new DateTime( $deadline );
    }

    /**
     * @return string
     */
    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    /**
     * @return number
     */
    public function getPersonId() {
        return $this->person_id;
    }

    public function setPersonId($person_id) {
        $this->person_id = $person_id;
    }

    public function getTeamLead() {
        return $this->team_lead;
    }
    public function setTeamLead( $name ) {
        $this->team_lead = $name;
    }
}
