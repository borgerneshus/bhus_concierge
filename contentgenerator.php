<?php
header("Access-Control-Allow-Origin: *");
setlocale(LC_ALL, "da_DK.UTF-8");
date_default_timezone_set ("Europe/Copenhagen");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
include_once 'includes/bhus_concierge.php';

$_GET['targetmailbox'] = "lok11_borghus@odense.dk,lok21_borghus@odense.dk,lok22_borghus@odense.dk,lok31_borghus@odense.dk,lok32_borghus@odense.dk,lok33_borghus@odense.dk,lok34_borghus@odense.dk,lok35_borghus@odense.dk,lok36_borghus@odense.dk";
//$_GET['targetmailbox'] = "borgerneshusbooking@odense.dk";
//$_GET['targetmailbox'] = "lok31_borghus@odense.dk,lok32_borghus@odense.dk";
$_GET['start'] = isset($_GET['start']) ? $_GET['start'] :'00:00:00'; 
$_GET['end'] =  isset($_GET['end']) ? $_GET['end']:'23:59:59';
$_GET['displaycount'] =  isset($_GET['displaycount']) ? $_GET['displaycount'] : '50';
if(isset($_GET['debug']) && $_GET['debug'] == true)
{
    //mail("tfpet@odense.dk", "Jeg Opdatere", "opdatere indhold");
}
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
