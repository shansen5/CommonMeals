<?php

/**
 * DAO for {@link Meal}.
 * <p>
 * 
 */
final class MealDao extends AbstractDao {

    /**
     * Find all {@link Unit}s by search criteria.
     * @return array array of {@link Unit}s
     */
    public function find( AbstractSearchCriteria $search = null ) {
        $result = array();
        foreach ( $this->query( $this->getFindSql( $search )) as $row ) {
            $meal = new Meal();
            MealMapper::map( $meal, $row );
            $result[ $meal->getId() ] = $meal;
        }
        return $result;
    }

    /**
     * Find {@link Meal} by identifier.
     * @return Meal or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query(
                'SELECT id, meal_team_id, summary, details, meal_datetime, '
                    . 'tags, person_id, deadline, meal_cost, meal_cost_1, meal_cost_2, '
                    . 'sign_up_limit FROM meals u WHERE id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $meal = new Meal();
        MealMapper::map( $meal, $row );
        return $meal;
    }

    /**
     * Save {@link Meal}.
     * @param Meal $meal {@link Meal} to be saved
     * @return Meal saved {@link Meal} instance
     */
    public function save( AbstractModel $meal ) {
        if ( $meal->getId() === null ) {
            return $this->insert( $meal );
        }
        return $this->update( $meal );
    }

    /**
     * Delete {@link Meal} by identifier.
     * @param int $id {@link Meal} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete( $id ) {
        $sql = '
            DELETE FROM meals 
            WHERE
                id = :id';
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, array(
            ':id' => $id,
        ));
        return $statement->rowCount() == 1;
    }

    /**
     * @return Meal
     * @throws Exception
     */
    public function insert( AbstractModel $meal ) {
        $meal->setId( null );
        $sql = '
            INSERT INTO meals (id, meal_team_id, summary, details, 
                meal_datetime, tags, person_id, deadline, meal_cost, 
                meal_cost_1, meal_cost_2, sign_up_limit)
                VALUES (:id, :meal_team_id, :summary, :details, 
                :meal_datetime, :tags, :person_id, :deadline,
                :meal_cost, :meal_cost_1, :meal_cost_2, :sign_up_limit)';
        return $this->execute( $sql, $meal );
    }

    /**
     * @return Meal
     * @throws Exception
     */
    public function update( AbstractModel $meal ) {
        $sql = '
            UPDATE meals SET
                meal_team_id = :meal_team_id,
                summary = :summary,
                details = :details,
                meal_datetime = :meal_datetime,
                tags = :tags,
                person_id = :person_id,
                deadline = :deadline,
                meal_cost = :meal_cost,
                meal_cost_1 = :meal_cost_1,
                meal_cost_2 = :meal_cost_2,
                sign_up_limit = :sign_up_limit
            WHERE
                id = :id';
        return $this->execute( $sql, $meal );
    }

    protected function getFindSql( AbstractSearchCriteria $search = null ) {
        $from_date = new DateTime();
        $to_date = null;
        if ( $search && $search->hasFilter() ) {
            if ( $search->getFromDate() ) {
                $from_date = $search->getFromDate();
            }
            if ( $search->getToDate() ) {
                $to_date = $search->getToDate();
            }
        }
        $sql = 'SELECT id, meal_team_id, summary, details, meal_datetime, '
                . 'tags, person_id, deadline, meal_cost, meal_cost_1, '
                . 'meal_cost_2, sign_up_limit '
                . ' FROM meals '
                . 'WHERE meal_datetime >= "' . $from_date . '"';
        if ( $to_date ) {
            $sql .= ' AND meal_datetime <= "' . $to_date . '"';
        }
        $sql .= ' ORDER BY meal_datetime';
        return $sql;
    }

    protected function getParams( AbstractModel $meal, $update = false ) {
        $params = array(
            ':id' => $meal->getId(),
            ':meal_team_id' => $meal->getMealTeamId(),
            ':summary' => $meal->getSummary(),
            ':details' => $meal->getDetails(),
            ':meal_datetime' => $meal->getDateTime(),
            ':tags' => $meal->getTags(),
            ':person_id' => $meal->getPersonId(),
            ':deadline' => $meal->getDeadline(),
            ':meal_cost' => $meal->getMealCost(),
            ':meal_cost_1' => $meal->getMealCost1(),
            ':meal_cost_2' => $meal->getMealCost2(),
            ':sign_up_limit' => $meal->getSignUpLimit()
        );
        return $params;
    }

}
