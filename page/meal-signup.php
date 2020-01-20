<?php

function add_members( $meal, $person_ids ) {
    $dao = new MemberAttendeeDao();
    $attendees = array();
    foreach ( $person_ids as $person_id ) {
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
        $guest->setAgeGroup( AbstractMealAttendee::AGE_ADULT );
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

function count_specials( $attendee, &$count_veg, &$count_gf, &$count_df ) {
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
// array of unit_id and sub_group
$user_person_id = $_SESSION[ 'oc_user' ];
$unit_id_plus = Utils::getUnitIdByPersonId( $user_person_id );
$unit_members = array();
$count_adults = 0;
$count_child_young = 0;
$count_child_older = 0;
$count_veg = 0;
$count_gf = 0;
$count_df = 0;

if ( $role == Utils::USER ) {
    // Array of UnitPersons in the unit of person identified by 'unit_id_plus'
    $unit_members = Utils::getUnitMembersByUnitId( $unit_id_plus );
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
    $total_count = $count_adults + $count_child_young + $count_child_older;
    if ( array_key_exists( 'add_attendees', $_POST )) {
        $person_ids = $_POST[ 'add_attendees' ];
        if ( count( $person_ids ) + $total_count > $meal->getSignUpLimit() ) {
            Flash::addFlash( 'Meal sign up limit exceeded' );
        } elseif  ( array_key_exists( 'add_attendees', $_POST )) {
            $success = add_members( $meal, $person_ids );
            if ( $success ) {
                Flash::addFlash('Attendees saved successfully.');
            } else {
                Flash::addFlash( 'Unable to add attendees' );
            }
        }
        Utils::redirect( 'meal-signup', array('meal_id' => $meal->getId()));
    } else {
        Flash::addFlash( 'No attendees were selected.' );
    }
} elseif (array_key_exists('add_guest', $_POST)) {
    // save
    $total_count = $count_adults + $count_child_young + $count_child_older;
    $total_to_add = $_POST['guest_adults'] + $_POST['guest_child_young']
                        + $_POST['guest_child_older'];
    if ( $total_to_add == 0 ) {
         Flash::addFlash('No guests were selected.');
    } elseif ( $total_count + $total_to_add > $meal->getSignUpLimit() ) {
        Flash::addFlash( 'Meal sign up limit exceeded' );
    } else {
        $guest_unit = $unit_id_plus['unit_id'] . $unit_id_plus['sub_unit'];
        if (array_key_exists('guest_unit', $_POST)) {
            $guest_unit = $_POST['guest_unit'];
        }
        $success = add_guests($meal, $guest_unit, $_POST['guest_adults'], $_POST['guest_child_young'], $_POST['guest_child_older']);
        if ( $success ) {
            Flash::addFlash('Guests saved successfully.');
        } else {
            Flash::addFlash('Guests could not be added.');
        }
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
    $meal->downloadAttendeesReport( $member_attendees, $guests );
} elseif ( array_key_exists( 'email_attendees', $_POST )) {
    $result = $meal->emailAttendeesReport( $user_person_id, $member_attendees, $guests );
    Flash::addFlash( $result );
}
