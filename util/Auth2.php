<?php

	//
	// This file implements the authentication using
	// HTTP digest algorithm.
	// just include it on you php file and call authenticate();
	// written by Jader Feijo (jader@movinpixel.com)
	//

        session_start();

	require_once( '../model/AbstractModel.php' );
        require_once( '../model/Person.php' );
        require_once( '../dao/AbstractDao.php' );
        require_once( '../dao/PersonDao.php' );
        require_once( '../mapping/PersonMapper.php' );
        require_once( '../config/Config.php' );

	function get_password($person_id) {
		// return the password for the given username
                $dao = new PersonDao();
		$person = $dao->findById($person_id);
		$_SESSION[ 'oc_user_role' ] = null;
                if ( $person ) {
		    if ( $person->getIsMealAdmin() ) {
                        $_SESSION['oc_user_role'] = 'MEALS_ADMIN';
		    }
                    return $person->getPassword();
                }
		return null;
	}
	
	function authenticate( $person_id, $pwd ) {
		$password = get_password($person_id);
		if (!$password) {
			return FALSE;
		}
		if ($password != $pwd) {
			return FALSE;
		}
		
                $_SESSION['oc_user'] = $person_id;
                    
		return TRUE;
	}

?>
