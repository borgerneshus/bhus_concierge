<?php
/*
 * Hent alle booking for et mødelokale og eksponer som json
 * så det kan bearbejdes i feks shiny.
 */
setlocale(LC_ALL, "da_DK.UTF-8");
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include_once 'includes/bhus_concierge.php';

$_GET['targetmailbox'] =  "lok11_borghus@odense.dk,lok12_borghus@odense.dk,lok21_borghus@odense.dk,lok22_borghus@odense.dk,lok31_borghus@odense.dk,lok32_borghus@odense.dk,lok33_borghus@odense.dk,lok34_borghus@odense.dk,lok35_borghus@odense.dk,lok36_borghus@odense.dk";
$_GET['start'] =  isset($_GET['start']) ? $_GET['start'] : 'January 1 00:00:00'; 
$_GET['end'] = isset($_GET['end']) ? $_GET['end'] : 'December 31 23:59:59';
$_GET['filter_screen'] = false;
$concierge = new bhus_concierge();
$result = $concierge->GetByServiceInput(true);
/*
 * Make it serializeable
 */
$encode_test = array();
//$test = json_encode($obj);
/*
 * We do this because php json_encode can not decode the more complex
 * objects from the php-ews library.
 */
foreach($result as $mail => $array)
{
    foreach ($array[0] as $obj)
    {
        $show_screen = 0;
        $php_Funny = str_replace('Æ', 'æ', strtolower($obj->Location));
        if (strpos($php_Funny, 'skærm') !== false) {
            $obj->Location = str_replace('Æ', 'æ',$obj->Location);
            $obj->Location = str_replace("skærm", "", strtolower($obj->Location));
            $obj->Location = str_replace("()", "", strtolower($obj->Location));
            $show_screen = 1;
        }
        $start_date =  new DateTime($obj->Start);
        $start_date->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
        $end_date =  new DateTime($obj->End);
        $end_date->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
        $send_date = new DateTime($obj->DateTimeSent);
        $end_date->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
        
        $days_before_start =  $send_date->diff($start_date)->format('%a')+1;
        
        
        $forfatter_mail = $concierge->ews->lookup_outlook_smtp_email($obj->Organizer->Mailbox->EmailAddress);
       
        $encode_test[$mail][] = array("subject" => $obj->Subject,"start" => $start_date->format('d-m-Y H:i'),"end" => $end_date->format('d-m-Y H:i'),
            "location" => $obj->Location,'show_on_screen' => $show_screen,'forfatter_mail' => $forfatter_mail,'forfatter_navn' => $obj->Organizer->Mailbox->Name,
            'booking_oprettet' => $send_date->format('d-m-Y H:i'),'aflyst' => $obj->IsCancelled ? 1 : 0,'days_before_start' => $days_before_start);
    }
}
header('Content-Type: application/json');
echo  json_encode($encode_test);
 /*switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }*/