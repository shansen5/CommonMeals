<?php

/**
 * DAO for {@link MemberAttendee}.
 * <p>
 * 
 */
final class MemberAttendeeDao extends AbstractDao {

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
            $attendee = new MemberAttendee();
            MemberAttendeeMapper::map( $attendee, $row );
            $result[ $attendee->getId() ] = $attendee;
            $dao = new PersonDao();
            $person = $dao->findById( $attendee->getPersonId() );
            if ($person === null) {
                throw new NotFoundException('Unknown Person identifier provided.');
            }
            $attendee->setPerson( $person );
        }
        return $result;
    }

    /**
     * Find {@link MemberAttendee} by identifier.
     * @return MemberAttendee or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query(
                'SELECT id, meal_id, person_id, specials, extra_cost'
                    . ' FROM meal_attendees u WHERE id = '
                    . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $attendee = new MemberAttendee();
        MemberAttendeeMapper::map( $attendee, $row );
        $dao = new PersonDao();
        $person = $dao->findById( $attendee->getPersonId() );
        if ($person === null) {
            throw new NotFoundException('Unknown Person identifier provided.');
        }
        $attendee->setPerson( $person );
        return $attendee;
    }

    /**
     * Save {@link MemberAttendee}.
     * @param MemberAttendee $attendee {@link MemberAttendee} to be saved
     * @return MemberAttendee saved {@link MemberAttendee} instance
     */
    public function save( AbstractModel $attendee ) {
        if ( $attendee->getId() === null ) {
            return $this->insert( $attendee );
        }
        return $this->update( $attendee );
    }

    /**
     * Delete {@link MemberAttendee} by identifier.
     * @param int $id {@link MemberAttendee} identifier
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
     * @return MemberAttendee
     * @throws Exception
     */
    public function insert( AbstractModel $attendee ) {
        $attendee->setId( null );
        $sql = '
            INSERT INTO meal_attendees (id, meal_id, person_id, specials, extra_cost)
                VALUES (:id, :meal_id, :person_id, :specials, :extra_cost)';
        return $this->execute( $sql, $attendee );
    }

    /**
     * @return MemberAttendee
     * @throws Exception
     */
    public function addAttendees( array $attendees, $meal_id ) {
        if ( count( $attendees ) === 0 ) {
            return;
        }
        $sql = ' INSERT INTO meal_attendees ( meal_id, person_id, specials, extra_cost ) '
                . 'VALUES ';
        $num = count( $attendees );
        $i = 1;
        foreach ( $attendees as $attendee ) {
            $sql .= '( ' . $meal_id . ', ' . $attendee->getPersonId() 
                     . ', ' . json_encode ( $attendee->getSpecials() )
                     . ', '  . $attendee->getExtraCost() . ')';
            if ( $i < $num ) {
                $sql .= ', ';
                $i += 1;
            }
        } 
        return $this->execute( $sql );
    }

    public function removeAttendees( array $attendee_ids, $meal_id ) {
        $sql = 'DELETE FROM meal_attendees WHERE  meal_id = ' . $meal_id;
        $sql .= ' AND (';
        $num = count( $attendee_ids );
        if ( $num === 0 ) {
            return;
        }
        $i = 1;
        foreach ( $attendee_ids as $attendee_id ) {
            $sql .= ' person_id = ' . $attendee_id; 
            if ( $i < $num ) {
                $sql .= ' OR ';
            }
            $i += 1;
        }
        $sql .= ')';
        return $this->execute( $sql );
    }

    /**
     * @return MemberAttendee
     * @throws Exception
     */
    public function update( AbstractModel $attendee ) {
        $sql = '
            UPDATE meal_attendees SET
                meal_id = :meal_id,
                person_id = :person_id,
                specials = :specials,
                extra_cost = :extra_cost
            WHERE
                id = :id';
        return $this->execute( $sql, $attendee );
    }

    protected function getFindSql( AbstractSearchCriteria $search = null ) {
        $sql = 'SELECT id, meal_id, person_id, specials, extra_cost '
                . ' FROM meal_attendees WHERE person_id is not null ';
        if ( $search and $search->hasFilter() ) {
            $where_started = true;
            if ( $search->getMealId() ) {
                $sql = $this->handleWhere( $sql, $where_started );
                $sql .= ' meal_id = ' . $search->getMealId();
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
            ':person_id' => $attendee->getPersonId(),
            ':specials' => json_encode( $attendee->getSpecials() ),
            ':extra_cost' => $attendee->getExtraCost() 
        );
        return $params;
    }

}
