/**
 * Created by nmoller on 14-07-01.
 */

function test_review_quiz(Y,link,qte_pages){
    /* todo: Il reste des strings à transferer dans la table de string (ex. total). */
    var text='<div id="modaloutil" class="modal hide fade" tabindex="-1" role="dialog" static="" aria-labelledby="mymodalLabel" aria-hidden="false"> ' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div class="modal-header"><h4>' + M.util.get_string('rsg_quiz_dialog_feedback_exemple_answer_title','rsg') + '</h4>'+
        ' </div>'+
        '<div class="modal-body">'+ M.util.get_string('rsg_quiz_dialog_feedback_exemple_answer_message','rsg') + 
        '<p>&nbsp</p>' + '</div></div>';

    $('#page-mod-quiz-review').append(text);
    $('span.arrow').append('Total : '+qte_pages);

    $("#modaloutil").modal("show");
}

function test_quiz_totalpages(Y,qte_pages){
    //on prend pour aquis qu'il y a
    $('span.qno').append('/'+qte_pages);
}


function test_quiz_modal_quitter(Y, wwwroot,page,attemptid){
    var cat_link= wwwroot+'/mod/rsg/catalogue';
    var end_link=wwwroot+'/mod/quiz/summary.php?attempt='+attemptid;
    var text='<div id="modaloutil" class="modal hide fade" tabindex="-1" role="dialog" static="" aria-labelledby="mymodalLabel" aria-hidden="false"> ' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><div class="modal-header"><h4>' + M.util.get_string('rsg_quiz_dialog_quit_title','rsg') + '</h4>'+
        ' </div>'+
        '<div class="modal-body"><p>'+
        M.util.get_string('rsg_quiz_dialog_quit_message','rsg') +
        '</p> '+'<a class="rsg_quiz_quit_button btn btn-info btn-lg "  href="'+cat_link+'" id="rsg_quiz_save">' + M.util.get_string('rsg_quiz_dialog_quit_button_keep','rsg') + '</a>'+
        '<a class="endtestlink btn btn-info btn-lg" href="'+end_link+'">' + M.util.get_string('rsg_quiz_dialog_quit_message_finish','rsg') + '</a>'+
    /* '<input type="submit" class="close btn btn-info btn-lg" data-dismiss="modal" aria-hidden="true" value="' + M.util.get_string('rsg_quiz_dialog_quit_message_cancel','rsg') + '">'+ */
        '</div></div>';

    /* custommenuitem brise encapsulation mais on doit le faire obtenir le comportement voulu pour rsg */
    var multiselect_quit_confirm = Y.all('#quiz_rsg_close_modal,.custommenuitem, .logo-rsg');
    multiselect_quit_confirm.on('click',
        function(e) {
            
            e.preventDefault();

            $('#page-content').append(text);
            var my_modal= Y.one('#modaloutil');
            var form = Y.one('#responseform');
            $("#modaloutil").modal("show");
            //On ajoute les comportement click aux buttons.
            //Ça aurait pu être fait dans la fonction nav_to_page et en modifiant le traitement du paramètre thispage(#followingpage)
            //dans processattemp.php.
            Y.on('click', function(e) {
                e.preventDefault();
                nav_to_page(-1);

            }, 'a.endtestlink');
            //on ajoute le paramètre que modifie le traitement de la soumission
            Y.on('click', function(e) {
                e.preventDefault();
                form.append('<input type="hidden" name="redirect" value="1">');
                nav_to_page(-1);

            }, 'a.rsg_quiz_quit_button');

            if (form) {
                function find_enabled_submit() {
                    // This is rather inelegant, but the CSS3 selector
                    //     return form.one('input[type=submit]:enabled');
                    // does not work in IE7, 8 or 9 for me.
                    var enabledsubmit = null;
                    form.all('input[type=submit]').each(function(submit) {
                        if (!enabledsubmit && !submit.get('disabled')) {
                            enabledsubmit = submit;
                        }
                    });
                    return enabledsubmit;
                }

                function nav_to_page(pageno) {
                    Y.one('#followingpage').set('value', pageno);

                    // Automatically submit the form. We do it this strange way because just
                    // calling form.submit() does not run the form's submit event handlers.
                    var submit = find_enabled_submit();
                    submit.set('name', '');
                    submit.getDOMNode().click();
                };
            }
            Y.delegate(
                'click',
                function(e){
                    e.preventDefault();
                    //alert('salut');
                    // Automatically submit the form. We do it this strange way because just
                    // calling form.submit() does not run the form's submit event handlers.
                    var submit = find_enabled_submit();
                    submit.set('name', '');
                    submit.getDOMNode().click();
                },
                document.body,
                '#rsg_quiz_save'
            );

        });
}
