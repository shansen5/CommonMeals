<?php


/**
 * Search criteria for {@link UnitDao}.
 * <p>
 * Can be easily extended without changing the {@link UnitDao} API.
 */
final class UnitSearchCriteria extends AbstractSearchCriteria {

    public function hasFilter() {
        return false;
    }

}
