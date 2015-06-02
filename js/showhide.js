// 1

function sleep(millis, callback) {
    setTimeout(function()
            { callback(); }
    , millis);
}

function refresh_afterwords(){
	location.reload(true);
}


function toggle_showhide(page_id, var_name, default_value) 
{
     jQuery.ajax({
       url: toggle_showhide_script.ajaxurl,
       type: 'POST',
       data: ({'action' : 'toggle_showhide',
       	       'page_id' : page_id,
      	       'var_name' : var_name,
      	       'default_value' : default_value
             }),
       success: function() {
      	 return false;
       }
     });

    sleep(1000, refresh_afterwords);
}

