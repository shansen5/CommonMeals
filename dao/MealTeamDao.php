<?php

/**
 * DAO for {@link MealTeam}.
 * <p>
 * 
 */
final class MealTeamDao extends AbstractDao {

    private function getTeamLeadNames( $team, $search = null ) {
        $pn_dao = new PersonNameDao();
        $pn_search = new PersonNameSearchCriteria();
        $person_names = [];
        $pn_search->setPersonId( $team->getLead1Id() );
        $pn_array = $pn_dao->find( $pn_search );   
        if ( $pn_array[0] ) {
            $person_names[] = $pn_array[0];
        }
        $lead2_id = $team->getLead2Id();
        if ( $lead2_id ) {
            $pn_search->setPersonId( $lead2_id );
            $pn_array = $pn_dao->find( $pn_search );   
            if ( $pn_array[0] ) {
                $person_names[] = $pn_array[0];
            }
        }
        return $person_names;
    }
    
    /**
     * Find all {@link MealTeam}s by search criteria.
     * @return array array of {@link MealTeam}s
     */
    public function find( AbstractSearchCriteria $search = null ) {
        $result = array();
        foreach ( $this->query( $this->getFindSql( $search )) as $row ) {
            $team = new MealTeam();
            MealTeamMapper::map( $team, $row );
            $team->setLeadNames( $this->getTeamLeadNames( $team ));
            $result[ $team->getId() ] = $team;
        }
        return $result;
    }

    /**
     * Find {@link Meal} by identifier.
     * @return Meal or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query(
                'SELECT id, lead1_id, lead2_id '
                    . ' FROM meal_teams u WHERE id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $team = new MealTeam();
        MealTeamMapper::map( $team, $row );
        $team->setLeadNames( $this->getTeamLeadNames( $team ));
        return $team;
    }

    /**
     * Save {@link Team}.
     * @param Team $team {@link MealTeam} to be saved
     * @return MealTeam saved {@link MealTeam} instance
     */
    public function save( AbstractModel $team ) {
        if ( $team->getId() === null ) {
            return $this->insert( $team );
        }
        return $this->update( $team );
    }

    /**
     * Delete {@link Team} by identifier.
     * @param int $id {@link Team} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete( $id ) {
        $sql = '
            DELETE FROM teams 
            WHERE
                id = :id';
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, array(
            ':id' => $id,
        ));
        return $statement->rowCount() == 1;
    }

    /**
     * @return MealTeam
     * @throws Exception
     */
    public function insert( AbstractModel $team ) {
        $team->setId( null );
        $sql = '
            INSERT INTO meal_teams (id, lead1_id, lead2_id )
                VALUES (:id, :lead1_id, :lead2_id)';
        return $this->execute( $sql, $team );
    }

    /**
     * @return MealTeam
     * @throws Exception
     */
    public function update( AbstractModel $team ) {
        $sql = '
            UPDATE meal_teams SET
                lead1_id = :lead1_id,
                lead2_id = :lead2_id
            WHERE
                id = :id';
        return $this->execute( $sql, $team );
    }

    protected function getFindSql( AbstractSearchCriteria $search = null ) {
        $sql = 'SELECT id, lead1_id, lead2_id FROM meal_teams ';
        return $sql;
    }

    protected function getParams( AbstractModel $team, $update = false ) {
        $params = array(
            ':id' => $team->getId(),
            ':lead1_id' => $team->getLead1Id(),
            ':lead2_id' => $team->getLead2Id()
        );
        return $params;
    }

}
