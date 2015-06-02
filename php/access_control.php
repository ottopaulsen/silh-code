    <?php
/**
 * Description: Kode for SIL Håndball. 
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */


   
function silhUserCanEdit($page = 0) {
    $userCanWrite = FALSE; 

    if ($page == 0) $page = get_the_ID();
    
    global $oUserAccessManager;
    if (isset($oUserAccessManager)) :
        $uamAccessHandler = $oUserAccessManager->getAccessHandler();
	    $oCurrentUser = $uamAccessHandler->getUserAccessManager()->getCurrentUser();
        $aUserGroupsForPage = $uamAccessHandler->getUserGroupsForObject('page', $page);
        $aUserGroupsForUser = $uamAccessHandler->getUserGroupsForObject('user', $oCurrentUser->ID);

        foreach ($aUserGroupsForPage as $pageGroups) {
        	foreach ($aUserGroupsForUser as $userGroups) {
        		if ($userGroups->getGroupName() == $pageGroups->getGroupName()) {
        			$userCanWrite = TRUE;
        		}
        	}
        }

    endif;

    # Administrators can always edit
    if (is_super_admin()) {
        $userCanWrite = TRUE;
    }

    return $userCanWrite;
}

// Check if user is in one of the groups
function silhUserInGroup($groups) {
    $userInGroup = FALSE; 
    
    global $oUserAccessManager;
    if (isset($oUserAccessManager)) :
        $uamAccessHandler = $oUserAccessManager->getAccessHandler();
        $oCurrentUser = $uamAccessHandler->getUserAccessManager()->getCurrentUser();
        $aUserGroupsForUser = $uamAccessHandler->getUserGroupsForObject('user', $oCurrentUser->ID);

        foreach ($groups as $group) {
            foreach ($aUserGroupsForUser as $userGroups) {
                if ($userGroups->getGroupName() == $group) {
                    $userInGroup = TRUE;
                }
            }
        }

    endif;

    # Administrators can always edit
    if (is_super_admin()) {
        $userInGroup = TRUE;
    }

    return $userInGroup;
}


// [ifusercanedit]
// Vis innhold i shortcoden hvis den innloggede brukeren kan editere siden
// Opptatert 18.8.2014 av Otto Paulsen

add_shortcode( 'ifusercanedit', 'ifusercanedit_func' );
function ifusercanedit_func($atts, $content){

    $res = "" ;

    if ( silhUserCanEdit() ) {
        $res = $res . $content;
    } 

    return $res;
}


// [ifuseringroup]
// Vis innhold i shortcoden hvis den innloggede brukeren er med i en av de gruppene som er oppgitt
// Opptatert 18.8.2014 av Otto Paulsen

add_shortcode( 'ifuseringroup', 'ifuseringroup_func' );
function ifuseringroup_func($atts, $content){

    $res = '';

    extract(shortcode_atts(array('grupper' => ''), $atts));

    if ( silhUserInGroup(explode(',', $grupper)) ) {
        $res .= do_shortcode($content);
    } 

    return $res;
}


// List brukere som kan redigere siden
add_shortcode( 'sideredaktorer', 'sideredaktorer_func' );
function sideredaktorer_func($atts){

    $res = "" ;

    extract(shortcode_atts(array('skilletegn' => '<br>'), $atts));

    $isFirst = true;

    global $oUserAccessManager;
    if (isset($oUserAccessManager)) :
        $uamAccessHandler = $oUserAccessManager->getAccessHandler();
        $aUserGroupsForPage = $uamAccessHandler->getUserGroupsForObject('page', get_the_ID());

        foreach ($aUserGroupsForPage as $pageGroups) {
        	$aUsers = $pageGroups->getFullUsers();
            foreach ($aUsers as $user) {
                $userdata = get_userdata($user->id);
                if($isFirst) {
                    $isFirst = false;
                } else {
                    $res .= $skilletegn;
                }
                $res .= $userdata->first_name . ' ' . $userdata->last_name;
            }
        }

    endif;

    return $res;
}




/* Place custom code above this line. */
?>