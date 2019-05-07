<?php


/**
 * Search criteria for {@link MealDao}.
 * <p>
 * Can be easily extended without changing the {@link MealDao} API.
 */
final class MealSearchCriteria extends AbstractSearchCriteria {
    private $from_date = null;
    private $to_date = null;
    private $show_all = true;

    public function hasFilter() {
        if ( $this->from_date || $this->to_date ) {
            return true;
        }
        return false;
    }

    public function setShowAll( bool $sense ) {
        $this->show_all = $sense;
    }
    
    public function getShowAll() {
        return $this->show_all;
    }
    
    public function setFromDate( $s_date ) {
        $this->from_date = $s_date;
    }
    
    public function getFromDate() {
        return $this->from_date;
    }
    
    public function setToDate( $s_date ) {
        $this->to_date = $s_date;
    }
    
    public function getToDate() {
        return $this->to_date;
    }
    
}
