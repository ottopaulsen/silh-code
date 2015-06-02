<?php
/*
 Plugin Name: SILH Code
 Plugin URI: https://github.com/ottopaulsen/silh-code
 Description: Kode for SIL Håndball.
 Author: Otto Paulsen
 GitHub Plugin URI: https://github.com/ottopaulsen/silh-code
 GitHub Branch: master
 Version: 2.0.1
 */


/* Place custom code below this line. */




/* Check if the user and the page is member of the same UAM group.
   If so, return true.

   TO DO: Return TRUE also if ALL users can write, and not only group members.

   TO DO: Take page id as optional argument. If set, check for that page, not current. To be used in input forms (Formidable).

   Author: Otto Paulsen
   Date:   21.04.2014
*/

require_once dirname( __FILE__ ) .'/php/access_control.php';
require_once dirname( __FILE__ ) .'/php/loginout.php';
require_once dirname( __FILE__ ) .'/php/silh_google.php';
require_once dirname( __FILE__ ) .'/php/showhide.php';
require_once dirname( __FILE__ ) .'/php/beskjeder.php';
require_once dirname( __FILE__ ) .'/php/tekstfelt.php';
require_once dirname( __FILE__ ) .'/php/linker.php';
require_once dirname( __FILE__ ) .'/php/treningstid.php';
require_once dirname( __FILE__ ) .'/php/kontaktpersoner.php';
require_once dirname( __FILE__ ) .'/php/nyheter.php';
require_once dirname( __FILE__ ) .'/php/formidable_silh.php';
require_once dirname( __FILE__ ) .'/php/lagsidemeny.php';
require_once dirname( __FILE__ ) .'/php/klubbsidemeny.php';
require_once dirname( __FILE__ ) .'/php/topmenu.php';


//[sidebar_klubbside]
// Vis riktig meny baser på hvilken hovedside brukeren er på
// Opptatert 11.6.2014 av Otto Paulsen
add_shortcode( 'sidebar_klubbside', 'sidebar_klubbside_func' );
function sidebar_klubbside_func($atts){

    // Legg inn meny basert på URL-parameter 'meny' eller sidevariabel 'meny'
    $meny = $_GET['meny'];
    if (empty($meny)) {
        $meny = get_post_meta( get_the_ID(), 'meny', true );
    }
    
    if ($meny){
        if ($meny == 'menynavn') {
        } else {
            //echo '<h4>' . $meny . '</h4>';
            wp_nav_menu( array( 'menu' => $meny,
                                'menu_id' => 'klubbsidemenylinje' ) );
        }
    }

}




// Denne funka ikke - så den er ikke i bruk. Otto.
add_shortcode( 'sorter_dag', 'sorter_dag_func' );
function sorter_dag_func($atts){

    extract(shortcode_atts(array('dag' => 'Mandag'), $atts));

    $res = "0";

    switch($dag){
        case "Mandag":
            $res = 1;
            break;
        case "Tirdag":
            $res = 2;
            break;
        case "Onsdag":
            $res = 3;
            break;
        case "Torsdag":
            $res = 4;
            break;
        case "Fredag":
            $res = 5;
            break;
        case "Lørdag":
            $res = 6;
            break;
        case "Søndag":
            $res = 7;
            break;
    }

    return $res;
}




add_shortcode( 'listpages', 'listpages_func' );
function listpages_func($atts){

    $args = array (
                'hierarchical' => 0,
                'sort_column' => 'post_author',
                'sort_order' => 'asc'
            );
    $pages = get_pages($args);

    $res = '<table>';
    foreach ( $pages as $page ) {
        $user = get_user_by( 'id',  $page->post_author);
        $res .= '<tr>' . 
                '<td><a href="' . $page->guid . '">' . $page->post_title . '</a></td>' . 
                '<td>' . $user->first_name . ' ' . $user->last_name . '</td>' . 
                '<td>' . $page->post_modified . '</td>' . 
                '</tr>';
    }
    $res .= '</table>';

    return $res;
}

   
add_shortcode( 'Klubbside header', 'klubbsideHeader_func' );
function klubbsideHeader_func($atts){
    return '<h1>' . get_the_title(get_the_ID()) . '</h1>';
}   
   
// Skriver tittel på siden som overskrift 1
add_shortcode( 'Sidetittel', 'sidetittel_func' );
function sidetittel_func($atts){
    return '<h1>' . get_the_title(get_the_ID()) . '</h1>';
}   

// Skriv når og hvem som sist endret siden
add_shortcode( 'sistendret', 'sistendret_func' );
function sistendret_func(){
    # Skriv informasjon om når siden sist ble endret, og av hvem.
    $endretAv = get_the_modified_author();
    $endretTid = get_the_modified_date('j.n.y');
    $ret = '<div class="sistEndretText"> Sist endret <span class="sistEndretTid">' . $endretTid . '</span> av <span class="sistEndretForfatter">' . $endretAv . '</span></div>';
    return $ret;
}



// Gir en dato som er 60 dager fram i tid. Kan endres med dager=x.
add_shortcode( 'Default dato', 'defaultdato_func' );
function defaultdato_func($atts){
    $res = '';
    extract(shortcode_atts(array('dager' => '60'), $atts));
    $res = date('d.m.Y', time() + (60 * 60 * 24 * $dager));
    return $res;
}


// Returner link til forrige side
add_shortcode( 'Forrige side', 'forrigeside_func' );
function forrigeside_func($atts){
    return $_SERVER['HTTP_REFERER'];
}


// Returner link til denne side
add_shortcode( 'urldenneside', 'urldenneside_func' );
function urldenneside_func($atts){
    return $_SERVER['REQUEST_URI'];
}




add_shortcode( 'lagsidefooter', 'lagsidefooter_func' );
function lagsidefooter_func($atts){
    $res = '';

    $res .= '<span class="lagsideFooter">';
    $res .= 'Redaktører for denne siden: ' . sideredaktorer_func(array('skilletegn' => ', '));
    $res .= sistendret_func();
    $res .= '</span>';
    return $res;
}



/* Place custom code above this line. */
?>
