<?php
/**
 * Created by PhpStorm.
 * User: aboris
 * Mamadou Kane 20180225
 * Date: 2017-01-21
 * Time: 14:06
 */

// Include the main TCPDF library (search for installation path).
require_once(__DIR__.'/../../../lib/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class mypdf extends TCPDF {
    const K_PATH_IMAGES= 'assets/';
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = $this::K_PATH_IMAGES.'2017_certificat_sofeduc.jpg'; // #3126
        //$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        $this->Image($img_file, 0, 0, 280, 218, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }

    public function generatePage($USER,$uecs_string,$time_string,$html_table_cont){
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->getAutoPageBreak();
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break);
        // set the starting point for the page content
        //$this->setPageMark();

        $this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'2017_logo_college_rosemont.png';
        $this->Image($img_file, 18, 15, 17, 23, '', '', '', false, 300, '', false, false, 0);
		
		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'logo_certificat_RSG.png';
        $this->Image($img_file, 216, 20, 45, 8, '', '', '', false, 300, '', false, false, 0);
		
		
		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'bordure-certificat-haut.png';
        $this->Image($img_file, 10, 6, 261.5, 1.5, '', '', '', false, 300, '', false, false, 0);
		
		
		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'bordure-certificat-bas.png';
        $this->Image($img_file, 10, 208, 261, 2, '', '', '', false, 300, '', false, false, 0);
		
		
		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'bordure-certificat-gauche.png';
        $this->Image($img_file, 8, 6, 2, 204, '', '', '', false, 300, '', false, false, 0);
		

		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'bordure-certificat-droite.png';
        $this->Image($img_file, 270, 6, 2, 204, '', '', '', false, 300, '', false, false, 0);
		
	
        //$this->setXY(20,170);
        $img_file = MYPDF::K_PATH_IMAGES.'signature.jpg';
        $this->Image($img_file, 30, 139, 60, 22, '', '', '', false, 300, '', false, false, 0);
		
		
		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'souligne-certificat.png';
        $this->Image($img_file, 30, 162, 76, 0.45, '', '', '', false, 300, '', false, false, 0);
		
		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'souligne-certificat.png';
        $this->Image($img_file, 121, 162, 44, 0.45, '', '', '', false, 300, '', false, false, 0);
		
		
       
		$html = '<span style="color:#000000;text-align:center;font-size:44pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">Certificat de perfectionnement</span>';
        $this->setXY(20,31);
		$this->writeHTML($html, true, false, true, false, '');
		
	
		
		
		
        $html = '<span style="color:#000000;text-align:center;font-weight:bold;font-size:30pt;background-color:#ffffff; ">'.ucfirst($USER->firstname).' '.ucfirst($USER->lastname).'</span>';
        $this->setXY(20,59);
        $this->writeHTML($html, true, false, true, false, '');
		
		
		$html = '<span style="color:#000000;text-align:center;font-weight:bold;font-size:20pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">a réussi la capsule de perfectionnement suivante </span>';
        $this->setXY(20,74.5);
        $this->writeHTML($html, true, false, true, false, '');
		

        $table='<table  cellspacing="1.5" class="reference" style="width:95%; font-familly:helvetica, sans-serif;  ">
        <tr    style="background-color:#666262;  font-familly:helvetica, sans-serif; font-size:140%; ">
		 <th height="40";  style="width:30%;color:#ffffff;text-align:center; border: 1px solid #666262;  "> <span style="line-height:2.5px;  ">Sujet</span></th>
		 <th height="40"; style="width:43%;color:#ffffff;text-align:center ;border: 1px solid #666262;"><span style="line-height:2.5px;">Titre de la capsule </span></th>
		 <th height="40"; style="width:27%;color:#ffffff;text-align:center; border: 1px solid #666262;"><span style="line-height:2.5px;">Durée / UEC</span></th>
	    </tr>
		<!-- <tr><td></td><td></td><td></td></tr>  -->
            '.$html_table_cont.'
        </table>
        ';
        $this->setXY(28,92);
        $this->writeHTML($table, true, false, true, false, ''); 
	
		
		
	/*	$html = '<span style="color:#000000;font-weight:bold;font-size:13pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">Représentant de l’établissement</span>';
        $this->setXY(30,164);
        $this->writeHTML($html, true, false, true, false, '');*/
		
		$html = '<span style="color:#000000;font-weight:bold;font-size:13pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">Denis Rousseau</span>';
        $this->setXY(30,164);
        $this->writeHTML($html, true, false, true, false, '');
		
		$html = '<span style="color:#000000;font-weight:bold;font-size:13pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">Directeur général</span>';
        $this->setXY(30,169);
        $this->writeHTML($html, true, false, true, false, '');
		
		$html = '<span style="color:#000000;font-weight:bold;font-size:14pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;"> Date </span>';
        $this->setXY(120,163);
        $this->writeHTML($html, true, false, true, false, '');
		
		
		
		
		$html = '<span style="color:#000000;font-weight:bold;font-size:11pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">Le Collège de Rosemont est membre de la Société de formation et d’éducation continue (SOFEDUC) qui accrédite les organismes et </span>';
        $this->setXY(52,186);
        $this->writeHTML($html, true, false, true, false, '');
		
		$html = '<span style="color:#000000;font-weight:bold;font-size:11pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">les entreprises pour l’émission des unités d’éducation continue (UEC). « Une UEC représente dix heures de participation à une</span>';
        $this->setXY(52,191);
        $this->writeHTML($html, true, false, true, false, '');
		
		$html = '<span style="color:#000000;font-weight:bold;font-size:11pt;background-color:#ffffff; font-familly:goudybeckeroldstyleb;">activité de formation structurée, organisée et dirigée par une organisation accréditée. (...) »</span>';
        $this->setXY(52,196);
        $this->writeHTML($html, true, false, true, false, '');
		
		
		$this->setXY(0,27);
        $img_file = MYPDF::K_PATH_IMAGES.'sofeduc-uec.png';
        $this->Image($img_file, 28, 183, 20, 20, '', '', '', false, 300, '', false, false, 0);

      //  $this->setXY(118,155);      
       // $date=new \DateTime(date('Y-m-j'));
      //  setlocale(LC_TIME, "fr_CA");
      //  $print_date=utf8_encode(strftime("%d %B %Y %H:%M:%S", userdate($capsuleInfo['timeadduec'], '%d %B %Y')));
	   //gmdate("Y-m-d\TH:i:s\Z", $rsg_track->timeadduec)
	   
        // Print a text
     //   $html = '<span style="color:#000000;font-weight:bold;font-size:13pt;background-color:#ffffff;">&nbsp; '.$print_date.' &nbsp;</span>';
    //    $this->writeHTML($html, true, false, true, false, '');

    }

}
