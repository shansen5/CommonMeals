<?php

$role = Utils::getUserRole();
if ( !isset( $_POST['from_date'])) {
    $now = new DateTime();
    $_POST['from_date'] =  $now->format('m-d-Y') ;
    $threeWeekInterval = new DateInterval( "P3W" );
    $endInterval = $now;
    $endInterval->add( $threeWeekInterval );
    $_POST['to_date'] = $endInterval->format('m-d-Y');
}

$dao = new MealDao();

// data for template
$title = 'Meals';
$search = new MealSearchCriteria();
$from = Utils::createDateTimeFromString( $_POST['from_date']);
$to = Utils::createDateTimeFromString( $_POST['to_date']);
$search->setFromDate( $from->format( 'Y-m-d' ));
$search->setToDate( $to->format( 'Y-m-d' ));
$meals = $dao->find( $search );
