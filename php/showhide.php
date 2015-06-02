    <?php
/**
 * Description: Vis/skjul innhold bare ved å klikke på en link.
 * Author: Otto Paulsen
 * Author URI: http://yoursite.com
 * Version: 0.1.0
 */

/* Place custom code below this line. */

/*
  Kode for å vise/skjule deler av innholdet på en side.
  Bruk kortkoden [showhide] (se nedenfor) for å skjule/vise content.

*/

function install_showhide() 
{

     wp_enqueue_script( 'toggle_showhide_handle', plugins_url() . '/silh_code/js/showhide.js', array('jquery'), null);
     wp_localize_script( 'toggle_showhide_handle', 'toggle_showhide_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}


add_action('template_redirect', 'install_showhide');

add_action("wp_ajax_nopriv_toggle_showhide", "toggle_showhide");
add_action("wp_ajax_toggle_showhide", "toggle_showhide");

function toggle_showhide()
{
    $page_id = $_POST['page_id'];
    $var_name = $_POST['var_name'];
    $default_value = $_POST['default_value'];

    $vis_innhold = get_post_meta( $page_id, $var_name, true );

    if(empty($vis_innhold))
        $vis_innhold = $default_value;

    if(strtolower($vis_innhold) == 'ja')
        $vis_innhold = 'nei';
    else
        $vis_innhold = 'ja';

    update_post_meta($page_id, $var_name, $vis_innhold);

    die();
}



// [showhide id=1 innhold="innholdsbeskrivelse"]
// Lar bruker med edit-rettighet velge med knapp om innhold i shortkoden skal vises eller ikke.
// Lagrer verdien som sidevariabel.
// Opptatert 14.9.2014 av Otto Paulsen

add_shortcode( 'showhide', 'showhide_func' );
function showhide_func($atts, $content){

    $res = "" ;
    $show = false;

    extract(shortcode_atts(array('id' => 1), $atts));
    extract(shortcode_atts(array('innhold' => 'innhold'), $atts));

    $var = 'showhide_' . $id;

    $vis_innhold = get_post_meta( get_the_ID(), $var, true );

    if(empty($vis_innhold))
        $vis_innhold = 'nei';

    if(strtolower($vis_innhold) == 'ja')
        $show = true;

    if ( silhUserCanEdit() ) {
        if($show){
            $res .= '<a class="editlink" href="#" onclick="toggle_showhide(&quot;' . get_the_ID() . '&quot;, &quot;' . $var . '&quot;, &quot;nei&quot;);">Skjul ' . $innhold . '</a>';
        } else {
            $res .= 'Innholdet nedenfor er skjult for andre brukere. <a class="editlink" href="#" onclick="toggle_showhide(&quot;' . get_the_ID() . '&quot;, &quot;' . $var . '&quot;, &quot;nei&quot;);">Vis ' . $innhold . '</a>';
        }
        $res .= '<hr>';
    } 

    if($show or silhUserCanEdit()){
        $res .= $content;
    }

    if ( silhUserCanEdit() ) {
        $res .= '<hr>';
    }

    return $res;
}


/* Place custom code above this line. */
?>