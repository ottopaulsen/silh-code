    <?php
/**
 * Description: Kode for SIL Håndball. (http://justintadlock.com/archives/2011/02/02/creating-a-custom-functions-plugin-for-end-users)
 * Author: Otto Paulsen
 * Author URI: http://yoursite.com
 * Version: 0.1.0
 */

/* Place custom code below this line. */



/* [GoogleKalender kalender_var="kalender" type="mnd" hoyde="300"]
   Brukes for å legge inn Google-kalender på siden.
   Legger inn link som tar deg til form der du kan legge inn Kalender-ID.
*/
add_shortcode( 'GoogleKalender', 'googleKalender_func' );
add_shortcode( 'GoogleKalenderMnd', 'googleKalender_func' );
function googleKalender_func($atts, $tag){
    $res = "";

    extract(shortcode_atts(array('kalender_id' => 'Kalender-ID'), $atts));
    extract(shortcode_atts(array('kalender_var' => 'kalender'), $atts));
    extract(shortcode_atts(array('type' => 'mnd'), $atts));
    extract(shortcode_atts(array('hoyde' => '300'), $atts));

    $kal_id = get_post_meta( get_the_ID(), $kalender_var, true );

    if (empty($kal_id) and !empty($kalender_id) and $kalender_id != 'Kalender-ID') {
        // kalender-id satt direkte i shortcode. Sikkert før input-skjema ble laget. Bruk den.
        $kal_id = $kalender_id;
    }

    if (empty($kal_id)){
        if(silhUserCanEdit()) {
            $res .= '<a class="editlink" href="/sett-google-kalender-id?side_id=' . get_the_ID() . '&kalender_var=' . $kalender_var . '">Legg inn Kalender-ID</a>';
        }
    } else {
        // Kalender-ID funnet
        if ($type == 'agenda') {
            $res .= '<iframe src="https://www.google.com/calendar/embed?showTitle=0&showTz=0&showTabs=0&showCalendars=0&mode=AGENDA&height=' . 
                     $hoyde . '&wkst=2&bgcolor=%23FFFFFF&src=' . $kal_id . '&color=%2323164E&ctz=Europe%2FOslo" style=" border-width:0 " width="100%" height="' . 
                     $hoyde . '" frameborder="0" scrolling="no"></iframe>';
        } else {
            $res .= '<iframe style="border: 0;" src="https://www.google.com/calendar/embed?src=' . 
                     $kal_id. '&ctz=Europe/Oslo" height="600" width="100%" frameborder="0" scrolling="no"></iframe>';
        }
        if(silhUserCanEdit()) {
            $res .= '<a class="editlink" href="/sett-google-kalender-id?side_id=' . get_the_ID() . '&kalender_var=' . $kalender_var . '">Endre Kalender-ID</a>';
        }
    }

    //update_post_meta(2751, 'otto test', 'Test: kal_id = ' . $kal_id );

    return $res;
}

// Skriv ut google-kalender i agenda-format
// Denne er foreldet - bruk GoogleKalender i stedet.
add_shortcode( 'GoogleKalenderAgenda', 'GoogleKalenderAgenda_func' );
function GoogleKalenderAgenda_func($atts){
    extract(shortcode_atts(array('kalender_id' => 'mangler'), $atts));
    extract(shortcode_atts(array('hoyde' => '300'), $atts));

    $argArr = array('kalender_id' => $kalender_id,
                     'hoyde'      => $hoyde,
                     'type'       => 'agenda');

    return googleKalender_func ($argArr);
}



/* [GoogleKalender kalender_var="kalender" type="mnd" hoyde="300"]
   Brukes for å legge inn Google-kalender på siden.
   Legger inn link som tar deg til form der du kan legge inn Kalender-ID.
*/

   
add_shortcode( 'GoogleSkjema', 'GoogleSkjema_func' );
function GoogleSkjema_func($atts){

    $res = "";

    extract(shortcode_atts(array('vis_svar' => 'ja'), $atts));
    extract(shortcode_atts(array('hoyde' => '500'), $atts));
    extract(shortcode_atts(array('svarhoyde' => '500'), $atts));

    $skjema_id = get_post_meta( get_the_ID(), 'skjema_id', true );
    $skjemasvar_url = get_post_meta( get_the_ID(), 'skjemasvar_url', true );

    
    if (empty($skjema_id)){
        if(silhUserCanEdit()) {
            $res .= '<a class="editlink" href="/sett-google-skjema-id?side_id=' . get_the_ID() . 
                    '&skjema_var=skjema_id&skjemasvar_var=skjemasvar_url' . 
                    '">Legg inn skjema</a>';
        }
    } else {
        if(silhUserCanEdit()) {
            $res .= '<a class="editlink" href="/sett-google-skjema-id?side_id=' . get_the_ID() . 
                    '&skjema_var=skjema_id&skjemasvar_var=skjemasvar_url&skjema_id=' . $skjema_id . '&skjemasvar_url=' . urlencode($skjemasvar_url) . 
                    '">Endre skjema</a>';
        }
        if(substr($skjema_id, 0, 4) == 'http') {
            $url = $skjema_id;
        } else {
            $url = 'https://docs.google.com/forms/d/' . $skjema_id . '/viewform';
        }
        $res .= '<iframe src="' . $url . '?embedded=true" width="100%" height="' . $hoyde . '" frameborder="0" marginheight="0" marginwidth="0">Laster inn ...</iframe>';
    }

    if (!empty($skjemasvar_url)){

        $res .= '<br><p>Det kan ta inntil 5 minutter før svaret vises.</p>';
        $res .= '<iframe src="' . 
                 str_replace('edit', 'pubhtml', $skjemasvar_url) . 
                 '&amp;single=true&amp;widget=true&amp;headers=false" width="100%" height="' . $svarhoyde . '">Laster inn ...</iframe>';
    }

    return $res;
}





/* Place custom code above this line. */
?>