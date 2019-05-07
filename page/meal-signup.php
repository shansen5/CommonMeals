<?php

function get_specials() {
    $specials_array = array();
    if ( array_key_exists( 'edit_veg', $_POST )) {
        foreach( $_POST[ 'edit_veg' ] as $id ) {
            $specials_array[ $id ][] = "veg";
        }
    }
    if ( array_key_exists( 'edit_df', $_POST )) {
        foreach( $_POST[ 'edit_df' ] as $id ) {
            $specials_array[ $id ][] = "df";
        }
    }
    if ( array_key_exists( 'edit_gf', $_POST )) {
        foreach( $_POST[ 'edit_gf' ] as $id ) {
            $specials_array[ $id ][] = "gf";
        }
    }
    $array_keys = array_keys( $_POST );
    foreach( $array_keys as $key => $other ) {
        $found = strstr( $other, "edit_other-", false );
        if ( $found ) {
            $specials_array[ substr( $found, 11 )][] = $_POST[ $other ];
        }
    }
    return $specials_array;
}

function get_extra_cost() {
    $extra_cost_array = array();
    $array_keys = array_keys( $_POST );
    foreach( $array_keys as $key => $other ) {
        $found = strstr( $other, "extra_cost-", false );
        if ( $found ) {
            $extra_cost_array[ substr( $found, 11 )] = $_POST[ $other ];
        }
    }
    return $extra_cost_array;
}

$role = Utils::getUserRole();
$errors = array();
$meal = Utils::getMealByGetId();
$unit_id = Utils::getUnitIdByPersonId( $_SESSION[ 'oc_user' ]);
$unit_members = array();
$count_adults = 0;
$count_0_5 = 0;
$count_6_12 = 0;
$count_veg = 0;
$count_gf = 0;
$count_df = 0;

if ( $role == Utils::USER ) {
    // Array of UnitPersons in the unit of person identified by 'unit_id'
    $unit_members = Utils::getUnitMembersByUnitId( $unit_id );
} else {
    // Array of UnitPersons for all current members
    $unit_members = Utils::getUnitMembersByUnitId();
}
// Array of MealAttendees for the meal identified by 'meal_id'
$meal_attendees = Utils::getMealAttendeesByMealId( $meal->getId() );
$meal_attendee_ids = array();
if ( $meal_attendees ) {
    foreach ( $meal_attendees as $attendee ) {
        $meal_attendee_ids[] = $attendee->getPersonId();
    }
}
$interval = new DateInterval( 'P6Y' );
$interval->invert = 1;
$six_yrs_ago = new DateTime();
$six_yrs_ago->add( $interval );
$interval = new DateInterval( 'P13Y' );
$interval->invert = 1;
$thirteen_yrs_ago = new DateTime();
$thirteen_yrs_ago->add( $interval );
$meal_income = 0;
foreach( $meal_attendees as $attendee ) {
    $person = $attendee->getPerson();
    $birthdate = new DateTime( $person->getBirthdate() );
    if ( $birthdate < $thirteen_yrs_ago ) {
        $count_adults++;
        $meal_income += $meal->getMealCost();
    } elseif ( $birthdate < $six_yrs_ago ) {
        $count_6_12++;
        $meal_income += $meal->getMealCost2();
    } else {
        $count_0_5++;
        $meal_income += $meal->getMealCost1();
    }
    if ( $attendee->getSpecialsVeg() ) {
        $count_veg++;
    }
    if ( $attendee->getSpecialsGF() ) {
        $count_gf++;
    }
    if ( $attendee->getSpecialsdF() ) {
        $count_df++;
    }
    $meal_income += $attendee->getExtraCost();
}
$unit_member_ids = array();
foreach( $unit_members as $unit_person ) {
    $unit_member_ids[] = $unit_person->getPersonId();
}

if (array_key_exists('cancel', $_POST)) {
    // redirect
    if ( $meal->getId() ) {
        Utils::redirect( 'meal-detail', array('id' => $meal->getId()));
    } else {
        Utils::redirect('meal-list', array());
    }
} elseif (array_key_exists('add', $_POST)) {
    // save
    if ( array_key_exists( 'add_attendees', $_POST )) {
        $dao = new MealAttendeeDao();
        $attendees = array();
        foreach ( $_POST[ 'add_attendees' ] as $person_id ) {
            $attendee = new MealAttendee();
            $attendee->setPersonId( $person_id );
            $attendee->setExtraCost( 0 );
            $attendees[] = $attendee;
        }
        $success = $dao->addAttendees( $attendees,
                                       $meal->getId() );
        if ( $success ) {
            Flash::addFlash('Attendees saved successfully.');
        } else {
            Flash::addFlash( 'Unable to add attendees' );
        }
    }
    Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
} elseif (array_key_exists('edit', $_POST)) {
    // save
    $specials_array = get_specials();
    $extra_cost_array = get_extra_cost();
    if (( $specials_array && sizeof( $specials_array )) ||
        ( $extra_cost_array && sizeof( $extra_cost_array ))) {
        $dao = new MealAttendeeDao();
        foreach( $meal_attendees as $attendee ) {
            if ( in_array( $attendee->getPersonId(), $unit_member_ids )) {
                foreach( $specials_array as $key => $value ) {
                    if ( $attendee->getPersonId() === (string)$key ) {
                        $specials_json = json_encode( $value );
                        $attendee->setSpecials( $specials_json );
                        $dao->save( $attendee );
                    }
                }
                foreach( $extra_cost_array as $key => $value ) {
                    if ( $attendee->getPersonId() === (string)$key ) {
                        $attendee->setExtraCost( $value );
                        $dao->save( $attendee );
                    }
                }
            }
        }
        Flash::addFlash('Attendees saved successfully.');
    }
    Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
} elseif (array_key_exists('remove', $_POST)) {
    // save
    if ( array_key_exists( 'remove_ids', $_POST )) {
        $dao = new MealAttendeeDao();
        $dao->removeAttendees( $_POST[ 'remove_ids' ], 
                                        $meal->getId() );
        Flash::addFlash('Attendees removed successfully.');
    }
    Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
} elseif ( array_key_exists( 'download_report', $_POST )) {
    download_report( $meal, $meal_attendees );
}

function download_report( $meal, $attendees ) {
    $dir = getcwd();
    $filename = 'logs/meal_report' . date('Y-m-d-H-mi') . '.csv';
    $handle = fopen( $filename, 'w' );
    if ( $handle ) {
        fwrite( $handle, "Code, Name, Size, Unit, Delivery Date, Time, Location, Zone, Quantity, Account, Customer\n" );
    }
}

function make_header( $filename ) {
    if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/text');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        // exit;
    }
    unlink( $filename );
}

