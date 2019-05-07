<?php

/**
 * Model class representing one meal.
 */
final class MealTeam extends AbstractModel {

    /** @var int */
    private $lead1_id;
    /** @var int */
    private $lead2_id;
    /** @var string */
    private $lead1_name;
    /** @var string */
    private $lead2_name;

    //~ Getters & setters

    /**
     * @return number
     */
    public function getLead1Id() {
        return $this->lead1_id;
    }

    public function setLead1Id( $id ) {
        $this->lead1_id = $id;
    }

    /**
     * @return number
     */
    public function getLead2Id() {
        return $this->lead2_id;
    }

    public function setLead2Id( $id ) {
        $this->lead2_id = $id;
    }

    /**
     * @return string
     */
    public function getLead1Name() {
        return $this->lead1_name;
    }

    public function setLead1Name( $name ) {
        $this->lead1_name = $name;
    }

    /**
     * @return string
     */
    public function getLead2Name() {
        return $this->lead2_name;
    }

    public function setLead2Name( $name ) {
        $this->lead2_name = $name;
    }

    public function setLeadNames( $name_array ) {
        $num_names = count( $name_array );
        if ( $num_names > 0 ) {
            $pn = $name_array[0];
            $this->setLead1Name( $pn->getFirstName() . ' ' 
                . $pn->getLastName() );
        }
        if ( $num_names > 1 ) {
            $pn = $name_array[1];
            $this->setLead2Name( $pn->getFirstName() . ' ' 
                . $pn->getLastName() );
        }
    }
}
