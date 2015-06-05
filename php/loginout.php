    <?php
/**
 * Description: Kode for SIL Håndball. 
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */

function list_user_groups() {

	global $oUserAccessManager;
    $res = '';
    $first = true;

    if (isset($oUserAccessManager)) :
        $uamAccessHandler = $oUserAccessManager->getAccessHandler();
	    $oCurrentUser = $uamAccessHandler->getUserAccessManager()->getCurrentUser();
        $aUserGroupsForUser = $uamAccessHandler->getUserGroupsForObject('user', $oCurrentUser->ID);

    	foreach ($aUserGroupsForUser as $userGroups) {
    		if(!$first) $res .= ', ';
    		$res .= $userGroups->getGroupName();
    		$first = false;
    	}

      if (is_super_admin()) {
        if(!$first) $res .= ', ';
        $res .= 'admin';
      }      

    endif;

    return $res;
}


   
add_shortcode( 'loginout', 'loginout_func' );
function loginout_func($atts){

	$res = '';

	if (is_user_logged_in()){

	  global $current_user;
      get_currentuserinfo();

      $res .= 'Innlogget som: ' . $current_user->user_login . "\n";
      $res .= 'Epost: ' . $current_user->user_email . "\n";
      $res .= 'Tilganger: ' . list_user_groups();
      /*
      echo 'User first name: ' . $current_user->user_firstname . "\n";
      echo 'User last name: ' . $current_user->user_lastname . "\n";
      echo 'User display name: ' . $current_user->display_name . "\n";
      echo 'User ID: ' . $current_user->ID . "\n";
      */



		$res .= "\n\n" . '<a href="' . wp_logout_url(get_permalink()) . '">Logg ut</a>';
	} else {
		$args = array('echo' => false,
                  'remember' => true);
		$res .= wp_login_form( $args );
	}

	return $res;

}

/* Place custom code above this line. */
?>