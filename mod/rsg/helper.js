/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){	//Click the button event!
    var ouvert = '';
    var chtexte = '';
    $("#c1, #c2, #c3").animate({height :'460px'}, 300, 'linear');		//force les onglets à être fermés à l\'ouverture de la page
    $("#b1, #b2, #b3").animate({height : '150px'}, 300, 'linear');		//force les onglets à être fermés à l\'ouverture de la page

    $("#plus1, #plus2, #plus3").click(function(){
        switch ($(this).attr("id"))
        {
                case "plus1" :chtexte = 1; break;
                case "plus2" :chtexte = 2; break;
                case "plus3" :chtexte = 3; break;
        }
        if (ouvert != '')	//si un des onglets est ouvert on le ferme
        {

            $("#b" + ouvert).animate({height : '140px'}, 300, 'linear');
            $("#b" + ouvert).css('overflow', 'hidden');
            $("#c" + ouvert).animate({height : '460px'}, 300, 'linear');
            $("#plus" + ouvert).html('En afficher plus');
            $("#plus" + chtexte).css('background', 'url($CFG - > wwwroot.self::$rootThImgarr_down) no-repeat center bottom');
        }
        if (ouvert == chtexte)
        {
            ouvert = '';
        }
        else
        {
            $("#b" + chtexte).animate({height : '340px'}, 300, 'linear');
            $("#b" + chtexte).css('overflow', 'visible');
            $("#c" + chtexte).animate({height : '900px'}, 300, 'linear');
            $("#plus" + chtexte).html('Afficher moins');
            $("#plus" + chtexte).css('background', 'url($CFG - > wwwroot.self::$rootThImg.arr_top) no-repeat center bottom');
            ouvert = chtexte;
        }
    });
});
