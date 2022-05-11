<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-07-08
 * Time: 08:23
 */

/**
 * Je veux générer plusieurs scénarios de dates pour tester mes dernières implémentations.
 * Dates intégrées dans la suite de test contenue dans le dossier sql test_certificats
 */

//Inscription

$date2=strtotime('2012-01-03');

echo $date2.'<br/>';
//Activité premier année
$date2=strtotime('2012-01-04');

echo $date2.'<br/>';

$date2=strtotime('2012-06-22');

echo $date2.'<br/>';
$date2=strtotime('2012-07-10');

echo $date2.'<br/>';

$date2=strtotime('2013-01-02');

echo $date2.'<br/>';

//Activité deuxième année
$date2=strtotime('2013-01-04');

echo $date2.'<br/>';

$date2=strtotime('2013-06-22');

echo $date2.'<br/>';
$date2=strtotime('2013-07-10');

echo $date2.'<br/>';

$date2=strtotime('2014-01-02');

echo $date2.'<br/>';