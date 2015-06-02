    <?php
/**
 * Description: Kode for SIL Håndball. 
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */

// Lister kontaktpersoner som brukes i shortcode [lagenes_kontaktliste]
function list_kontaktpersoner($lag_id, $roller){
    $res = '';
    $entries = FrmEntry::getAll('it.form_id = 2');
    foreach ($entries as $row) {
        if(true){
            $res .= $row->name . $row->lag;    
        }
    }
    $res = '[display-frm-data id=2458 filter=1 lag_id="' . $lag_id . '" rolle="Foreldrekontakt"]';
    return do_shortcode($res);
}

   
add_shortcode( 'Kontaktpersoner', 'kontaktpersoner_func' );
function kontaktpersoner_func($atts){
    extract(shortcode_atts(array('lag_id' => ''), $atts));
    if(!$lag_id) $lag_id = get_post_meta( get_the_ID(), 'lag_id', true );
	if ( $lag_id ) :
	  $ret = FrmProDisplaysController::get_shortcode(array('id' => 497, 'lag_id' => $lag_id));
      $ret .= do_shortcode('<p>[Legg inn kontaktperson lag_id=' . $lag_id . ']</p><br/>');
	else :
	  $ret = '<h2>Kontaktpersoner</h2>';
	  $ret .= 'Denne modulen heter "Kontaktpersoner" og skal brukes for å vise lagets kontaktpersoner.';
	  $ret .= '<br>Siden mangler det tilpassede feltet "lag_id", som må til for at modulen skal virke.';
	  $ret .= '<br>Kontakt webmaster om du ikke vet hvordan du skal fikse det selv.';
	endif;

	return $ret;
}
   
add_shortcode( 'Legg inn kontaktperson', 'leggInnKontaktperson_func' );
function leggInnKontaktperson_func($atts){
    extract(shortcode_atts(array('lag_id' => ''), $atts));
    if(!$lag_id) $lag_id = get_post_meta( get_the_ID(), 'lag_id', true );
	$res = '';
	if ($lag_id) :
	  if (silhUserCanEdit()) :
	    $res = '<p><a class="editlink" href="/innmelding-av-kontaktperson/?lag=' . $lag_id . '&tilbake=' . $_SERVER['REQUEST_URI'] . '">Legg inn ny kontaktperson</a></p>';
	  endif;
	endif;

	return $res;
}



// [lagfordeling]
// Vis fordeling av lag for treningsgruppe
// Bruker sidens lag_id for treningsgruppe. Kan evt. oppgis som parameter til kortkoden.
// Slår opp i tablepress-tabell etter informasjon.
// Opptatert 18.8.2014 av Otto Paulsen


add_shortcode( 'lagfordeling', 'lagfordeling_func' );
function lagfordeling_func($atts){


    $res = '' ;
    
    extract(shortcode_atts(array('lag_id' => ''), $atts));
    if(!$lag_id) $lag_id = get_post_meta( get_the_ID(), 'lag_id', true );
    
    if ($lag_id){
        if ($lag_id == 'all') {
            $res = do_shortcode('[table id=4]');
        } else {
            $table = TablePress::$controller->model_table->load( 4 );
            $data = $table['data'];
            foreach ($data as &$row) {
                if($row[0] == $lag_id) {
                    $lagene = $row[1];
                }
            }
            if (empty($lagene)) {
                $res = $res . 'Lag i årets klasse er ikke satt. Kontakt kontoret for mer informasjon.';
            } else {
                $res = $res . "Lag for sesongen: " . $lagene;
            }
        }
        
    } else {
        if (silhUserCanEdit()) {
            $res = $res . 'lag_id er ikke satt på denne siden. Kontakt webmaster for hjelp.';
        } else {
            $res = "";
        }
    }


    return $res; 
}



// [lagenes_kontaktliste]
//
// Skriv ut kontaktliste for lagene med kontaktpersoner.
// Henter data fra tablepress-tabell og formidable tabell.
// Opptatert 20.8.2014 av Otto Paulsen


add_shortcode( 'lagenes_kontaktliste', 'lagenes_kontaktliste_func' );
function lagenes_kontaktliste_func($atts){

    $res = '<table class="silhTable">
            <caption style="caption-side:bottom;text-align:left;border:none;background:none;margin:0;padding:0;"></caption>
            <thead>
            <tr>
            <th class="column-1"><div>Klasse og lag</div></th>
            <th class="column-2"><div>Kontaktpersoner</div></th>
            </tr>
            </thead>
            <tbody>';


    // Les tablepress tabell nr 4, som har lagfordelingen'
    $table = TablePress::$controller->model_table->load( 4 );
    $data = $table['data'];

    foreach ($data as $key => $drow) {
        $id_arr[$key]  = $drow[0];
        $lag_arr[$key] = $drow[1];
    }

    array_multisort($lag_arr, SORT_NATURAL, $data);


    $rowcount = 0;
    $odd = true;
    foreach ($data as &$row) {
        $lag_id = $row[0];
        $lag = $row[1];
        if($rowcount > 0 and $lag != "" and $lag_id != ""){
            $res = $res . '<tr class="' . ($odd ? 'odd' : 'even') . '"><td>' . $lag . '</td><td>' . list_kontaktpersoner($lag_id, array('Hovedtrener', 'Trener', 'Foreldrekontakt')) . '</td></tr>';
        }
        $rowcount++;
        $odd = !$odd;
    }


    $res = $res . '</tbody></table>';
    return $res;
}


add_shortcode( 'epostliste', 'epostliste_func' );
function epostliste_func($atts){
    
    extract(shortcode_atts(array('rolle' => 'Alle'), $atts));

    $roller = explode(",", $rolle);
    
    global $frm_entry, $frm_entry_meta;

    $res = '<a href="mailto:';

    $entries = $frm_entry->getAll("it.form_id=2");

    $count = 0;

    foreach ( $entries as $entry ) {
        $roll = $frm_entry_meta->get_entry_meta_by_field($entry->id, 11, true);
        if ( in_array($roll, $roller) or strtolower($rolle) == 'alle') {
            $epost = $frm_entry_meta->get_entry_meta_by_field($entry->id, 10, true);
            $count += 1;
            $res .= $epost . ($count > 0 ? ',' : '');            
        }
    }

    $res .= '">' . implode(', ', $roller)  . ' (' . $count . ' stk)' . '</a>';


    return $res;
}



/* Place custom code above this line. */
?>