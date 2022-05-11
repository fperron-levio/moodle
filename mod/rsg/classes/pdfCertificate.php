<?php
/**
 *
 */

//TODO: refactoriser!!!
//Je ferai une version fonctionnel...mais ce n'est pas beau :)

require_once __DIR__.'/../../../config.php';

global $CFG, $USER;
require_once $CFG->dirroot.'/auth/rsg/classes/RSGUser.php';
require_once $CFG->dirroot.'/mod/rsg/classes/mypdf.php';
$user=new \auth\rsg\RSGUser($USER,false);

$num = optional_param('num', 0, PARAM_INT);
//setup
//TODO: choisir un bon numéro. Tester la longueur des titres.
$per_page=7; //combien de capsules on mettra par page
$data=$user->getCertificateInfoArray();

$data=$data[$num];

$to_cert=array();

global $DB;
foreach($data as $rsginfo){
    $vars=explode('-',$rsginfo);
    $rsg=$DB->get_record('rsg',array('id'=>$vars[0]));
    $val=array($vars[1],$rsg);
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
    if ($i >= $num_page*$per_page){
        $pages[$num_page]=array();
        $num_page++;
    }
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
    setlocale(LC_TIME, "fr_CA");
    $caps_counter=0;
    foreach($page as $val){
        $cont_text=strlen($val[1]->name)<69?$val[1]->name:substr($val[1]->name,0,69).'...';
        $date=new \DateTime();
        $date->setTimestamp($val[0]);
        $date_string=utf8_encode(strftime("%d %B %Y", strtotime($date->format('Y-m-d'))));
        $html_table_cont.='<tr>
                    <td>'.$cont_text.'</td>
                    <td>'.$date_string.'</td>
                    <td style="text-align:center">'.$val[1]->uec.'</td>
                </tr>';
        $caps_counter++;
    }
    if ($caps_counter==0){
        $html_table_cont.='<tr><td>Vous n\'avez pas complété de capsules pour cette période</td><td>-</td><td>-</td></tr>';
        $caps_counter++;
    }
    if ($caps_counter==1)
        $html_table_cont.='<tr><td></td><td></td><td></td></tr>';

    $pdf->AddPage();
    $pdf->generatePage($USER,$uecs_string,$time_string,$html_table_cont);
}

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($user->getFirstName().'_'.$user->getLastName().'_certificat_'.$num.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+