/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* Voir js_init_call dans form. */
M.form.init_view_page = function(YUI, data) {
   M.form.data = data;
}

//--- tâche #3848
function setCookie(cname, cvalue) {
    document.cookie = cname + "=" + cvalue + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return ""; 
}
//tâche #3848 ---

// Pour empêcher les copier-coller des champs email et password
window.onload = function() {
    var email = document.getElementById('email');
    var email2 = document.getElementById('confirmemail');
    var password = document.getElementById('password');
    var confirmpassword = document.getElementById('confirmpassword');

    email.onpaste = function(e) {
        e.preventDefault();
    }
    confirmemail.onpaste = function(e) {
        e.preventDefault();
    }
    password.onpaste = function(e) {
        e.preventDefault();
    }
    confirmpassword.onpaste = function(e) {
        e.preventDefault();
    }
}

$(document).ready(function() {
    // Modifie la structure visuel du bureau coordonnateur
    var burean_coord_label = M.form.data.officelabel;
    var wrapper = $("<div class='control-group'></div>");
    var label = $("<label class='control-label'></label>");
    label.html("<span class='required'>*</span>" + burean_coord_label);
    var controls_wrapper = $("<div class='controls'></div>");
    var office_element = $('#rsg_region_office').next('select');
    controls_wrapper.append(office_element);
    wrapper.append(label, controls_wrapper);
    $('#rsg_region_office').closest('.control-group').after(wrapper);
    
    // Section qui fait apparaître ou non le champ numeroidentification
    var status = $('#rsgstatus');
    status.parent().parent().css("display", "inline-block"); // Tâche #3660
    var numid = $('#numeroidentification');
    var currentStatus = status.val(); // Requis pour réaffichage formulaire

    if(currentStatus != 1) {
        numid.parent().parent().css("display", "none");
    } else {
        numid.parent().parent().css("display", "inline-block");
    }

    status.on('change', function(e) {
        status = e.currentTarget.value;

        if (status != 1) {
            numid.parent().parent().css("display", "none");
        } else {
            numid.parent().parent().css("display", "inline-block");
        }
    });
	
	$('#auth_rsgsignupformpolicyagreed_link').on('click', function(e){
		e.preventDefault();
		window.open(this.getAttribute("href"),'_blank');
	});
});