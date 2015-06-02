    <?php
/**
 * Description: Kode for SIL Håndball. 
 * Author: Otto Paulsen
 * 
 * Version: 0.1.0
 */

/* Place custom code below this line. */

add_filter('frm_redirect_url', 'return_page', 10, 2);
function return_page($url, $form, $params){
  if(!isset($params['action'])){
    $params['action'] = FrmAppHelper::get_param('frm_action');
  }

  if($form->id == 23 and ($params['action'] == 'create' or $params['action'] == 'update')){ 
    if(isset($_POST['item_meta'][196])){
        $side_id = $_POST['item_meta'][196];
        $url = get_page_link($side_id);
    }
  } elseif ($form->id == 18 and ($params['action'] == 'create' or $params['action'] == 'update')){ 
    if(isset($_POST['item_meta'][173])){
        $side_id = $_POST['item_meta'][173];
        $url = get_page_link($side_id);
    }
  } elseif ($form->id == 19 and ($params['action'] == 'create' or $params['action'] == 'update')){ 
    if(isset($_POST['item_meta'][177])){
        $side_id = $_POST['item_meta'][177];
        $url = get_page_link($side_id);
    }
  } elseif ($form->id == 22 and ($params['action'] == 'create' or $params['action'] == 'update')){ 
    if(isset($_POST['item_meta'][191])){
        $side_id = $_POST['item_meta'][191];
        $url = get_page_link($side_id);
    } 
  }
  return $url;
}


/*
    Hook for innsending av Formidable Forms.
        Form id 18 - Google Kalender-ID brukes for å legge inn Google Kalender-ID på siden. Verdien lagres i variabel.
        Form id 19 - Google Skjema-ID brukes for å legge inn Google Skjema-ID på siden. Verdien lagres i variabel.
        Form id 22 - Tekstfelt. Input for brukervennlig tekstfelt. Verdien lagres i variabel.
        Form id 23 - Linker. Input for link-felt.
*/
add_action('frm_after_create_entry', 'formidableHookSave', 10, 2);
function formidableHookSave($entry_id, $form_id){
    if($form_id == 18){ 
        if(isset($_POST['item_meta'][171])) 
            $kalender_var = $_POST['item_meta'][171];
        if(isset($_POST['item_meta'][169]))
            $kalender_id = $_POST['item_meta'][169];
        if(isset($_POST['item_meta'][173]))
            $side_id = $_POST['item_meta'][173];
        update_post_meta($side_id, $kalender_var, $kalender_id);
        wp_redirect( get_page_link($side_id));
    } elseif ($form_id == 19) {
        if(isset($_POST['item_meta'][175])) 
            $skjema_var = $_POST['item_meta'][175];
        if(isset($_POST['item_meta'][174]))
            $skjema_id = $_POST['item_meta'][174];
        if(isset($_POST['item_meta'][179])) 
            $skjemasvar_var = $_POST['item_meta'][179];
        if(isset($_POST['item_meta'][178]))
            $svar_url = $_POST['item_meta'][178];
        if(isset($_POST['item_meta'][177]))
            $side_id = $_POST['item_meta'][177];
        update_post_meta($side_id, $skjema_var, $skjema_id);
        update_post_meta($side_id, $skjemasvar_var, $svar_url);
        wp_redirect( get_page_link($side_id));
    } elseif ($form_id == 22) {
        if(isset($_POST['item_meta'][188])) 
            $tekst = $_POST['item_meta'][188];
        if(isset($_POST['item_meta'][190])) 
            $tekstnavn = 'tekst_' . $_POST['item_meta'][190];
        if(isset($_POST['item_meta'][191]))
            $side_id = $_POST['item_meta'][191];
        update_post_meta($side_id, $tekstnavn, wpautop($tekst, true));
        wp_redirect( get_page_link($side_id));
    } elseif ($form_id == 23) {
        if(isset($_POST['item_meta'][193])) 
            $tekst = $_POST['item_meta'][193];
        if(isset($_POST['item_meta'][194])) 
            $url = $_POST['item_meta'][194];
        if(isset($_POST['item_meta'][195])) 
            $egetvindu = $_POST['item_meta'][195];
        if(isset($_POST['item_meta'][196]))
            $side_id = $_POST['item_meta'][196];
        if(isset($_POST['item_meta'][199]))
            $gruppe = $_POST['item_meta'][199];
        leggInnLink($side_id, $gruppe, $tekst, $url, $egetvindu);
        //wp_redirect( get_page_link($side_id), 201);
    }
}


/* Place custom code above this line. */
?>