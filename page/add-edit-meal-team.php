<?php

$errors = array();
$meal = null;
$meal_datetime_string = '';
$edit = array_key_exists('id', $_GET);
if ($edit) {
    $meal = Utils::getMealByGetId();
    if ( $meal ) {
        $meal_team_id = $meal->getMealTeamId();
        $meal_date_string = $meal->getDateTime()->format('Y-m-d');
        $meal_time_string = $meal->getDateTime()->format('h:i a');
        $deadline_date_string = $meal->getDeadline()->format('Y-m-d');
        $deadline_time_string = $meal->getDeadline()->format('h:i a');
        $meal_team_id = $meal->getMealTeamId();
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
        'detail' => $_POST['meal']['detail'],
        'meal_date' => $_POST['meal']['meal_date'],
        'meal_time' => $_POST['meal']['meal_time'],
        'deadline_date' => $_POST['meal']['deadline_date'],
        'deadline_time' => $_POST['meal']['deadline_time'],
        'tags' => $_POST['meal']['tags'],
        'meal_team_id' => $_POST['meal']['meal_team_id']
        );
    // map
    MealMapper::map($meal, $meal_data);
    PersonNameMapper::map($person_name, $name_data);
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
            Utils::redirect('meal-detail', array('id' => $meal->getId()));
        } catch( Exception $e ) {
            $error = new RError( 'transaction', $e->getMessage() );
            $errors[] = $error;
            DBConnection::rollback();
            Flash::addFlash('Failed to save meal.');
            // redirect
            Utils::redirect('meal-list', array('id' => $meal->getId()));
            DBConnection::close();
        }
        
    }
}
