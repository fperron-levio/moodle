/**
 * Created by nmoller on 14-06-25.
 */
/**
 * Ce fichier est traité automatiquement par Moodle.
 * Je dois juste ajouter $PAGE->requires->js_init_call('M.auth_rsg.showPopUp',null,true);
 * au template frontpage de cleanrsg pour que ce soit exécuté
 *
 */
M.auth_rsg={};

/**
 *
 * @param Y
 */

/**
 * Voir si l'on doit ajouter d'autres comportement au demarrage...
 * @param Y
 */

M.auth_rsg.showPopUp=function (Y){
    $("#mymodal").modal("show");
}