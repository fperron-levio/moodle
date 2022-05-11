// Mécanisme pour le passage de données backend-frontend.
// Se fait typiquement via modules.
// Voir http://docs.moodle.org/dev/How_to_create_a_YUI_3_module.
//      http://docs.moodle.org/dev/JavaScript_usage_guide
//
YUI.add('moodle-mod_rsg-catalogue', function(Y) {
    console.log("moodle-mod_rsg-catalogue:add");
    var ModulenameNAME = 'catalogue';
    var MODULENAME = function() {
        MODULENAME.superclass.constructor.apply(this, arguments);
    };
    Y.extend(MODULENAME, Y.Base, {
        initializer : function(config) { // 'config' contains the parameter values
             console.log('moodle-mod_rsg-catalogue:initializer');
            // console.log(config);
        }
    }, {
        NAME : ModulenameNAME, //module name is something mandatory. 
                                // It should be in lower case without space 
                                // as YUI use it for name space sometimes.
        ATTRS : {
                 aparam : {}
        } // Attributes are the parameters sent when the $PAGE->requires->yui_module calls the module. 
          // Here you can declare default values or run functions on the parameter. 
          // The param names must be the same as the ones declared 
          // in the $PAGE->requires->yui_module call.
    });
    M.mod_rsg = M.mod_rsg || {}; // This line use existing name path if it exists, otherwise create a new one. 
                                // This is to avoid to overwrite previously loaded module with same name.
                                                 
    M.mod_rsg.init_catalogue = function(config) { // 'config' contains the parameter values
        M.mod_rsg.config = config;

        // Test initialisation des catégories. 
        return new MODULENAME(config); // 'config' contains the parameter values
    };
    
    M.mod_rsg.someotherfunction = function(opt) {
         console.log("moodle-mod_rsg-catalogue:Some other function called.");
        // console.log(opt);
    };
    
  }, '@VERSION@', {
      requires:['base']
  });