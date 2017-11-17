<?php
include_once "vendor/autoload.php";
use \jamesiarmes\PhpEws\Client;
use \jamesiarmes\PhpEws\Request\FindItemType;

use \jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseFolderIdsType;

use \jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
use \jamesiarmes\PhpEws\Enumeration\DistinguishedFolderIdNameType;
use \jamesiarmes\PhpEws\Enumeration\ResponseClassType;

use \jamesiarmes\PhpEws\Type\CalendarViewType;
use \jamesiarmes\PhpEws\Type\DistinguishedFolderIdType;
use \jamesiarmes\PhpEws\Type\ItemResponseShapeType;
use \jamesiarmes\PhpEws\Type\EmailAddressType;

function getOutlookCalendarEvents($user,$password,$start_data,$end_data)
{
    // Replace with the date range you want to search in. As is, this will find all
    // events within the current calendar year.
    $start_date = new DateTime($start_data);
    $end_date = new DateTime($end_data);
    $timezone = 'Eastern Standard Time';

    // Set connection information.
    $host = 'outlook.office365.com';
    $username = $user;
    $password =  $password;
    $version = "Exchange2016";

    $client = new Client($host, $username, $password, $version);
    $client->setTimezone($timezone);

    $request = new FindItemType();
    $request->ParentFolderIds = new NonEmptyArrayOfBaseFolderIdsType();

    // Return all event properties.
    $request->ItemShape = new ItemResponseShapeType();
    $request->ItemShape->BaseShape = DefaultShapeNamesType::ALL_PROPERTIES;
    
    $mailBox = new EmailAddressType();
    $mailBox->EmailAddress = "dfhh@odense.dk";
    
    $folder_id = new DistinguishedFolderIdType();
    $folder_id->Id = DistinguishedFolderIdNameType::CALENDAR;
    $folder_id->Mailbox = $mailBox;
    
    
    $request->ParentFolderIds->DistinguishedFolderId[] = $folder_id;
 
    $request->CalendarView = new CalendarViewType();
    $request->CalendarView->StartDate = $start_date->format('c');
    $request->CalendarView->EndDate = $end_date->format('c');

    $response = $client->FindItem($request);

    // Iterate over the results, printing any error messages or event ids.
    $response_messages = $response->ResponseMessages->FindItemResponseMessage;
    /*foreach ($response_messages as $response_message) {
        // Make sure the request succeeded.
        if ($response_message->ResponseClass != ResponseClassType::SUCCESS) {
            $code = $response_message->ResponseCode;
            $message = $response_message->MessageText;
            fwrite(
                STDERR,
                "Failed to search for events with \"$code: $message\"\n"
            );
            continue;
        }

        // Iterate over the events that were found, printing some data for each.
        $items = $response_message->RootFolder->Items->CalendarItem;
        foreach ($items as $item) {
            $id = $item->ItemId->Id;
            $start = new DateTime($item->Start);
            $end = new DateTime($item->End);
            $output = 'Found event ' . $item->ItemId->Id . "<br/>"
                . '  Change Key: ' . $item->ItemId->ChangeKey . "<br/>"
                . '  Title: ' . $item->Subject . "<br/>"
                . '  Start: ' . $start->format('l, F jS, Y g:ia') . "<br/>"
                . '  End:   ' . $end->format('l, F jS, Y g:ia') . "<br/><br/>";

            echo($output);
        }
    }*/
    return Calendar_response_to_array($response_messages);
}
function Calendar_response_to_array($calendar_events)
{
    $return = array();
    foreach ($calendar_events as $response_message) {
        // Make sure the request succeeded.
        if ($response_message->ResponseClass != ResponseClassType::SUCCESS) {
            $code = $response_message->ResponseCode;
            $message = $response_message->MessageText;
            fwrite(
                STDERR,
                "Failed to search for events with \"$code: $message\"\n"
            );
            continue;
        }

        // Iterate over the events that were found, printing some data for each.
        $items = $response_message->RootFolder->Items->CalendarItem;
        foreach ($items as $item) {
            array_push($return,$item);
        }
    }
    return $return;
}




