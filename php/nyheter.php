    <?php
/**
 * Description: Kode for SIL Håndball. 
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */



//[sistenyheter]
// Vis et antall av de siste nyheter, evt. hopp over et antall (offset)
// Opptatert 29.6.2014 av Otto Paulsen

add_shortcode( 'sistenyheter', 'sistenyheter_func' );
function sistenyheter_func($atts){

    global $post;

    extract(shortcode_atts(array('antall' => '1'), $atts));
    extract(shortcode_atts(array('offset' => '0'), $atts));
    extract(shortcode_atts(array('h' => '4'), $atts));
    extract(shortcode_atts(array('cat' => ''), $atts));

    $args = array( 'numberposts' => $antall, 'offset' => $offset, 'category_name' => $cat );
    $myposts = get_posts( $args );
    $res = "" ;

    foreach ( $myposts as $post ) : 
        setup_postdata( $post );
		$res = $res . '<h' . $h . '><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h' . $h . '>';
        $res = $res . get_the_post_thumbnail(  );
        $res = $res . '<p class="sistEndretText">Skrevet den <span class="sistEndretTid">' . get_the_date() . '</span> av <span class="sistEndretForfatter">' . get_the_author() . '</span></p>';
        $res = $res . '<p>' . get_the_excerpt() . '  <a href="' . get_the_permalink() . '"><br>Les innlegget</a></p><br>';
        $res = $res . '<hr><br>';
    endforeach; 
    wp_reset_postdata();

    return $res;
}



//[datertinnlegg]
// Vis et antall av de siste nyheter, evt. hopp over et antall (offset)
// Opptatert 29.6.2014 av Otto Paulsen

add_shortcode( 'datertinnlegg', 'datertinnlegg_func' );
function datertinnlegg_func($atts){

    global $post;

    extract(shortcode_atts(array('cat' => ''), $atts));
    extract(shortcode_atts(array('h' => '4'), $atts));

    $args = array( 'category_name' => $cat,
                   'meta_query' => array(
                        array(
                           'key' => 'utlopsdato',
                        )
                    ) 
                );
    $myposts = get_posts( $args );
    $res = '' ;

    foreach ( $myposts as $post ) : 
        setup_postdata( $post );
        $utlopsdato = get_post_meta($post->ID, 'utlopsdato', true);
        if ($utlopsdato >= date("Y-m-d")) {
            $res .= '<div class="silhArrangement">';
            $res .= '<h' . $h . '><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h' . $h . '>';
            $res .= '<p>' . get_the_excerpt() . '</p><br>';
            $res .= '</div>';
        }
    endforeach; 
    wp_reset_postdata();

    return $res;
}

/* Sett lengde i antall ord på kortversjon av nyheter */
add_filter( 'excerpt_length', 'silh_excerpt_length', 999 );
function silh_excerpt_length( $length ) {
	return 60;
}

/* Place custom code above this line. */
?>