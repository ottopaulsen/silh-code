<?php
/**
 * Description: Kode for SIL Håndball.
 * Author: Otto Paulsen
 * Author URI: http://yoursite.com
 * Version: 0.1.0
 */

/* Place custom code below this line. */

/* Returner tekstfelt med angitt nr for angitt side */
function getSilhTekstfelt($side_id, $nr){
    $tekst = get_post_meta( $side_id, 'tekst_' . $nr, true );
    return $tekst;
}



/* [tekstfelt tekstnavn="navn"]
   Brukes for å legge inn tekst som kan redigeres fra siden.
   Lagres i sidevariabel.
   Kan ha flere på en side, bare gi dem forskjllig tekstnavn.
*/
add_shortcode( 'tekstfelt', 'tekstfelt_func' );
function tekstfelt_func($atts){
    $res = '';

    extract(shortcode_atts(array('nr' => '1'), $atts));

    $tekst = get_post_meta( get_the_ID(), 'tekst_' . $nr, true );

    if (empty($tekst)){
        if(silhUserCanEdit()) {
            $res .= '<a class="editlink" href="/rediger-tekstfelt?side_id=' . get_the_ID() . '&tekstnavn=' . $nr . '">Legg inn tekst</a>';
        }
    } else {
        // Tekst funnet
        if(silhUserCanEdit()) {
            $res .= '<br/><p align="right"><a class="editlink" href="/rediger-tekstfelt?side_id=' . get_the_ID() . '&tekstnavn=' . $nr . '">Rediger tekst</a></p>';
        }
        $res .= '<div class="tekstfelt">' . do_shortcode($tekst) . '</div>';
    }

    return $res;
}


add_shortcode( 'get_tekstfelt', 'get_tekstfelt_func' );
function get_tekstfelt_func($atts){
    $res = '';

    extract(shortcode_atts(array('side' => ''), $atts));
    extract(shortcode_atts(array('nr' => '1'), $atts));

    if($side){
        $res = getSilhTekstfelt($side, $nr);
    }

    return $res;
}


/* Place custom code above this line. */
?>