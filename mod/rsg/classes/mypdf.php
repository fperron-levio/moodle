<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-07-04
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
        $img_file = $this::K_PATH_IMAGES.'certificat-sofeduc_2.jpg';
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
        $img_file = MYPDF::K_PATH_IMAGES.'logoCAD.jpg';
        $this->Image($img_file, 26, 20, 67, 25, '', '', '', false, 300, '', false, false, 0);

        //$this->setXY(20,170);
        $img_file = MYPDF::K_PATH_IMAGES.'signature.jpg';
        $this->Image($img_file, 40, 144, 60, 22, '', '', '', false, 300, '', false, false, 0);

        $this->setXY(20,70);
        // Print a text

        $html = '<span style="color:#000000;text-align:center;font-weight:bold;font-size:20pt;background-color:#ffffff;">'.ucfirst($USER->firstname).' '.ucfirst($USER->lastname).'</span>';
        $this->writeHTML($html, true, false, true, false, '');

        $this->setXY(17,82);
        // Print a text
        $html = '<span style="color:#000000;text-align:center;font-weight:bold;font-size:13pt;background-color:#ffffff;">&nbsp; a complété les capsules de perfectionnement suivantes :&nbsp;</span>';
        $this->writeHTML($html, true, false, true, false, '');

        //Efface le texte indésirable

        $this->setXY(10,110);
        // Print a text
        $html = '<span style="color:#000000;text-align:center;font-weight:bold;font-size:40pt;background-color:#ffffff;">&nbsp; &nbsp; &nbsp; &nbsp;
        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>';
        $this->writeHTML($html, true, false, true, false, '');

        $this->setXY(142,187);
        // Print a text
        $html = '<span style="color:#000000;font-size:11pt;background-color:#ffffff;">(...)&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
        &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</span>';
        $this->writeHTML($html, true, false, true, false, '');

        $this->setXY(20,193);
        // Print a text
        $html = '<span style="color:#000000;font-weight:bold;font-size:16pt;background-color:#ffffff;">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
        &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
        &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;
        &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</span>';
        $this->writeHTML($html, true, false, true, false, '');
        ///fin du cachage



        $this->setXY(220,136.5);
        // Print a text
        $html = '<span style="color:red;text-align:center;font-weight:bold;font-size:10pt;">'.$uecs_string.'</span>';
        $this->writeHTML($html, true, false, true, false, '');

        $this->setXY(222,142.5);
        // Print a text
        $html = '<span style="color:red;text-align:center;font-weight:bold;font-size:10pt;">'.$time_string.'</span>';
        $this->writeHTML($html, true, false, true, false, '');

        $table='<table class="reference" style="width:90%;background-color:#ffffff;" >
	    <tr>
		<th style="width:60%;color:#ffffff;background-color:#555555;border:1px solid #555555;padding:3px;vertical-align:top;text-align:center">Nom de la capsule</th>
		<th style="width:20%;color:#ffffff;background-color:#555555;border:1px solid #555555;padding:3px;vertical-align:top;text-align:left">Date de finalisation</th>
		<th style="width:20%;color:#ffffff;background-color:#555555;border:1px solid #555555;padding:3px;vertical-align:top;text-align:center">UEC</th>
	    </tr>
            '.$html_table_cont.'
        </table>
        ';

        $this->setXY(35,91);
        $this->writeHTML($table, true, false, true, false, '');

        $this->setXY(165,160);
        $date=new \DateTime(date('Y-m-j'));
        setlocale(LC_TIME, "fr_CA");
        $print_date=utf8_encode(strftime("%d %B %Y", strtotime($date->format('Y-m-d'))));
        // Print a text
        $html = '<span style="color:#000000;font-weight:bold;font-size:13pt;background-color:#ffffff;">&nbsp; '.$print_date.' &nbsp;</span>';
        $this->writeHTML($html, true, false, true, false, '');

    }

}