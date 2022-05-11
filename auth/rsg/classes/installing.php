<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of installing
 *
 * @author Nelson Moller <nmoller at cegepadistance.ca>
 */
define('CLI_SCRIPT', true);
require_once '../../../config.php';
class installing {
    //put your code here
     static $bc =array( 
        1 => ["","BC CPE \"LES CALINOURS\"", "BC CPE DE RIVIÈRE-DU-LOUP INC.", "BC CPE LA BALEINE BRICOLEUSE", "BC CPE L'AURORE BORÉALE", "BC CPE LES PINSONS INC.", "BC LE CPE DE MATANE", "BC LES SERVICES DE GARDE LA FARANDOLE", "BC SERVICES DE GARDE L'ENFANT JOUE"],
        2 => ["","BC CPE CROQUE LA VIE", "BC CPE LA BAMBINERIE", "BC CPE LES AMIS DE LA CULBUTE", "BC CPE MINI-MONDE", "BC LE CPE AUETISSATSH", "BCGMF DE L'ARRONDISSEMENT DE JONQUIÈRE", "BCGMF DU GRAND CHICOUTIMI"],
        3 => ["","ALLIANCE BCGMF DE BEAUPORT", "BC CPE DU SOLEIL À LA LUNE", "BC CPE JOLI-COEUR INC.", "BC CPE LA GRIMACE", "BC CPE LE KANGOUROU", "BC CPE LE PETIT BALUCHON (1981) INC.", "BC CPE L'ENCHANTÉ", "BC CPE LES PETITS MULOTS", "BC CPE L'ESSENTIEL", "BC CPE PIGNONS SUR RUE", "BC CPE QUÉBEC-CENTRE INC.", "BC DE LA HAUTE-ST-CHARLES", "BC PITCHOUNETTE GARDE EN MILIEU FAMILIAL INC.", "BCGMF DES HAUTES MARÉES"],
        4 => ["","BC CPE LE MANÈGE DES TOUT-PETITS INC.", "BC CPE FLOCONS DE RÊVE INC.", "BC CPE LA CLÉ DES CHAMPS INC.", "BC CPE LE CERF-VOLANT INC.", "BC CPE LES SOLEILS DE MÉKINAC", "BC LES PETITS COLLÉGIENS", "BC LES SERVICES DE GARDE GRIBOUILLIS"],
        5 => ["","BC  LE CPE FLEURIMONT INC.", "BC CPE CARROSSE-CITROUILLE INC.", "BC CPE FAMILI-GARD'ESTRIE", "BC CPE LA SOURCIÈRE", "BC CPE L'ENFANT-DO DE MEMPHRÉMAGOG", "BC CPE L'ENFANTILLAGE INC.", "BC CPE MAGIMO", "BC CPE SOUS LES ÉTOILES", "BC DU HAUT SAINT-FRANCOIS"],
        6 => ["","BC GARDE MILIEU FAMILIAL DE BORDEAUX-CARTIERVILLE", "BC - BC DE LA GARDE EN MF DE SAINT-LÉONARD", "BC - CPE \"LA GRENOUILLE ROSE\"", "BC - CPE DU CARREFOUR INC.", "BC - CPE DU MONTRÉAL MÉTROPOLITAIN", "BC - CPE ENFANTS SOLEIL INC.", "BC - CPE FAMILIGARDE DE LASALLE", "BC - CPE LE JARDIN DES RÊVES INC.", "BC CAVENDISH", "BC AHUNTSIC", "BC LA MAISON DU PANDA", "BC ST-MICHEL", "BC-CPE DE MONTRÉAL-NORD", "CPE DU PARC", "CPE GROS BEC", "CPE JARDIN DE FRUITS INC.", "CPE LA TROTTINETTE CAROTTÉE", "CPE LES MAISONS ENJOUÉES", "LES SERVICES DE GARDE DE LA POINTE INC. BC - 6-01"],
        7 => ["","BC -  CPE LA GATINERIE", "BC - CPE 1-2-3 PICABOU", "BC - CPE DE LA PETITE-NATION", "BC - CPE DES PREMIERS PAS", "BC - CPE LA RIBAMBELLE D'AYLMER", "BC - CPE LES FEUX FOLLETS", "BC - CPE L'ÉVEIL DE LA NATURE", "BC - CPE TROIS PETITS POINTS ...", "BC - RÉSEAU PETITS PAS"],
        8 => ["","BC - CPE ABINODJIC-MIGUAM", "BC - CPE BONNAVENTURE", "BC - CPE CHEZ CALIMÉRO", "BC - CPE DES PETITS ÉLANS", "BC - CPE LES PETITS CHATONS INC.", "BC - CPE VALLÉE DES LOUPIOTS"],
        9 => ["","BC CPE \"LE MUR-MÛR\" INC.", "BC CPE LA GIROFLÉE INC.", "BC CPE MAGIMUSE", "BC CPE MER ET MOUSSE", "BC CPE PICASSOU INC.", "BC CPE SOUS LE BON TOIT"],
        10=> ["","BC - CPE SUCRE D'ORGE", "BC - LE CPE DES P'TITS MARINGOUINS", "BC - PAIRITSIVIK OF NUNAVIK HOME DAY CARE AGENCY", "BC CHIBOUGAMAU-CHAPAIS 10-01"],
        11=> ["","BC AUX JOYEUX MARMOTS", "BC CPE \"CHEZ MA TANTE\"", "BC CPE \"LA BELLE JOURNÉE\" INC.", "BC CPE DE LA BAIE", "BC CPE LA MARÉE MONTANTE", "BC CPE LE VOYAGE DE MON ENFANCE"],
        12=> ["","BC CPE À LA BONNE GARDE", "BC CPE AU JARDIN DE DOMINIQUE INC.", "BC CPE AU PALAIS DES MERVEILLES", "BC CPE DES PETITS POMMIERS", "BC CPE LE PETIT TRAIN INC.", "BC CPE LES COQUINS", "BC CPE L'ESCALE INC.", "BC CPE PETIT TAMBOUR", "BC CPE VIRE-CRÊPE", "BC DE LA MRC DE MONTMAGNY", "BCGMF DES APPALACHES", "BCGMF RAYONS DE SOLEIL"],
        13=> ["","BC - \"FORCE VIVE\" CPE", "BC - CPE GAMINVILLE INC.", "BC - CPE LE CHEZ-MOI DES PETITS", "BC - CPE LE HÊTRE INC.", "BC - CPE LES P'TITS SOLEILS DE STE-DOROTHÉE", "BC - CPE PIROUETTE DE FABREVILLE INC."],
        14=> ["","BC - C.P.E. GAMIN GAMINE", "BC - C.P.E. LES JOLIS MINOIS", "BC - CPE \"LE CHAT PERCHÉ\"", "BC - CPE AUX PORTES DU MATIN INC.", "BC - CPE BALIBALLON", "BC - CPE BOUTE-EN-TRAIN", "BC - CPE LA CHENILLE INC.", "BC - CPE LES JOYEUX LUTINS", "BC - STATION ENFANCE DES MOULINS"],
        15=> ["","BC - CPE \"LES PETITS BALUCHONS\"", "BC - CPE \"LES MILLE-PATTES\"", "BC - CPE DES DEUX-MONTAGNES", "BC - CPE LA FOURMILIÈRE", "BC - CPE LA JOYEUSE ÉQUIPÉE", "BC - CPE LA ROSE DES VENTS", "BC - CPE L'ANTRE-TEMPS", "BC - CPE MAIN DANS LA MAIN INC.", "BC - CPE SOLEIL LEVANT", "BC - LE RÊVE DE CAILLETTE"],
        16=> ["","BC AU PIED DE L'ÉCHELLE", "BC CPE \"LA DOUCE COUVÉE\"", "BC CPE CADET-ROUSSELLE", "BC CPE FAMILIGARDE", "BC CPE JOIE DE VIVRE", "BC CPE KALÉIDOSCOPE CHILD CARE CENTER", "BC CPE LA GRANDE OURSE", "BC CPE LA MÈRE SCHTROUMPH", "BC CPE LA PETITE MARINE INC.", "BC CPE LA RUCHE MAGIQUE INC.", "BC CPE L'ATTRAIT MIGNON", "BC CPE LE PETIT MONDE DE CALIMÉRO INC.", "BC CPE LES AMIS GATORS", "BC CPE LES COPAINS D'ABORD", "BC CPE LES FRIMOUSSES DE LA VALLÉE", "BC CPE LES JOYEUX CALINOURS", "BC CPE LES PETITS MOUSSES", "BC CPE LES POMMETTES ROUGES", "BC CPE MAFAMIGARDE", "BC CPE MAMIE SOLEIL", "BC CPE MAMIE-POM", "BC CPE MATIN SOLEIL INC.", "BC CPE SOULANGES", "BC CPE VOS TOUT-PETITS", "BC LES JEUNES POUSSES DES JARDINS-DU-QUÉBEC"],
        17=> ["","BC CPE LA GIROUETTE INC.", "BC CPE LA MARELLE DES BOIS FRANCS", "BC CPE LES PETITS LUTINS DE DRUMMONDVILLE INC.", "BC CPE MON AUTRE MAISON", "CPE CHEZ-MOI CHEZ-TOI ET BC GARDE EN MILIEU FAMILIAL"]
    );
     static $regions=array("Bas-Saint-Laurent",
            "Saguenay-Lac-Saint-Jean",
            "Capitale-Nationale",
            "Mauricie",
            "Estrie",
            "Montréal",
            "Outaouais",
            "Abitibi-Témiscamingue",
            "Côte-Nord",
            "Nord-du-Québec",
            "Gaspésie-Îles-de-la-Madeleine",
            "Chaudière-Appalaches",
            "Laval",
            "Lanaudière",
            "Laurentides",
            "Montérégie",
            "Centre-du-Québec");
     
     static function test(){
         global $DB;
        foreach(self::$bc as $key =>$value){
            $region=new stdClass();
            $region->regionid=$key;
            $region->regionname=  self::$regions[$key-1];
            $regionId=$DB->insert_record('rsg_admin_regions',$region);
            foreach($value as $subkey=>$office){
                if ($subkey==0)                    
                    continue;
                echo $key.', '. $subkey.', '.$office.', '.$regionId.PHP_EOL;
                $bureau=new stdClass();
                $bureau->regionid=$regionId;
                $bureau->officeid=$subkey; //TODO refaire pour que ce soit les vrais ID (format 700X-XXXX) - en attendant utiliser le script dans la tâche #3149
                $bureau->officename=$office;
                $DB->insert_record('rsg_coord_office',$bureau);
            }
        }
     }
}

installing::test();
