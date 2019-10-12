<?php

function add_members( $meal ) {
    $dao = new MemberAttendeeDao();
    $attendees = array();
    foreach ( $_POST[ 'add_attendees' ] as $person_id ) {
        $attendee = new MemberAttendee();
        $attendee->setPersonId( $person_id );
        $attendee->setExtraCost( 0 );
        $attendees[] = $attendee;
    }
    return ( $dao->addAttendees( $attendees,
                                   $meal->getId() ));
}

function add_guests( $meal, $unit, $adults, $child_young, $child_older ) {
    $dao = new GuestDao();
    $guests = array();
    for ( $i = 0; $i < $adults; $i++ ) {
        $guest = new Guest();
        $guest->setAgeGroup( AbstractMealAttendee::GUEST_ADULT );
        $guest->setUnitId( $unit );
        $guest->setExtraCost( 0 );
        $guests[] = $guest;
    }
    for ( $i = 0; $i < $child_young; $i++ ) {
        $guest = new Guest();
        $guest->setAgeGroup( AbstractMealAttendee::AGE_CHILD_YOUNG );
        $guest->setUnitId( $unit );
        $guest->setExtraCost( 0 );
        $guests[] = $guest;
    }
    for ( $i = 0; $i < $child_older; $i++ ) {
        $guest = new Guest();
        $guest->setAgeGroup( AbstractMealAttendee::AGE_CHILD_OLDER );
        $guest->setUnitId( $unit );
        $guest->setExtraCost( 0 );
        $guests[] = $guest;
    }
    return ( $dao->addGuests( $guests, $meal->getId() ));

}

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

