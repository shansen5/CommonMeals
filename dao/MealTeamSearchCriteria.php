<?php


/**
 * Search criteria for {@link MealTeamDao}.
 * <p>
 * Can be easily extended without changing the {@link MealTeamDao} API.
 */
final class MealTeamSearchCriteria extends AbstractSearchCriteria {
    private $id = 0;

    public function hasFilter() {
        if ( $this->id ) {
            return true;
        }
        return false;
    }

    public function setId( $id ) {
        $this->id = $id;
    }
 
    public function getId() {
        return $this->id;
    }
}
