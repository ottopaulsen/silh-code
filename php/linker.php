<?php
/**
 * Description: Kode for SIL Håndball. 
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */


/* Ide: Legg linker i en variabel på siden. Les denne inn i form.
   Kan form kalle php? Kortkode?


*/


function leggInnLink($side_id, $gruppe, $tekst, $url, $egetvindu){
    
    $linksTekst = get_post_meta($side_id, 'linker_' . $gruppe, true);

    if($linksTekst){
    	$linksArr = json_decode($linksTekst, true);
    }

    $linksArr[] = array('tekst'=>$tekst, 'url'=>$url, 'egetvindu'=>($egetvindu == 'Nytt vindu'));

    update_post_meta($side_id, 'linker_' . $gruppe, json_encode($linksArr, JSON_UNESCAPED_UNICODE));
}



function byggLinkerH($linksTekst, $em, $gruppe){
    // Horisontal linje med linker (minst mulig plass i bredden)

    if($linksTekst){
        $linksArr = json_decode($linksTekst, true);
    }
    
    $res = '';
    $link_nr = 0;
    foreach($linksArr as $linkItem){
        $res .= '<span class="linker_h_span" style="font-size:' . $em . 'em">';
        $res .= '<a class="linker linker_h"';
        $res .= ' href="' . $linkItem['url'] . '"' . ($linkItem['egetvindu'] ? ' target="_blank">' : '>');
        $res .=  $linkItem['tekst'];
        $res .= '</a>';
        if(silhUserCanEdit()){
            if($link_nr > 0) $res .= '<a align="right" href="#" onclick="move_link_up(' . get_the_ID() . ', \'' . $gruppe . '\', ' . $link_nr . ');">&#9664;</a>';
            $res .= '<a align="right" href="#" onclick="fjern_link(' . get_the_ID() . ', \'' . $gruppe . '\', ' . $link_nr . ');">&nbsp;X&nbsp;</a>';
            if($link_nr < count($linksArr) - 1) $res .= '<a align="right" href="#" onclick="move_link_down(' . get_the_ID() . ', \'' . $gruppe . '\', ' . $link_nr . ');">&#9654;';
            $res .= '&nbsp;</a>';
        }
        $res .= '</span>';
        $link_nr += 1;
    }


    return $res;
}   

function byggLinkerV($linksTekst, $em, $gruppe){

    if($linksTekst){
        $linksArr = json_decode($linksTekst, true);
    }
    
    $res = '';
    $link_nr = 0;
    foreach($linksArr as $linkItem){
        $res .= '<div class="linker linker_v"';
        $res .= ' onclick="window.open(\'' . $linkItem['url'] . '\'';
        $res .= ($linkItem['egetvindu'] ? ', \'_blank\');"' : ', \'_self\');"'); 
        $res .= ' style="font-size:' . $em . 'em">';
        $res .= '<table><tr>';
        $res .= '<td width="90%">';  
        $res .= '<a href="javascript:void(0)"> ' . $linkItem['tekst'] . '</a></td>';
        if(silhUserCanEdit()){
            $res .= '<td>';
            if($link_nr > 0) $res .= '<a align="right" href="javascript:void(0)" onclick="move_link_up(' . get_the_ID() . ', \'' . $gruppe . '\', ' . $link_nr . '); return false;">&#9650;</a>';
            if($link_nr < count($linksArr) - 1) $res .= '<a align="right" href="javascript:void(0)" onclick="move_link_down(' . get_the_ID() . ', \'' . trim($gruppe) . '\', ' . $link_nr . '); return false;">&#9660;</a>';
            $res .= '<a class="linker_v_a" align="right" href="javascript:void(0)" onclick="fjern_link(' . get_the_ID() . ', \'' . $gruppe . '\', ' . $link_nr . '); return false;">&nbsp;X&nbsp;</a>';
            $res .= '</td>';
        }
        $res .= '</tr></table>';
        $res .= '</div>';
        $link_nr += 1;
    }


    return $res;
}



