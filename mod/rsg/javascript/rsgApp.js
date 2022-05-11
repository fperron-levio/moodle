// Init via Moodle/YUI.
function init_rsgApp(Y) {
    
    // App module :
    var rsgApp = angular.module( "rsgApp", ['ngAnimate'])
        .constant('CATEGORY_LIMIT_SHOW_MORE', 4)
    ;

    // App level services :
    rsgApp.service('moodleStringService', function() {
        this.getString = function(id, extraParam) {
            return M.util.get_string(id, 'mod_rsg', extraParam);
        };
    });

    // App level controller :
   /* Todo: revalider les services vraiment requis au niveau "app" */
   rsgApp.controller("AppController",
        ['$scope', '$sce', 'filterFilter', 'moodleStringService', '$window',
            function( $scope, $sce, filterFilter, moodleStringService, $window){
                /*
                * Si l'on a besoin de un popup... il est enregistré dans le scope de l'app...
                * On pourrait passer le message du bouton et le href pour que ce soit reutilisable.
                * @param text
                * @returns {string}
                */
                $scope.mypopup= function(dialog_text, buttonInfo){
                    // 
                    var buttonText = "";
                    
                    // Ajustement minimal pour rendre le dialogue plus réutilisable (était dialogue avec un bouton).
                    if (buttonInfo != null) {
                       buttonText =  '<a class="green-button-rsg" role="button" href="' + buttonInfo.href + '" aria-hidden="true">' + buttonInfo.label + '</a>';
                    }
                    
                   return '<div id="modalnoacces" class="modal hide fade" tabindex="-1" role="dialog" static="" aria-labelledby="mymodalLabel" aria-hidden="false"> ' +
                        '<div class="modal-header">&nbsp;<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
                        ' </div>'+
                        '<div class="modal-body"><p>'+
                        dialog_text+
                        '</p>' + buttonText +
                        '</div></div>';
                }

               // Pour des considérations de sécurité, il faut traiter spécifiquement le data qui peuvent sortir en html via des tags {{ }} 
               $scope.to_trusted = function(html_code) {
                   return $sce.trustAsHtml(html_code);
               }
            }
    ]);
}
