<?php

/**
 * Main application class.
 */
final class Index {

    const DEFAULT_PAGE = 'home';
    const PAGE_DIR = '../page/';
    const LAYOUT_DIR = '../layout/';

    /**
     * System config.
     */
    public function init() {
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');
        set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'loadClass'));
        // session
        if ( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }
    }

    /**
     * Run the application!
     */
    public function run() {
        $this->runPage($this->getPage());
    }

    /**
     * Exception handler.
     */
    public function handleException(Exception $ex) {
        $extra = array('message' => $ex->getMessage());
        if ($ex instanceof NotFoundException) {
            header('HTTP/1.0 404 Not Found');
            $this->runPage('404', $extra);
        } else {
            // TODO log exception
            header('HTTP/1.1 500 Internal Server Error');
            $this->runPage('500', $extra);
        }
    }

    /**
     * Class loader.
     */
    public function loadClass($name) {
        $classes = array(
            'Config' => '../config/Config.php',
            'RError' => '../validation/RError.php',
            'Flash' => '../flash/Flash.php',
            'NotFoundException' => '../exception/NotFoundException.php',
            'AbstractSearchCriteria' => '../dao/AbstractSearchCriteria.php',
            'AbstractMealAttendee' => '../model/AbstractMealAttendee.php',
            'GuestDao' => '../dao/GuestDao.php',
            'GuestMapper' => '../mapping/GuestMapper.php',
            'Guest' => '../model/Guest.php',
            'GuestSearchCriteria' => '../dao/GuestSearchCriteria.php',
            'AttendeeValidator' => '../validation/AttendeeValidator.php',
            'MealDao' => '../dao/MealDao.php',
            'MealMapper' => '../mapping/MealMapper.php',
            'Meal' => '../model/Meal.php',
            'MealSearchCriteria' => '../dao/MealSearchCriteria.php',
            'MealValidator' => '../validation/MealValidator.php',
            'MemberAttendeeDao' => '../dao/MemberAttendeeDao.php',
            'MemberAttendeeMapper' => '../mapping/MemberAttendeeMapper.php',
            'MemberAttendee' => '../model/MemberAttendee.php',
            'MemberAttendeeSearchCriteria' => '../dao/MemberAttendeeSearchCriteria.php',
            'MealTeamDao' => '../dao/MealTeamDao.php',
            'MealTeamSearchCriteria' => '../dao/MealTeamSearchCriteria.php',
            'MealTeamMapper' => '../mapping/MealTeamMapper.php',
            'MealTeam' => '../model/MealTeam.php',
            'PersonDao' => '../dao/PersonDao.php',
            'PersonMapper' => '../mapping/PersonMapper.php',
            'Person' => '../model/Person.php',
            'PersonSearchCriteria' => '../dao/PersonSearchCriteria.php',
            'PersonValidator' => '../validation/PersonValidator.php',
            'PersonNameDao' => '../dao/PersonNameDao.php',
            'PersonNameMapper' => '../mapping/PersonNameMapper.php',
            'PersonName' => '../model/PersonName.php',
            'PersonNameSearchCriteria' => '../dao/PersonNameSearchCriteria.php',
            'PersonNameValidator' => '../validation/PersonNameValidator.php',
            'UnitDao' => '../dao/UnitDao.php',
            'UnitMapper' => '../mapping/UnitMapper.php',
            'Unit' => '../model/Unit.php',
            'UnitSearchCriteria' => '../dao/UnitSearchCriteria.php',
            'UnitValidator' => '../validation/UnitValidator.php',
            'UnitPersonDao' => '../dao/UnitPersonDao.php',
            'UnitPersonMapper' => '../mapping/UnitPersonMapper.php',
            'UnitPerson' => '../model/UnitPerson.php',
            'UnitPersonSearchCriteria' => '../dao/UnitPersonSearchCriteria.php',
            'UnitPersonValidator' => '../validation/UnitPersonValidator.php',
            'DBConnection' => '../dao/DBConnection.php',
            'Utils' => '../util/Utils.php',
        );
        if (!array_key_exists($name, $classes)) {
            die('Class "' . $name . '" not found.');
        }
        require_once $classes[$name];
    }

    private function getPage() {
        $page = self::DEFAULT_PAGE;
        if (array_key_exists('page', $_GET)) {
            $page = $_GET['page'];
        }
        return $this->checkPage($page);
    }

    private function checkPage($page) {
        if (!preg_match('/^[a-z0-9-]+$/i', $page)) {
            // TODO log attempt, redirect attacker, ...
            throw new NotFoundException('Unsafe page "' . $page . '" requested');
        }
        if (!$this->hasScript($page) && !$this->hasTemplate($page)) {
            // TODO log attempt, redirect attacker, ...
            throw new NotFoundException('Page "' . $page . '" not found');
        }
        return $page;
    }

    private function runPage($page, array $extra = array()) {
        $run = false;
        if ($this->hasScript($page)) {
            $run = true;
            $runPage = $this->getScript($page);
            require $runPage;
        }
        if ($this->hasTemplate($page)) {
            $run = true;
            // data for main template
            $template = $this->getTemplate($page);
            $flashes = null;
            if (Flash::hasFlashes()) {
                $flashes = Flash::getFlashes();
            }

            // main template (layout)
            require self::LAYOUT_DIR . 'index.phtml';
        }
        if (!$run) {
            die('Page "' . $page . '" has neither script nor template!');
        }
    }

    private function getScript($page) {
        return self::PAGE_DIR . $page . '.php';
    }

    private function getTemplate($page) {
        return self::PAGE_DIR . $page . '.phtml';
    }

    private function hasScript($page) {
        return file_exists($this->getScript($page));
    }

    private function hasTemplate($page) {
        return file_exists($this->getTemplate($page));
    }

}