add_shortcode( 'linker', 'linker_func' );
function linker_func($atts){
    $res = '';

    extract(shortcode_atts(array('gruppe' => '1'), $atts));
    extract(shortcode_atts(array('type' => 'v'), $atts));
    extract(shortcode_atts(array('em' => '1.35'), $atts));

    $linker = get_post_meta( get_the_ID(), 'linker_' . $gruppe, true );

    if (empty($linker)){
        if(silhUserCanEdit()) {
            $res .= '<a class="editlink" href="/legg-inn-link?side_id=' . get_the_ID() . '&gruppe=' . $gruppe . '">Legg inn link</a>';
        }
    } else {
        // Tekst funnet
        $res .= (strtolower($type) == 'h' ? byggLinkerH($linker, $em, $gruppe) : byggLinkerV($linker, $em, $gruppe));
        if(silhUserCanEdit()) {
            $res .= '<p align="right"><a class="editlink" href="/legg-inn-link?side_id=' . get_the_ID() . '&gruppe=' . $gruppe . '">Legg til link</a></p>';
        }
    }

    return $res;
}






function install_linker() 
{

     wp_enqueue_script( 'fjern_link_handle', plugins_url() . '/silh_code/js/linker.js', array('jquery'), null);
     wp_localize_script( 'fjern_link_handle', 'fjern_link_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

     wp_enqueue_script( 'move_link_up_handle', plugins_url() . '/silh_code/js/linker.js', array('jquery'), null);
     wp_localize_script( 'move_link_up_handle', 'move_link_up_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

     wp_enqueue_script( 'move_link_down_handle', plugins_url() . '/silh_code/js/linker.js', array('jquery'), null);
     wp_localize_script( 'move_link_down_handle', 'move_link_down_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}


add_action('template_redirect', 'install_linker');

add_action("wp_ajax_nopriv_fjern_link", "fjern_link");
add_action("wp_ajax_fjern_link", "fjern_link");


add_action("wp_ajax_nopriv_move_link_up", "move_link_up");
add_action("wp_ajax_move_link_up", "move_link_up");

add_action("wp_ajax_nopriv_move_link_down", "move_link_down");
add_action("wp_ajax_move_link_down", "move_link_down");

function fjern_link()
{
    $page_id = $_POST['page_id'];
    $gruppe = $_POST['gruppe'];
    $link_nr = $_POST['link_nr'];

    $linksTekst = get_post_meta( $page_id, 'linker_' . $gruppe, true );

    if (!empty($linksTekst)){
        if(silhUserCanEdit($page_id)) {
            $linksArr = json_decode($linksTekst, true);
            array_splice($linksArr, $link_nr, 1);
            update_post_meta($page_id, 'linker_' . $gruppe, json_encode($linksArr, JSON_UNESCAPED_UNICODE));
        }
    }


    refresh_afterwords();

    die();
}

function move_link_up(){
    move_link(-1);
}

function move_link_down(){
    move_link(1);
}

function move_link($direction){

    $page_id = $_POST['page_id'];
    $gruppe = $_POST['gruppe'];
    $link_nr = $_POST['link_nr'];

    $linksTekst = get_post_meta( $page_id, 'linker_' . $gruppe, true );

    if (!empty($linksTekst)){
        if(silhUserCanEdit($page_id)) {
            $linksArr = json_decode($linksTekst, true);
            if ($link_nr + $direction < count($linksArr) and $link_nr + $direction >= 0){
                $temp = $linksArr[$link_nr];
                $linksArr[$link_nr] = $linksArr[$link_nr + $direction];
                $linksArr[$link_nr + $direction] = $temp;
            }
            update_post_meta($page_id, 'linker_' . $gruppe, json_encode($linksArr, JSON_UNESCAPED_UNICODE));
        }
    }


    refresh_afterwords();

    die();
}


/* Place custom code above this line. */
?>