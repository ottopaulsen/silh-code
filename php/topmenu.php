<?php
/**
 * Description: Kode for å sette riktig current meny.
 * Author: Otto Paulsen
 * 
 * Version: 1
 */

/* Place custom code below this line. */


add_filter( 'nav_menu_css_class', 'namespace_menu_classes', 10, 2 );
function namespace_menu_classes( $classes , $item ){
    $meny = $_GET['meny']; 
    if($meny) {
        $pages = get_post_ancestors($meny);
        $pages[] = $meny;
        foreach ($pages as $page) {
            $titles[] = get_post($page)->post_title;
        }

        if(array_search($item->title, $titles) !== false) {
            $classes[] = 'current-page-ancestor';
        } elseif(($key = array_search('current-page-ancestor', $classes)) !== false) {
            unset($classes[$key]);
        }
    }
    return $classes;
}


/* Place custom code above this line. */
?>
