/**
 * Created by nmoller on 14-06-26.
 */

/**
 * Contenant pour javascript du module
 * @type {{}}
 */
M.mod_rsg={};

/**
 * Pour que le data soit disponible, on doit faire l'appel à cette fonction
 * dans chaque template nécessaire du thème cleanrsg
 *
 * @param Y YUI sandbox...
 * @param acl ce que l'on passe dans un array a moment de l'appel de la fonction
 */
M.mod_rsg.init=function(Y, acl, visits){
    var it= M.mod_rsg;
    // Liste des capsules auxquelles l'usager a accès.
    it.acl=acl;
    it.visits = visits;
}

