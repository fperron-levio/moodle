
function init_rsgCatalog(Y, data) {
    // See rsgApp.js for rsgApp init.
    // Get main module / app reference:
    var rsgApp = angular.module('rsgApp');

    // Minimal error:
    if (rsgApp == null) {
        console.log("Error : null rsgApp reference in init_rsgCatalog.")
        return;
    }

    // Catalog Services :
    rsgApp.service('rsgCatalogService', function() {
        
        this.getCapsuleData = function() {
            return data.capsuleData;
        };
        
        this.getCapsuleDataType = function() {
            return data.capsuleDataType;
        };
       
        this.getCapsuleTemplateName = function() {
            return data.capsuleTemplateName;
        }
       
        this.getInfoCategories = function() {
            return data.infoCategories;
        }

        this.getNoResultMsg = function() {
            return data.noResultsMsg;
        }
    });


    rsgApp.service('linkAvailabilityService', ['moodleStringService', function(moodleStringService) {
        
        // todo : revoir nom des fonctions...
        this.isAvailableFromSubscription = function(idCapsule) {
            // This is a "hard" restriction. todo: url can be known but should not be accessible.
            var available = true; // default.
        
            if (typeof M.mod_rsg.acl !='undefined'){ //Pas d'access control list, accès non bloqué.    
                var acl_data = M.mod_rsg.acl;
                if (acl_data != null) {
                    var hasAccess  = $.inArray(idCapsule, acl_data)!=-1;
                    if (!hasAccess)  {
                        available = false;
                    }
                }
            }

           return available;
        };
        
        this.autoevaluationAvailableFromCapsuleVisits = function(idCapsule) {
           // This is a "soft" restriction only.
           var available = true; // default.
            
           if (typeof M.mod_rsg.visits !='undefined'){
                var visits_data = M.mod_rsg.visits;
                if (visits_data != null) {
                    var hasAccess  = visits_data[idCapsule] != undefined;
                    if (!hasAccess) {
                        available = false;
                    }
                }
            }
            
            return available;
        };
        
        this.displayAutoevaluationNeedCapsuleVisitsError = function() {
            var controllerElement = document.querySelector('body');
            var controllerScope = angular.element(controllerElement).scope(); 
            // Texte à externaliser.
            var dialog_text = moodleStringService.getString('rsg_autoevaluation_not_available');
            
            var dialog_html = controllerScope.mypopup(dialog_text, null);
            $('#page-footer').append(dialog_html);
            $("#modalnoacces").modal("show");
            
        };
        
        this.displayCapsuleAndAutoevaluationNotAvailableError = function() {
            // Todo: Move this...
            // See http://stackoverflow.com/questions/22570357/angularjs-access-controller-scope-from-outside
            var controllerElement = document.querySelector('body');
            var controllerScope = angular.element(controllerElement).scope();            
            var dialog_text = moodleStringService.getString('popup_noaccess');
            
            var buttonInfo = {};
            buttonInfo.href = M.cfg.wwwroot;
            buttonInfo.label = moodleStringService.getString('error404_dialog_button_return_home');
            
            var dialog_html = controllerScope.mypopup(dialog_text, buttonInfo);
            $('#page-footer').append(dialog_html);
            $("#modalnoacces").modal("show");
        };
        
        this.updateCapsuleVisits = function(idCapsule) {
            // Update the local visit data.
            if (typeof M.mod_rsg.visits !='undefined'){ //Pas d'access control list, accès non bloqué.
                var visits_data = M.mod_rsg.visits;
                if (visits_data != null) {
                    var numVisits = visits_data[idCapsule];
                    if (numVisits == undefined) {
                        visits_data[idCapsule] = 1;
                    } else {
                        visits_data[idCapsule] = numVisits +1
                    }
                }
            }
        };
        
    }]);

    // Catalog / list / capsule Directives 
    // 
    // Lien vers capsule (avec restriction d'accès possible).
    rsgApp.directive('rsgLinkCapsule',['moodleStringService', 'linkAvailabilityService', function(moodleStringService, linkAvailabilityService){
        return function(scope, element, attrs) {
            $(element).click(function(event) {
                // Fix bug. Opening a capsule is done through a new window.
                // The visit data is not refreshed and there is no ajax call to retrieve the data again.
                if (!linkAvailabilityService.isAvailableFromSubscription(scope.capsule.id)) {
                    event.preventDefault();
                    linkAvailabilityService.displayCapsuleAndAutoevaluationNotAvailableError();
                } else {
                    linkAvailabilityService.updateCapsuleVisits(scope.capsule.id);
                }
            });
        }
    }]);

    // Lien vers autoévaluation (avec restriction d'accès possible).
    rsgApp.directive('rsgLinkAutoevaluation',['moodleStringService', 'linkAvailabilityService', function(moodleStringService, linkAvailabilityService){
        return function(scope, element, attrs) {
            $(element).click(function(event) {
                if (!linkAvailabilityService.isAvailableFromSubscription(scope.capsule.id)) { 
                    event.preventDefault();
                    linkAvailabilityService.displayCapsuleAndAutoevaluationNotAvailableError();
                } else {
                    if (!linkAvailabilityService.autoevaluationAvailableFromCapsuleVisits(scope.capsule.id)) {
                        event.preventDefault();
                        linkAvailabilityService.displayAutoevaluationNeedCapsuleVisitsError();
                    }
                }
            });
        }
    }]);

    // Ajout de la fonctionalité de tooltip (jquery).
    // Recherche les tags ayant la classe tooltipCapsule.
    // Le comportement voulu (ex. placement est défini à un seul endroit).
    // Attributs requis: 'class' => 'tooltipCapsule', 'data-toggle' => 'tooltip', 'title' => '*** Message visible dans le tooltip ***'
    rsgApp.directive('tooltipCapsuleName',['moodleStringService',function(moodleStringService){
       return {
         restrict: 'C', /* class name only */
         link: function($scope, element, attrs) {
             $(element).tooltip({
                'placement':'bottom',
                'trigger':'click',
                'html':true,
                'title': function() {
                    /* todo: Devrait idéalement appeler sous-template pour ne pas construire le html dans le code
                    (et gérer l'affichage conditionnel). */
                    /* Un peu plus complexe puisque affichage différé avec jquery */
                    var tooltip = "";
                    
                    var description = $scope.capsule.description; /* Pourrait avoir été oublié à la création du cours. */
                    var outil = $scope.capsule.outil;  /* Pourrait avoir été oublié */
                    var category = $scope.getCategoryName($scope.capsule.category); /* devrait toujours être présent. */
                    
                    if (description!= "" && description !=undefined) {
                       /* tooltip += '<p><div class="capsule_tooltip_title">' + moodleStringService.getString('description') + '</div> '; */  /* #4534 */
					    tooltip += '<p><div class="capsule_tooltip_title" style="position ">' + moodleStringService.getString('description') + ' <span class="close2" style="color:black;font-size: 193%;position: absolute;top: -92px;right: 6px;background-color: white;padding-left: 3px;padding-right: 3px;padding-top: -3px;">&times;</span></div> ';
                        tooltip += '<div' +  description + '</div></p>';
                    }
                    
                    if (outil!= "" && outil !=undefined) {
                        tooltip += '<p><div class="capsule_tooltip_title">' + moodleStringService.getString('bonus_tool') + '</div>';
                        tooltip += '<div>' + moodleStringService.getString('bonus_tool_tooltip', outil) + '</div></p>';
                    }
                    
                    if (category!= "" && category !=undefined) {
                        tooltip += '<p><div class="capsule_tooltip_title">' + moodleStringService.getString('subject') + '</div>'; 
                        tooltip += '<div>' + moodleStringService.getString('subject_tooltip', category) + '</div></p>';
                    }
                    return tooltip;
                }
             });
          }
       };
    }]);

    rsgApp.directive('tooltipCapsuleTime',['moodleStringService',function(moodleStringService){
       return {
         restrict: 'C', /* class name only */
         link: function($scope, element, attrs) {
             $(element).tooltip({
                'placement':'bottom',
                'trigger':'click',
                'html':true,
                'title': function() {
                    /* todo: Devrait idéalement appeler sous-template pour ne pas construire le html dans le code (et gérer l'affichage conditionnel). */
                    /* Un peu plus complexe puisque affichage différé avec jquery */
                    /* Comportement: affiche le champs seulement si l'information est complète. */
                    /* Une durée de 0 minutes ou 0 heures ne va pas s'afficher. */
                    var tooltip = "";
                    var duration_capsule_text = $scope.capsule.duration_capsule_text;
                    var duration_autoevaluation_text = $scope.capsule.duration_autoevaluation_text
                    
                    if ( duration_capsule_text != "") {
                        // Devrait mettre capsule dans <div class="capsule_tooltip_title">?
                        tooltip += "<div>" +  moodleStringService.getString('capsule') + ' : ' + duration_capsule_text + "</div>";
                    }
                    if (duration_autoevaluation_text != "") {
                        // Devrait mettre autoevaluation dans <div class="capsule_tooltip_title">?
                        tooltip += "<div>" + moodleStringService.getString('autoevaluation') + ' : ' + duration_autoevaluation_text + "</div>";
                    }
                    return  tooltip;
                }
             });
          }
       };
    }]);

    // Controlleur de l'application.
    rsgApp.controller("CatalogController",
        ['$scope', '$sce','rsgCatalogService', 'filterFilter', 'moodleStringService', 'CATEGORY_LIMIT_SHOW_MORE', '$window', '$element',
            function( $scope, $sce, rsgCatalogService, filterFilter, moodleStringService, CATEGORY_LIMIT_SHOW_MORE, $window, $element){
                
                var d = new Date();
                var n = d.getTime();
                // todo: Pas idéal, le path complet devrait provenir du backend.
                $scope.statique_path_template= $window.M.cfg.wwwroot + '/mod/rsg/statique/'+ rsgCatalogService.getCapsuleTemplateName() +'.tpl.html'+"?skipcache="+n;
                
                $scope.CATEGORY_LIMIT_SHOW_MORE = CATEGORY_LIMIT_SHOW_MORE; /* voir mécanique d'injection de constantes d'angular */

                $scope.categoryLimitActive = {};

                $scope.setCategoryLimitActive = function() {
                    // Pourrait passer id ou "all".
                    for (var i=0; i < $scope.infoCategories.length; i++) {
                        // Important: Id cast to string.
                        $scope.categoryLimitActive[(($scope.infoCategories[i].id)).toString()] = true;
                    }
                }

                $scope.toggleCategoryLimit = function(categoryId) {
                   /* toggle */
                   $scope.categoryLimitActive[categoryId] = !$scope.categoryLimitActive[categoryId];
                };

                $scope.getCategoryLimit = function(categoryId) {
                    if ($scope.getCategoryLimitActive(categoryId)) {
                        return $scope.CATEGORY_LIMIT_SHOW_MORE;
                    } else {
                        return $scope.capsules.length; /* todo: bug max-int ne fonctionne pas, bug? Retester? */
                    }
                }

                $scope.getCategoryLimitActive = function(categoryId) {
                    return $scope.categoryLimitActive[categoryId];
                }

                $scope.my_search=function(input_search_query) {
                     /* Angular multiple field and $ are not working correctly (behavior and bug) */
                    $scope.search = function (item) {
                        // Voir aussi plus tard https://github.com/ikr/normalize-for-search (pour normaliser, ex. tete devrait trouver ête, ete, été).
                        var searchtext = $scope.search_query;
                        var sourcetext = '';
                        var found = false;
                        var search_fields =  ['keywords','description','name']; /* could be a config elsewhere in the app. Keep it here for now. */
                        var arrayLength =  search_fields.length;
                        for (var i = 0; i < arrayLength; i++) {
                            source = item[search_fields[i]];
                            if (source != null) {
                                if (source.toLowerCase().indexOf(searchtext) > -1) {
                                    found = true; /* We only one true out of multiple field (OR CONDITION). This is equivalent of searching in a concanated version of these fields. */
                                    break;
                                }
                            }
                        }
                        return found;
                    };
                    
                    if (input_search_query != $scope.search_query) {
                        $scope.search_query =  angular.lowercase(input_search_query); /* mettre en lowercase une fois */
                        $scope.search_change();
                    }
                };
                
                $scope.closeTabletKeyboard = function(Event) {
                    if (Event.which === 13 || Event.which === 1){
                        var jQueryInnerItem = $($element);
                        jQueryInnerItem.find('#search_capsule').blur();
                    }
                }
                
                $scope.search_change = function() {
                    $scope.setCategoryLimitActive(); /* reset to default (true) */

                    // Mise à jour de liste pré-filtrée.
                    if ($scope.search_query == '') {
                        /* pas de critère = pas de filtre. Si on laisse le critère ""
                         * passer dans le filtre rien n'est affiché. */
                        $scope.search_reset();
                        /* setInfoCategory_numCapsules_SortField = Cas particulier: sort par nombre d'items par categorie, default = toutes les capsules. */
                        $scope.setInfoCategory_numCapsules_SortField($scope.infoCategories, $scope.capsules);
                    } else {
                        $scope.capsules_searchFiltered = filterFilter($scope.capsules, $scope.search)
                        /* setInfoCategory_numCapsules_SortField = Cas particulier: sort par nombre d'items par categorie, default = capsules trouvées par la recherche. */
                        $scope.setInfoCategory_numCapsules_SortField($scope.infoCategories, $scope.capsules_searchFiltered);
                    }
                };

                $scope.search_reset = function() {
                    $scope.search_query = '';
                    $scope.capsules_searchFiltered = $scope.capsules;
                }
                
                $scope.isFrontpage=rsgCatalogService.getCapsuleDataType()=='frontpage';

                /* Helper, devrait pas être requis? */
                $scope.getString = function(id, extraParam){
                    return moodleStringService.getString(id, extraParam);
                 }

                $scope.getCategoryColor = function(categoryId){
                    return $scope.$$categoriesColor[categoryId];
                }

                $scope.getCategoryName = function(categoryId){
                    return $scope.$$categoriesName[categoryId];
                }

                /* Bug angular, pas possible d'évaluer cette condition dans le markup? */
                $scope.canShowCategoryEmptyEmptyMsg = function(capsules_groupFiltered) {
                    if (capsules_groupFiltered != undefined) {
                        if (capsules_groupFiltered.length == 0 && $scope.search_query == '') {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
                
                $scope.canShowCategory = function(capsules_groupFiltered) {
                    if (capsules_groupFiltered != undefined) {
                        if ((capsules_groupFiltered.length == 0 && $scope.search_query =='') || (capsules_groupFiltered.length > 0)) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }    
                }
                
                $scope.setInfoCategory_numCapsules_SortField = function(infoCategories, capsules) {
                    /* Demande RSG. Trier les catégories par nombre de capsules dans la catégorie! */
                    /* Trop de risque de régression avec modification du template, utilise un pré-calcul. */
                    // Utilise ensuite: | orderBy:'numCapsules':'reverse' dans le html (les single quotes sont importants).
                    for (var i = 0; i < infoCategories.length; i++) {
                        var category = infoCategories[i];
                        capsules_categoryFiltered =filterFilter(capsules, {category:category.id});
                        infoCategories[i].numCapsules = capsules_categoryFiltered.length;
                    }
                }

                // Récupération du data backend:
                $scope.infoCategories = rsgCatalogService.getInfoCategories();                
                $scope.capsules = rsgCatalogService.getCapsuleData();
                
                // Ajout: ne provient pas de la BD.
                $scope.setInfoCategory_numCapsules_SortField($scope.infoCategories, $scope.capsules);

                $scope.noResultMsg = rsgCatalogService.getNoResultMsg();
                
                // Initialisations:
                $scope.setCategoryLimitActive();

                /* Dans l'écran d'accueil, on affiche les capsules sans itérer sur les catégories. */
                /* On prépare ces variable pour minimiser overhead lors du digest. */
                /* Pourrait faire un merge de l'info avec record de capsule? */
                $scope.$$categoriesColor = {};
                $scope.$$categoriesName = {};

                for (var i in $scope.infoCategories) {
                    var category = $scope.infoCategories[i];
                     $scope.$$categoriesColor[(category.id).toString()] = category.color;
                     $scope.$$categoriesName[(category.id).toString()] = category.name;
                }

                // Première recherche.
                $scope.search_reset();
            }
    ]);
}