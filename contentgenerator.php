<?php
include_once 'includes/bhus_concierge.php';

$_GET['targetmailbox'] = "lok11_borghus@odense.dk,lok12_borghus@odense.dk,lok21_borghus@odense.dk,lok22_borghus@odense.dk,lok31_borghus@odense.dk,lok32_borghus@odense.dk,lok33_borghus@odense.dk,lok34_borghus@odense.dk,lok35_borghus@odense.dk,lok36_borghus@odense.dk";
$_GET['start'] = '00:00:00'; 
$_GET['end'] =  '23:59:59';
$_GET['displaycount'] =  isset($_GET['displaycount']) ? $_GET['displaycount'] : '4';
$template = isset($_GET['skabelon']) ?  $_GET['skabelon']: null;
if($template == null)
{
    return null;
}
/*
 * Build Data
 */
$calendar_events = array();
try {
    $concierge = new bhus_concierge();
    $calendar_events = $concierge->GetByServiceInput();
    //$calendar_events = array();
} catch (Exception $e) {
    
}
/*
 * Render template
 */
include_once("templates/" . $template . "/gui.php");
