<?php

/**
 * DAO for {@link Guest}.
 * <p>
 * 
 */
final class GuestDao extends AbstractDao {

    private function handleWhere( $sql, $where_started ) {
        if ( $where_started ) {
            $sql .= ' AND ';
        } else {
            $sql .= ' WHERE ';
        } 
        return $sql;
    }

    /**
     * Find all {@link Unit}s by search criteria.
     * @return array array of {@link Unit}s
     */
    public function find( AbstractSearchCriteria $search = null ) {
        $result = array();
        foreach ( $this->query( $this->getFindSql( $search )) as $row ) {
            $attendee = new Guest();
            GuestMapper::map( $attendee, $row );
            $result[ $attendee->getId() ] = $attendee;
        }
        return $result;
    }

    /**
     * Find {@link Guest} by identifier.
     * @return Guest or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query(
                'SELECT id, meal_id, guest_unit_id, guest_age, specials, extra_cost'
                    . ' FROM meal_attendees u WHERE id = '
                    . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $attendee = new Guest();
        GuestMapper::map( $attendee, $row );
        return $attendee;
    }

    /**
     * Save {@link Guest}.
     * @param Guest $attendee {@link Guest} to be saved
     * @return Guest saved {@link Guest} instance
     */
    public function save( AbstractModel $attendee ) {
        if ( $attendee->getId() === null ) {
            return $this->insert( $attendee );
        }
        return $this->update( $attendee );
    }

    /**
     * Delete {@link Guest} by identifier.
     * @param int $id {@link Guest} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete( $id ) {
        $sql = '
            DELETE FROM meal_attendees 
            WHERE
                id = :id';
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, array(
            ':id' => $id,
        ));
        return $statement->rowCount() == 1;
    }

    /**
     * @return Guest
     * @throws Exception
     */
    public function insert( AbstractModel $attendee ) {
        $attendee->setId( null );
        $sql = '
            INSERT INTO meal_attendees (id, meal_id, guest_unit_id, guest_age, specials, extra_cost)
                VALUES (:id, :meal_id, :guest_unit_id, :guest_age, :specials, :extra_cost)';
        return $this->execute( $sql, $attendee );
    }

    /**
     * @return Guest
     * @throws Exception
     */
    public function addGuests( array $guests, $meal_id ) {
        if ( count( $guests ) === 0 ) {
            return;
        }
        $sql = ' INSERT INTO meal_attendees ( meal_id, guest_unit_id, guest_age, specials, extra_cost ) '
                . 'VALUES ';
        $num = count( $guests );
        $i = 1;
        foreach ( $guests as $guest ) {
            $sql .= '( ' . $meal_id . ', ' . $guest->getUnitId() . ', "' . $guest->getGuestAge()
                     . '", "' . json_encode ( $guest->getSpecials() )
                     . '", '  . $guest->getExtraCost() . ')';
            if ( $i < $num ) {
                $sql .= ', ';
                $i += 1;
            }
        } 
        return $this->execute( $sql );
    }

    public function removeGuests( array $guest_ids, $meal_id ) {
        $num = count( $guest_ids );
        if ( $num === 0 ) {
            return;
        }
        $sql = 'DELETE FROM meal_attendees WHERE  meal_id = ' . $meal_id;
        $sql .= ' AND (';
        $i = 1;
        foreach ( $guest_ids as $guest_id ) {
            $sql .= ' id = ' . $guest_id; 
            if ( $i < $num ) {
                $sql .= ' OR ';
            }
            $i += 1;
        }
        $sql .= ')';
        return $this->execute( $sql );
    }

    /**
     * @return Guest
     * @throws Exception
     */
    public function update( AbstractModel $attendee ) {
        $sql = '
            UPDATE meal_attendees SET
                meal_id = :meal_id,
                guest_unit_id = :guest_unit_id,
                guest_age = :guest_age,
                specials = :specials,
                extra_cost = :extra_cost
            WHERE
                id = :id';
        return $this->execute( $sql, $attendee );
    }

    protected function getFindSql( AbstractSearchCriteria $search = null ) {
        $sql = 'SELECT id, meal_id, guest_unit_id, guest_age, specials, extra_cost '
                . ' FROM meal_attendees WHERE person_id is null ';
        if ( $search and $search->hasFilter() ) {
            $where_started = true;
            if ( $search->getMealId() ) {
                $sql = $this->handleWhere( $sql, $where_started );
                $sql .= ' meal_id = ' . $search->getMealId();
            }
            if ( $search->getUnitId() ) {
                $sql = $this->handleWhere( $sql, $where_started );
                $sql .= ' unit_id = ' . $search->getUnitId();
            }
        }
        return $sql;
    }

    protected function getParams( AbstractModel $attendee = null, 
                                  $update = false ) {
        if ( ! $attendee ) {
            return array();
        }
        $params = array(
            ':id' => $attendee->getId(),
            ':meal_id' => $attendee->getMealId(),
            ':guest_unit_id' => $attendee->getUnitId(),
            ':guest_age' => $attendee->getGuestAge(),
            ':specials' => json_encode( $attendee->getSpecials() ),
            ':extra_cost' => $attendee->getExtraCost() 
        );
        return $params;
    }

}
