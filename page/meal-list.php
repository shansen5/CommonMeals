<?php

$role = Utils::getUserRole();
if ( !isset( $_POST['from_date'])) {
    $now = new DateTime();
    $_POST['from_date'] =  $now->format('Y-m-d') ;
    $twoWeekInterval = new DateInterval( "P2W" );
    $endInterval = $now;
    $endInterval->add( $twoWeekInterval );
    $_POST['to_date'] = $endInterval->format('Y-m-d');
}

$dao = new MealDao();

// data for template
$title = 'Meals';
$search = new MealSearchCriteria();
$search->setFromDate( $_POST[ 'from_date' ]);
$search->setToDate( $_POST[ 'to_date' ]);
$meals = $dao->find( $search );