require_once '../util/Auth2.php';
require_once '../util/Utils.php';

ini_set( 'display_errors', 'off');

$index = new Index();
$index->loadClass( 'PersonName' );
$index->loadClass( 'PersonNameSearchCriteria' );
$index->loadClass( 'PersonSearchCriteria' );
$index->loadClass( 'PersonNameDao' );
$index->loadClass( 'PersonNameMapper' );
$index->loadClass( 'UnitPerson' );
$index->loadClass( 'UnitPersonDao' );
$index->loadClass( 'UnitPersonMapper' );
$index->loadClass( 'UnitPersonSearchCriteria' );

if ( isset( $_POST[ 'person_id' ]) && isset( $_POST[ 'password' ])) {
    if ( authenticate( $_POST[ 'person_id' ], $_POST[ 'password' ] )) {
        $index->init();
        // run application!
        $index->run();  
    } else {
        ?>
        <html>
        <head>
            <title>Login failed</title>
            <link rel="stylesheet" type="text/css" href="css/login.css" />
        </head>
        <body>
            <h3>Login failed</h3>
            <p>Use the back arrow in your browser and try again.</p>
        </body>
        <?php
    }
} else {
    if ( ! isset( $_SESSION['oc_user'] ))  {
        $people = Utils::getCurrentAdultsAndIds();
    ?>
<html>
<head>
  <title>User Login</title>
  <link rel="stylesheet" type="text/css" href="css/login.css" />
</head>
<body>
  <form name="frmUser" method="post" action="index.php">
    <table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
      <tr class="tableheader">
        <td align="center" colspan="2">Log in for Common Meals</td>
      </tr>
      <tr class="tablerow">
        <td align="right">Person</td>
        <td><select name='person_id' size='1'>
        <?php
            $first_name = '';
            $last_name = '';
            $person_id = 0;
            foreach ( $people as $person ) {
                $person_id = $person->getId();
                $first_name = $person->getFirstName();
                $last_name = $person->getLastName();
                echo '<option value="' . $person_id . '">' . $first_name . 
                     ' ' . $last_name . '</option>';
            }
        ?>
            </select></td>
      </tr>
      <tr class="tablerow">
        <td align="right">Password</td>
        <td><input type="password" name="password"></td>
      </tr>
      <tr class="tableheader">
        <td align="center" colspan="2">
            <input type="submit" name="submit" value="Submit">
        </td>
      </tr>
    </table>
  </form>
</body></html>
<?php 
    } else {
        $index->init();
        // run application!
        $index->run();  
    }
}
?>