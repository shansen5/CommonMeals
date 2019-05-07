<?php

$errors = array();
$meal = null;
$meal_datetime_string = '';
$meal_team_id = 0;
$meal_date_string = '';
$meal_time_string = '';
$deadline_date_string = '';
$deadline_time_string = '';
$edit = array_key_exists('meal_id', $_GET);
if ($edit) {
    $meal = Utils::getMealByGetId();
    if ( $meal ) {
        $meal_team_id = $meal->getMealTeamId();
        $meal_date_string = $meal->getDate();
        $meal_time_string = $meal->getTime();
        $deadline_date_string = $meal->getDeadlineDate();
        $deadline_time_string = $meal->getDeadlineTime();
    } 
} else {
    // set defaults
    $meal = new Meal();
}

if (array_key_exists('cancel', $_POST)) {
    // redirect
    if ( $meal->getId() ) {
        Utils::redirect('meal-detail', array('id' => $meal->getId()));
    } else {
        Utils::redirect('meal-list', array());
    }
} elseif (array_key_exists('save', $_POST)) {
    $meal_data = array(
        'summary' => $_POST['meal']['summary'],
        'details' => $_POST['meal']['details'],
        'meal_date' => $_POST['meal']['meal_date'],
        'meal_cost' => $_POST['meal']['meal_cost'],
        'meal_cost_1' => $_POST['meal']['meal_cost_1'],
        'meal_cost_2' => $_POST['meal']['meal_cost_2'],
        'meal_time' => $_POST['meal']['meal_time'],
        'deadline_date' => $_POST['meal']['deadline_date'],
        'deadline_time' => $_POST['meal']['deadline_time'],
        'tags' => $_POST['meal']['tags'],
        'meal_team_id' => $_POST['meal']['meal_team_id'],
        'person_id' => $_SESSION['oc_user']
        );
    // map
    MealMapper::map($meal, $meal_data);
    // validate
    $errors = MealValidator::validate($meal);
    if (empty($errors)) {
        // save
        DBConnection::beginTransaction();
        try {
            $dao = new MealDao( DBConnection::getDb() );
            $meal = $dao->save($meal);
            DBConnection::commit();
            DBConnection::close();
            Flash::addFlash('Meal saved successfully.');
            // redirect
            Utils::redirect('meal-detail', array('meal_id' => $meal->getId()));
        } catch( Exception $e ) {
            $error = new RError( 'transaction', $e->getMessage() );
            $errors[] = $error;
            DBConnection::rollback();
            Flash::addFlash('Failed to save meal.');
            // redirect
            Utils::redirect('meal-list', array('meal_id' => $meal->getId()));
            DBConnection::close();
        }
        
    }
}
