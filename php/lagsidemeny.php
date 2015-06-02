    <?php
/**
 * Description: Kode for SIL Håndball. 
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */ 


   
/*
add_shortcode( 'lagsidemeny1', 'lagsidemeny1_func' );
function lagsidemeny1_func($atts){

    extract(shortcode_atts(array('foreldreside' => '0'), $atts));

    $args = array (
                'hierarchical' => 0,
                'sort_column' => 'post_title',
                'sort_order' => 'asc',
                'child_of' => $foreldreside
            );
    $pages = get_pages($args);

    $res = '<table>';
    foreach ( $pages as $page ) {
        $lag_id = get_post_meta( $page->ID, 'lag_id', true );
        $title = $page->post_title;
        $url = get_permalink($page->ID);
        $res .= '<tr>' . 
                '<td><a href="' . $url . '">' . $lag_id . '</a></td>' . 
                '<td><a href="' . $url . '"><h3>' . $title . '</h3></a></td>' . 
                '</tr>';
    }
    $res .= '</table>';

    return $res;
}
*/

add_shortcode( 'lagsidemeny', 'lagsidemeny_func' );
function lagsidemeny_func($atts){

    extract(shortcode_atts(array('foreldreside' => '0'), $atts));

    $args = array (
                'hierarchical' => 0,
                'sort_column' => 'post_title',
                'sort_order' => 'asc',
                'parent' => $foreldreside
            );
    $pages = get_pages($args);

    $res = '';
    foreach ( $pages as $page ) {
        $lag_id = get_post_meta( $page->ID, 'lag_id', true );
        $title = $page->post_title;
        $url = get_permalink($page->ID);
        //$res .= '<a class="undersidemenya" href="' . $url . '"><span class="undersidemeny">' . $title . '</span></a>';
        $res .= '<a href="' . $url . '"><span class="undersidemeny">' . $title . '</span></a>';
    }
    $res .= '';

    return $res;
}

/* Place custom code above this line. */
?>