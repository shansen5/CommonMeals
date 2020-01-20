<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../util/PHPMailer/PHPMailer.php';
require '../util/PHPMailer/Exception.php';

/**
 * Model class representing one meal.
 */
final class Meal extends AbstractModel {

    /** @var number */
    private $meal_team_id;
    /** @var string */
    private $summary;
    /** @var string */
    private $details;
    /** @var DateTime */
    private $meal_datetime;
    /** @var string */
    private $tags;
    /** @var number */
    private $person_id;
    /** @var DateTime */
    private $deadline;
    /** @var number */
    private $meal_cost;
    /** @var number */
    private $meal_cost_1;
    /** @var number */
    private $meal_cost_2;
    /** @var number */
    private $sign_up_limit;

    private $team_lead;

    function __construct() {
        $this->meal_cost = 0.00;
        $this->meal_cost_1 = 0.00;
        $this->meal_cost_2 = 0.00;
        $this->sign_up_limit = 40;
    }

    //~ Getters & setters

    /**
     * @return number
     */
    public function getMealTeamId() {
        return $this->meal_team_id;
    }

    public function setMealTeamId( $id ) {
        $this->meal_team_id = $id;
    }

    /**
     * @return number
     */
    public function getMealCost() {
        return $this->meal_cost;
    }

    public function setMealCost( $cost ) {
        $this->meal_cost = $cost;
    }

    /**
     * Meal cost for ages 0-5
     * @return number
     */
    public function getMealCost1() {
        return $this->meal_cost_1;
    }

    public function setMealCost1( $cost ) {
        $this->meal_cost_1 = $cost;
    }

    /**
     * Meal cost for ages 6-12
     * @return number
     */
    public function getMealCost2() {
        return $this->meal_cost_2;
    }

    public function setMealCost2( $cost ) {
        $this->meal_cost_2 = $cost;
    }

    /**
     * Limit on number of meal attendees
     * @return number
     */
    public function getSignUpLimit() {
        return $this->sign_up_limit;
    }

    public function setSignUpLimit( $limit ) {
        $this->sign_up_limit = $limit;
    }

    /**
     * @return summary
     */
    public function getSummary() {
        return $this->summary;
    }

    public function setSummary( $summary ) {
        $this->summary  = $summary ;
    }

    /**
     * @return string
     */
    public function getDetails() {
        return $this->details;
    }
    
    public function setDetails( $details ) {
        $this->details = $details;
    }