function get_guest_specials() {
    $specials_array = array();
    if ( array_key_exists( 'guest_edit_veg', $_POST )) {
        foreach( $_POST[ 'guest_edit_veg' ] as $id ) {
            $specials_array[ $id ][] = "veg";
        }
    }
    if ( array_key_exists( 'guest_edit_df', $_POST )) {
        foreach( $_POST[ 'guest_edit_df' ] as $id ) {
            $specials_array[ $id ][] = "df";
        }
    }
    if ( array_key_exists( 'guest_edit_gf', $_POST )) {
        foreach( $_POST[ 'guest_edit_gf' ] as $id ) {
            $specials_array[ $id ][] = "gf";
        }
    }
    $array_keys = array_keys( $_POST );
    foreach( $array_keys as $key => $other ) {
        $found = strstr( $other, "guest_edit_other-", false );
        if ( $found ) {
            $specials_array[ substr( $found, 17 )][] = $_POST[ $other ];
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

function get_guest_extra_cost() {
    $extra_cost_array = array();
    $array_keys = array_keys( $_POST );
    foreach( $array_keys as $key => $other ) {
        $found = strstr( $other, "guest_extra_cost-", false );
        if ( $found ) {
            $extra_cost_array[ substr( $found, 17 )] = $_POST[ $other ];
        }
    }
    return $extra_cost_array;
}

function count_attendees( $member_attendees, &$count_adults, &$count_child_young, 
                          &$count_child_older, &$meal_income, &$count_df, &$count_gf,
                          &$count_veg, $meal ) {
    foreach( $member_attendees as $attendee ) {
        $age = $attendee->getAgeGroup();
        if ( $age === AbstractMealAttendee::AGE_ADULT ) {
            $count_adults++;
            $meal_income += $meal->getMealCost();
        } elseif ( $age === AbstractMealAttendee::AGE_CHILD_OLDER ) {
            $count_child_older++;
            $meal_income += $meal->getMealCost2();
        } else {
            $count_child_young++;
            $meal_income += $meal->getMealCost1();
        }
        $meal_income += $attendee->getExtraCost();
        count_specials( $attendee, $count_veg, $count_gf, $count_df );
    }
}

function count_guests( $guests, &$count_adults, &$count_child_young, &$count_child_older, 
                       &$meal_income , &$count_df, &$count_gf, &$count_veg,
                       $meal ) {
    foreach ( $guests as $guest ) {
        $age = $guest->getAgeGroup();
        if ( $age === AbstractMealAttendee::AGE_ADULT ) {
            $count_adults++;
            $meal_income += $meal->getMealCost();
        } elseif ( $age === AbstractMealAttendee::AGE_CHILD_OLDER ) {
            $count_child_older++;
            $meal_income += $meal->getMealCost2();
        } else {
            $count_child_young++;
            $meal_income += $meal->getMealCost1();
        }
        $meal_income += $guest->getExtraCost();
        count_specials( $guest, $count_veg, $count_gf, $count_df );
    }
}

function count_specials( $attendee, &$count_veg, &$count_gf, &$countdf ) {
    if ( $attendee->getSpecialsVeg() ) {
        $count_veg++;
    }
    if ( $attendee->getSpecialsGF() ) {
        $count_gf++;
    }
    if ( $attendee->getSpecialsdF() ) {
        $count_df++;
    }
}

$role = Utils::getUserRole();
$errors = array();
$meal = Utils::getMealByGetId();
$unit_id = Utils::getUnitIdByPersonId( $_SESSION[ 'oc_user' ]);
$unit_members = array();
$count_adults = 0;
$count_child_young = 0;
$count_child_older = 0;
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
// Array of MemberAttendees for the meal identified by 'meal_id'
$member_attendees = Utils::getMemberAttendeesByMealId( $meal->getId() );
$member_attendee_ids = array();
if ( $member_attendees ) {
    foreach ( $member_attendees as $attendee ) {
        $member_attendee_ids[] = $attendee->getPersonId();
    }
}
$guests = Utils::getGuestsByMealId( $meal->getId() );
$meal_income = 0;
count_attendees( $member_attendees, $count_adults, $count_child_young, 
                 $count_child_older, $meal_income, $count_df, $count_gf,
                 $count_veg, $meal );
count_guests( $guests, $count_adults, $count_child_young, $count_child_older, 
              $meal_income , $count_df, $count_gf, $count_veg, $meal);

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
} elseif (array_key_exists('add_member', $_POST)) {
    // save
    if ( array_key_exists( 'add_attendees', $_POST )) {
        $success = add_members( $meal );

        if ( $success ) {
            Flash::addFlash('Attendees saved successfully.');
        } else {
            Flash::addFlash( 'Unable to add attendees' );
        }
    }
    Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
} elseif (array_key_exists('add_guest', $_POST)) {
    // save
    $guest_unit = $unit_id;
    if (array_key_exists('guest_unit', $_POST)) {
        $guest_unit = $_POST['guest_unit'];
    }
    $success = add_guests($meal, $guest_unit, $_POST['guest_adults'], $_POST['guest_child_young'], $_POST['guest_child_older']);
    if ( $success ) {
        Flash::addFlash('Guests saved successfully.');
    } else {
        Flash::addFlash('Guests could not be added.');
    }
    Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
} elseif (array_key_exists('edit', $_POST)) {
    // save
    $specials_array = get_specials();
    $extra_cost_array = get_extra_cost();
    if (( $specials_array && sizeof( $specials_array )) ||
        ( $extra_cost_array && sizeof( $extra_cost_array ))) {
        $dao = new MemberAttendeeDao();
        foreach( $member_attendees as $attendee ) {
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
    }
    $guest_specials_array = get_guest_specials();
    $guest_extra_cost_array = get_guest_extra_cost();
    if (( $guest_specials_array && sizeof( $guest_specials_array )) ||
        ( $guest_extra_cost_array && sizeof( $guest_extra_cost_array ))) {
        $dao = new GuestDao();
        foreach( $guests as $guest ) {
            foreach( $guest_specials_array as $key => $value ) {
                if ( $guest->getId() === $key ) {
                    $specials_json = json_encode( $value );
                    $guest->setSpecials( $specials_json );
                    $dao->save( $guest );
                }
            }
            foreach( $guest_extra_cost_array as $key => $value ) {
                if ( $guest->getId() === $key ) {
                    $guest->setExtraCost( $value );
                    $dao->save( $guest );
                }
            }
        }
        Flash::addFlash('Attendees saved successfully.');
    }
    Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
} elseif (array_key_exists('remove', $_POST)) {
    // remove
    $remove_success = false;
    if ( array_key_exists( 'remove_ids', $_POST )) {
        $dao = new MemberAttendeeDao();
        $dao->removeAttendees( $_POST[ 'remove_ids' ], 
                                        $meal->getId() );
        $remove_success = true;
    }
    if ( array_key_exists( 'remove_guest_ids', $_POST )) {
        $dao = new GuestDao();
        $dao->removeGuests( $_POST[ 'remove_guest_ids' ], 
                                        $meal->getId() );
        $remove_success = true;
    }
    if ( $remove_success ) {
        Flash::addFlash('Attendees removed successfully.');
    } else {
        Flash::addFlash('Unable to remove attendees.');
    }
    Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
} elseif ( array_key_exists( 'download_attendees', $_POST )) {
    download_attendees( $role, $meal, $member_attendees );
} elseif ( array_key_exists( 'download_units', $_POST )) {
    download_units( $role, $meal, $member_attendees );
}

function download_attendees( $role, $meal, $attendees ) {
    $dir = getcwd();
    $filename = 'logs/meal_attendees_report-' . date('Y-m-d-H-mi') . '.csv';
    $handle = fopen( $filename, 'w' );
    if ( $handle ) {
        fwrite( $handle, "Meal:, " . $meal->getSummary() . "\n" );
        fwrite( $handle, "Team:, " . Utils::getMealTeamLeads( $meal->getMealTeamId() ) . "\n");
        fwrite( $handle, "Date and Time:, " . $meal->getDateTime() . "\n");
        fwrite( $handle, "Signup by:, " . $meal->getDeadline() . "\n");
        fwrite( $handle, "Cost:, " . $meal->getMealCost() . "\n");
        fwrite( $handle, "Young child:, " . $meal->getMealCost1() . "\n");
        fwrite( $handle, "Older child:, " . $meal->getMealCost2() . "\n");
        fwrite( $handle, "\n" );
        fwrite( $handle, "Name, Age, Veg, GF, DF, Allergies" );
        if ( $role === Utils::MEALS_ADMIN ) {
            fwrite( $handle, ", Extra Cost\n" );
        } else {
            fwrite( $handle, "\n" );
        }
        foreach( $attendees as $attendee ) {
            $person = $attendee->getPerson();
            fwrite( $handle, $person->getFirstName() . " " . $person->getLastName() . "," );
            fwrite( $handle, "age" );
            if ( $role === Utils::MEALS_ADMIN ) {
                fwrite( $handle, $attendee->getExtraCost() );
            } else {
                fwrite( $handle, "\n" );
            }
        }
    }
    fclose( $handle );
    make_header( $filename );
}

function download_units( $role, $meal, $attendees ) {
    $dir = getcwd();
    $filename = 'logs/meal_units_report-' . date('Y-m-d-H-mi') . '.csv';
    $handle = fopen( $filename, 'w' );
    if ( $handle ) {
        fwrite( $handle, "Unit,, Count,,," );
        if ( $role === Utils::MEALS_ADMIN ) {
            fwrite( $handle, ", Cost\n" );
        } else {
            fwrite( $handle, "\n" );
        }
        fwrite( $handle, ", Adult, Young Child, Older Child\n");
    }
    fclose( $handle );
    make_header( $filename );
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

