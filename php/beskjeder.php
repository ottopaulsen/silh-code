    <?php
/**
 * Description: Kode for SIL Håndball. 
 * Kode for beskjedmodulen på lagsidene
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */


// Skriv ut beskjeder fra Formidable view
add_shortcode( 'Beskjeder', 'beskjeder_func' );
function beskjeder_func($atts){

    extract(shortcode_atts(array('lag_id' => ''), $atts));
    if(!$lag_id) $lag_id = get_post_meta( get_the_ID(), 'lag_id', true );

    if ($lag_id) :
        $ret = do_shortcode('<p>[Legg inn beskjed lag_id=' . $lag_id . ']</p><br/>');
        $ret .= FrmProDisplaysController::get_shortcode(array('id' => 530, 'lag_id' => $lag_id));
    else :
        $ret = '<h2>Beskjeder</h2>';
        $ret .= 'Denne modulen heter "Beskjeder" og kan brukes for å legge ut beskjeder på siden.';
        $ret .= '<br>Siden mangler det tilpassede feltet "lag_id", som må til for at beskjedmodulen skal virke.';
        $ret .= '<br>Kontakt webmaster om du ikke vet hvordan du skal fikse det selv.';
    endif;

    return $ret;
}

// Vis link for å legge inn beskjed for bruker med skriverettighet
add_shortcode( 'Legg inn beskjed', 'legginnbeskjed_func' );
function legginnbeskjed_func($atts){
    extract(shortcode_atts(array('lag_id' => ''), $atts));
    if(!$lag_id) $lag_id = get_post_meta( get_the_ID(), 'lag_id', true );

    $res = '';

    if ($lag_id) :
      if (silhUserCanEdit()) :
        $res = '<a class="editlink" href="/legg-inn-beskjed-til-laget/?lag=' . $lag_id . '&tilbake=' . $_SERVER['REQUEST_URI'] . '">Legg inn beskjed</a>';
      endif;
    endif;

    return $res;
}




/* Place custom code above this line. */
?>