    /**
     * @return DateTime as string formatted m-d-Y
     */
    public function getDateTimeMDY() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        // return $this->meal_datetime->format( 'Y-m-d H:i');
        return $this->meal_datetime->format( 'm-d-Y g:i A');
    }

    /**
     * @return DateTime as string formatted Y-m-d
     */
    public function getDateTimeYMD() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        return $this->meal_datetime->format( 'Y-m-d H:i');
    }

    public function getDateYMD() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        return $this->meal_datetime->format( 'Y-m-d');
    }

    public function getDateMDY() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        return $this->meal_datetime->format( 'm-d-Y');
    }

    public function getTime() {
        if ( ! $this->meal_datetime ) {
            return '';
        }
        return $this->meal_datetime->format( 'H:i');
    }

    public function setDateTime( $meal_datetime ) {
        $this->meal_datetime = new DateTime( $meal_datetime );
    }

    public function setDate( $meal_date ) {
        $this->meal_datetime = new DateTime( $meal_date );
    }

    public function setTime( $meal_time ) {
        $this->meal_datetime = new DateTime(
            $this->meal_datetime->format( 'Y-m-d' ) . $meal_time
        );
    }

    public function setDeadlineDate( $deadline_date ) {
        $this->deadline = new DateTime( $deadline_date );
    }

    public function setDeadlineTime( $deadline_time ) {
        $this->deadline = new DateTime(
            $this->deadline->format( 'Y-m-d' ) . $deadline_time
        );
    }

    public function getDeadlineDateMDY() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'm-d-Y g:i A');
    }

    public function getDeadlineDateYMD() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'Y-m-d');
    }

    public function getDeadlineTime() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'H:i');
        // return $this->deadline->format( 'g:i A');
    }

    /**
     * @return DateTime
     */
    public function getDeadlineMDY() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'm-d-Y H:i');
    }

    /**
     * @return DateTime
     */
    public function getDeadlineYMD() {
        if ( ! $this->deadline ) {
            return '';
        }
        return $this->deadline->format( 'Y-m-d H:i');
    }

    public function getDeadlineDatetime() {
        return $this->deadline;
    }

    public function setDeadline( $deadline ) {
        $this->deadline = new DateTime( $deadline );
    }

    /**
     * @return string
     */
    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    /**
     * @return number
     */
    public function getPersonId() {
        return $this->person_id;
    }

    public function setPersonId($person_id) {
        $this->person_id = $person_id;
    }

    public function getTeamLead() {
        return $this->team_lead;
    }
    public function setTeamLead( $name ) {
        $this->team_lead = $name;
    }

    public function emailAttendeesReport( $user_person_id, $attendees, $guests ) {
        $filename = $this->writeAttendeesReportCSV( $attendees, $guests );
        $dao = new PersonDao();
        $person = $dao->findById($user_person_id);
        if ($person === null) {
            throw new NotFoundException('Unknown Person identifier provided.');
        }
        $mail = new PHPMailer;
        $mail->setFrom( "bellcoho@bellcoho.com", 'Bellcoho CommonMeals' );
        $mail->addAddress( $person->getEmail() );
        $mail->Subject = "Meal Attendee Report";
        $mail->Body = "The attendee report for your meal is attached.";
        $mail->addAttachment( $filename );
        if ( !$mail->send()) {
            return 'Mailer error: ' . $mail->ErrorInfo;
        } else {
            return 'Report sent by email';
        }
    }

    public function downloadAttendeesReport( $attendees, $guests ) {
        $filename = $this->writeAttendeesReportCSV( $attendees, $guests );
        $this->makeHeader( $filename );
    }

    private function writeAttendeesReportCSV( $attendees, $guests ) {
        $dir = getcwd();
        $filename = 'logs/meal_attendees_report-' . date('Y-m-d-H-mi') . '.csv';
        $handle = fopen( $filename, 'w' );
        if ( $handle ) {
            fwrite( $handle, "Meal:," . $this->getSummary() . "\n" );
            fwrite( $handle, "Team:," . Utils::getMealTeamLeads( $this->getMealTeamId() ) . "\n");
            fwrite( $handle, "Date and Time:," . $this->getDateTimeMDY() . "\n");
            fwrite( $handle, "Signup by:," . $this->getDeadlineMDY() . "\n");
            fwrite( $handle, "Cost:," . $this->getMealCost() . "\n");
            fwrite( $handle, "Young child:," . $this->getMealCost1() . "\n");
            fwrite( $handle, "Older child:," . $this->getMealCost2() . "\n");
            fwrite( $handle, "\n" );
            fwrite( $handle, "Unit,Name,Age,Veg,GF,DF,Allergies" );
            fwrite( $handle, ",Extra Cost,Total Cost,Unit Total\n" );
            $total_income = 0;
            $count_adult = 0;
            $count_child_young = 0;
            $count_child_older = 0;
            $records = array();
            foreach( $attendees as $attendee ) {
                $person = $attendee->getPerson();
                $name = $person->getFirstName() . " " . 
                            $person->getLastName() . ",";
                $cost = 0;
                $line = $this->writeAbstractAttendee( $attendee, 
                            $count_adult, $count_child_young, 
                            $count_child_older, $count_veg, $count_gf,
                            $count_df, $cost );
                $record = array( 'unit' => $person->getUnit() . $person->getSubUnit(),
                                 'line' => $name . $line,
                                 'cost' => $cost ); 
                $records[] = $record;
            }
            foreach( $guests as $guest ) {
                $name = "Guest,";
                $cost = 0;
                $line = $this->writeAbstractAttendee( $guest, 
                            $count_adult, $count_child_young, 
                            $count_child_older, $count_veg, $count_gf,
                            $count_df, $cost );
                $record = array( 'unit' => $guest->getUnitId(),
                                 'line' => $name . $line,
                                 'cost' => $cost ); 
                $records[] = $record;
            }
            usort( $records, function( $i1, $i2 ) {
                if ( (int)$i1['unit'] < (int)$i2['unit'] ) {
                    return -1;
                } else if ( (int)$i1['unit'] > (int)$i2['unit'] ) {
                    return 1;
                }
                return 0;
                // return( $i1['unit'] <=> $i2['unit'] );
            });
            $unit = 0;
            $unit_cost = 0;
            foreach( $records as $arecord ) {
                if ( $arecord['unit'] === $unit ) {
                    $unit_cost += $arecord['cost'];
                } else {
                    if ( $unit != 0 ) {
                        fwrite( $handle, ",,,,,,,,," . $unit_cost . "\n" );
                    }
                    $unit = $arecord['unit'];
                    $unit_cost = $arecord['cost'];
                }
                fwrite( $handle, $arecord['unit'] . "," . $arecord['line'] . 
                                 $arecord['cost'] . "\n" );
                $total_income += $arecord['cost'];
            }
            if ( $unit != 0 ) {
                fwrite( $handle, ",,,,,,,,," . $unit_cost . "\n" );
            }
            fwrite( $handle, "\nAdults:," . $count_adult . "\n" );
            fwrite( $handle, "Young Child:," . $count_child_young . "\n" );
            fwrite( $handle, "Older Child:," . $count_child_older . "\n" );
            fwrite( $handle, "\nVeg:," . $count_veg . "\n" );
            fwrite( $handle, "GF:," . $count_gf . "\n" );
            fwrite( $handle, "DF:," . $count_df . "\n" );
            fwrite( $handle, "\nTotal income:," . $total_income );
        }
        fclose( $handle );
        return( $filename );
    }

    // Return CSV string of the record
    private function writeAbstractAttendee( $attendee, 
                                    &$count_adult, &$count_child_young, 
                                    &$count_child_older, &$count_veg, 
                                    &$count_gf, &$count_df,
                                    &$attendee_cost ) {
        $age_group = $attendee->getAgeGroup();
        $result = $age_group . ",";
        $count_veg += $this->writeSpecial( $result, $attendee->getSpecialsVeg() );
        $count_gf += $this->writeSpecial( $result, $attendee->getSpecialsGF() );
        $count_df += $this->writeSpecial( $result, $attendee->getSpecialsDF() );
        $result .= $attendee->getSpecialsOther() . ",";
        $result .= $attendee->getExtraCost() . ",";
        if ( $age_group === AbstractMealAttendee::AGE_ADULT ) {
            $count_adult++;
            $attendee_cost = $this->getMealCost() + $attendee->getExtraCost();
        } elseif ( $age_group === AbstractMealAttendee::AGE_CHILD_OLDER ) {
            $count_child_older++;
            $attendee_cost = $this->getMealCost2() + $attendee->getExtraCost();
        } else {
            $count_child_young++;
            $attendee_cost = $this->getMealCost1() + $attendee->getExtraCost();
        }
        return $result;
    }

    private function writeSpecial( &$result, $special ) {
        $checked = 0;
        if ( $special ) {
            $result .= "X";
            $checked = 1;
        }
        $result .= ",";
        return $checked;
    }

    private function makeHeader( $filename ) {
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
        // unlink( $filename );
    }

}
