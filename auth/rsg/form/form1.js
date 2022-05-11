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
	// Section qui fait apparaître les BC selon la région sélectionnée
    var region = $('#rsgregion');
    var office = $('#rsgoffice');
    var currentRegion = region.val(); // Requis pour réaffichage formulaire

    bc_complet = M.form.data.officedata;

    function updateOfficeListFromRegion(value_region) {

        office.find('option').remove().end();

        $.each(bc_complet[value_region], function(regionid, value) {
            office.append($('<option>', {
				value: regionid,
				text: value
              }));
         });

        office.parent().parent().css("display", "inline-block");
        office.val('0');
    }

    if (currentRegion == '0' || currentRegion == null) {
        office.parent().parent().css("display", "none");
    } else {
        updateOfficeListFromRegion(currentRegion);
		
		// Met à jour le menu déroulant "Bureau coordonnateur"
		//--- tâche #3848
		var tempFormInfos = getCookie("tempFormInfos");
		if(tempFormInfos){
			// valide rsgregion
			var tempFormInfosObj = JSON.parse(tempFormInfos);
			if(tempFormInfosObj.rsgregion == currentRegion){
				if(tempFormInfosObj.rsgoffice && tempFormInfosObj.rsgoffice != '0'){
					office.val(tempFormInfosObj.rsgoffice += ''); //conversion en string
				}
			}
		}
		//tâche #3848 ---
    }

    region.on('change', function(e) {
        currentRegion = e.currentTarget.value;

        if (currentRegion == '0') {
			office.find('option').remove().end();
			office.parent().parent().css("display", "none");
        } else {
            updateOfficeListFromRegion(currentRegion);
        }
		
		//--- tâche #3848
		var tempFormInfos = '{"rsgregion":'+currentRegion+',"rsgoffice":0}';
		setCookie("tempFormInfos", tempFormInfos)
		//tâche #3848 ---
    });
	
	//--- tâche #3848
	office.on('change', function(e) {
        currentOffice = this.value;
		var tempFormInfos = '{"rsgregion":'+currentRegion+',"rsgoffice":'+currentOffice+'}';
		setCookie("tempFormInfos", tempFormInfos)
    });
	//tâche #3848 ---

    // Section qui fait apparaître ou non le champ numeroidentification
    var status = $('#rsgstatus');
    var numid = $('#id_numeroidentification');
    status.parent().parent().css("display", "inline-block"); // Tâche #3660
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

    // Éliminer les 0 qui précède du numéro d'identification.
    numid.on('change', function(e) {
        var currentNumid = e.currentTarget.value;
        if (currentNumid =='NaN') {
            e.currentTarget.value = '';
        } else {
            // Quand c'est un entier on ignore les zéros au début
            e.currentTarget.value = parseInt(e.currentTarget.value).toString();
        }
    });
	
	$('#auth_rsgsignupformpolicyagreed_link').on('click', function(e){
		e.preventDefault();
		window.open(this.getAttribute("href"),'_self');
	});
});