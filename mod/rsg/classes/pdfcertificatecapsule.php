<?php
/**
 * Ce script est une adaptation
 * du script pdfCertificate.php
 * créé par Neelson Müller
 * --
 * Tâche #3345
 * Andrei Boris 20170130
 * Mamadou Kane 20180225
 */

require_once __DIR__.'/../../../config.php';

global $CFG, $USER;
require_once $CFG->dirroot.'/auth/rsg/classes/RSGUser.php';
require_once $CFG->dirroot.'/mod/rsg/classes/rsg_pdf.php';
$user=new \auth\rsg\RSGUser($USER,false);

$num = optional_param('capsule', 0, PARAM_INT);
$current_user = $user->getUser();
$to_cert=array();
global $DB;

if ($rsg_track = $DB->get_record_select('rsg_track', 'userid = :userid AND rsgid = :rsgid AND timeadduec <> 0', array('userid' => $current_user->id, 'rsgid' => $num), $fields='*', $strictness=IGNORE_MISSING)) {

    $rsg = $DB->get_record('rsg', array('id' => $num));
    $cat = $DB->get_field('course_categories', 'name', array('id' => $rsg->category),MUST_EXIST);
    $val = array($rsg_track->timeadduec,$rsg,$cat);
    array_push($to_cert,$val);

}

//calculs des UECs et des heures de formation
$uecs=0;
foreach($to_cert as $val){
    $uecs+=$val[1]->uec;
}

$time=$uecs*10;

$uecs_string=sprintf('%-04.2f',$uecs);
$time_string=sprintf('%-04.1f',$time);

//création du contenu des pages
$pages=array();
$num_page=1;
$pages[0]=array();

for ($i=0;$i<count($to_cert);$i++){
    array_push($pages[$num_page-1],$to_cert[$i]);
}

// create new PDF document
$pdf = new mypdf('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// add pages
foreach($pages as $page){
    $html_table_cont='';
    $html_table_cont2='';

    setlocale(LC_TIME, "fr_CA");
    $caps_counter=0;
    foreach($page as $val){
        $cont_text=strlen($val[1]->name)<69?$val[1]->name:substr($val[1]->name,0,69).'...';
        $cate_text=$val[2];
        $date=new \DateTime();
        $date->setTimestamp($val[0]);
        $date_string=utf8_encode(strftime("%d %B %Y", strtotime($date->format('Y-m-d'))));
        $html_table_cont.='<tr bgcolor="" >
                    <td  style="text-align:center; border:0.25 solid #666262; "><span style=" line-height:40px; ">'.$cate_text.'</span></td>
                    <td  style="text-align:center; border:0.25 solid #666262; "><span style=" "><br style=" line-height:13px; " />'.$cont_text.'<br style=" line-height:1px; " /></span></td>
					<td  style="text-align:center; border:0.25 solid #666262; "><span style=" line-height:40px;">'. 10*$uecs.'  heures / '. $uecs.' UEC</span></td>
              
                </tr>';
        $html_table_cont.='<tr  style="font-size:90%;">&nbsp;Cette formation doit être considérée dans le cadre de l’article 59 du Règlement sur les services de garde éducatif à l’enfance.<td></td><td></td><td></td></tr>';
        $html_table_cont.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont.='<tr ><td></td><td></td><td></td></tr>';







        $html_table_cont.='<tr ><td></td><td></td><td></td></tr>';

        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr ><td></td><td></td><td></td></tr>';
        $html_table_cont2.='<tr>
                    <td></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  '. $date_string. '</td>
					<td style="text-align:center"></td>
              
                </tr>';

        $caps_counter++;
    }
    if ($caps_counter==0){
        $html_table_cont.='<tr>
		<td>Vous n\'avez pas complété de capsules pour cette période</td><td>-</td><td>-</td></tr>';
        $caps_counter++;
    }
    if ($caps_counter==1)
        $html_table_cont.='<tr><td></td><td></td><td></td></tr>';

    $pdf->AddPage();
    $pdf->generatePage($USER,$uecs_string,$time_string,$html_table_cont);

    $pdf->generatePage($USER,$uecs_string,$time_string,$html_table_cont2);
}

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($user->getFirstName().'_'.$user->getLastName().'_certificat_'.$num.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+