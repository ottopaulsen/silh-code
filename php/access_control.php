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
    
    global $userAccessManager;
    if (isset($userAccessManager)) :
        $uamAccessHandler = $userAccessManager->getAccessHandler();
	    //$oCurrentUser = $uamAccessHandler->getUserAccessManager()->getCurrentUser();
        $aUserGroupsForPage = $uamAccessHandler->getUserGroupsForObject('page', $page);
        $aUserGroupsForUser = $uamAccessHandler->getUserGroupsForObject('_user_', get_current_user_id());

        foreach ($aUserGroupsForPage as $pageGroups) {
        	foreach ($aUserGroupsForUser as $userGroups) {
        		if ($userGroups->getName() == $pageGroups->getName()) {
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
    
    global $userAccessManager;
    if (isset($userAccessManager)) :
        $uamAccessHandler = $userAccessManager->getAccessHandler();
        //$oCurrentUser = $uamAccessHandler->getUserAccessManager()->getCurrentUser();
        $aUserGroupsForUser = $uamAccessHandler->getUserGroupsForObject('_user_', get_current_user_id());

        foreach ($groups as $group) {
            foreach ($aUserGroupsForUser as $userGroups) {
                if ($userGroups->getName() == $group) {
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
add_shortcode('sideredaktorer', 'sideredaktorer_func');

function sideredaktorer_func($atts){

    $res = "" ;

    extract(shortcode_atts(array('skilletegn' => '<br>'), $atts));

    $isFirst = true;

    global $userAccessManager;
    if (isset($userAccessManager)) :
        $uamAccessHandler = $userAccessManager->getAccessHandler();
        $aUserGroupsForPage = $uamAccessHandler->getUserGroupsForObject('page', get_the_ID());

        foreach ($aUserGroupsForPage as $pageGroups) {
            $aUsers = $pageGroups->getFullUsers();
            
            foreach ($aUsers as $userId => $user) {
                $userdata = get_userdata($userId);
                
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