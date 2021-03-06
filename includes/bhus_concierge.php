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

use \jamesiarmes\PhpEws\Enumeration\DistinguishedPropertySetType;
use \jamesiarmes\PhpEws\Enumeration\MapiPropertyTypeType;
use \jamesiarmes\PhpEws\Type\PathToExtendedFieldType;
use \jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfPathsToElementType;
use \jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;
use \jamesiarmes\PhpEws\Request\ResolveNamesType;
class bhus_concierge {
    public $ews = null;
    public $last_return = null;
    /*
     * Attempt to extract data from url , and validate we are ready to run.
     */
    private function GetServiceParams()
    {
        $params = array();
        $params['filter_screen'] = isset($_GET['filter_screen']) ? $_GET['filter_screen'] : true;
        if(isset($_GET['targetmailbox']))
        {
            $targets = $_GET['targetmailbox'];
            if(strpos($_GET['targetmailbox'], ',') !== false)
            {
                //multiple mailbox's sendt.
                $mailboxs = explode(",",$targets);
                $params['targets'] = $mailboxs;
            }
            else
            {
                $params['targets'] = array();
                $params['targets'][] = $targets;
            }
        }
        /*
         * Check start Dates.
         */
        if(isset($_GET['start']))
        {
            if(strpos($_GET['start'], ',') !== false)
            {
                //multiple mailbox's sendt.
                $start_dates = explode(",",$_GET['start']);
                $params['start'] = $start_dates;
            }
            else
            {
                $params['start'] = array();
                $params['start'][] = $_GET['start'];
            }
        }
        else
        {
            //defaults to entire year.
        }
        //Check end dates.
        if(isset($_GET['end']))
        {
            if(strpos($_GET['end'], ',') !== false)
            {
                //multiple mailbox's sendt.
                $start_dates = explode(",",$_GET['end']);
                $params['end'] = $start_dates;
            }
            else
            {
                $params['end'] = array();
                $params['end'][] = $_GET['end'];
            }
        }
        else
        {
            //defaults to entire year.
        }
        //Are start and end dates array equally long ?
        if(sizeof($params['end']) != sizeof($params['start']))
        {
            //error ?!
            echo "Start and end date count must be equal.<br/>";
            return null;
        }
        return $params;
    }
    
    /*
     * Constructor
     */
    public function bhus_concierge()
    {
        //Load the settings object
        $master_config = $this->LoadConfig("settings.json");
        $this->ews = new outlook_ews($master_config->master_user,$master_config->master_password);
    }
    public function LoadConfig($path)
    {
        $string = file_get_contents($path);
        return json_decode($string);

    }
    /*
     * Get calendar data based on input via service
     */
    public function GetByServiceInput($combine = false)
    {
        $return = array();
        $params = $this->GetServiceParams();
        if($params == null)
        {
            //abort.
            exit(1);
        }
        if($combine)
        {
            foreach($params['targets'] as $mail)
            {
                foreach($params['start'] as $index => $start_date)
                {
                    $result = $this->ews->GetCalendarForEmail($mail,$start_date,$params['end'][$index],$params['filter_screen']);
                    $obj = new stdClass();
                    $return[$mail][] = $result;
                }
                foreach($return as $arr)
                {
                    usort($arr,array('bhus_concierge','sort_by_start'));
                }
            }
        }
        else
        {
            foreach($params['targets'] as $mail)
            {
                foreach($params['start'] as $index => $start_date)
                {
                    $result = $this->ews->GetCalendarForEmail($mail,$start_date,$params['end'][$index],$params['filter_screen']);
                    $return = array_merge($return,$result);
                    
                }
            }
            usort($return,array('bhus_concierge','sort_by_start'));
        }
        $this->last_return = $return;
        return $return;
    }
    public function GetRefreshTime()
    {
        if($this->last_return != null)
        {
            usort($this->last_return,array('bhus_concierge','sort_by_end'));
            return reset($this->last_return)->End;
        }
    }
    function sort_by_start($a, $b)
    {
        $a = new DateTime($a->Start);
        $a->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
        $b = new DateTime($b->Start);
        $b->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    function sort_by_end($a, $b)
    {
        $a = new DateTime($a->End);
        $a->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
        $b = new DateTime($b->End);
        $b->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}
/*
 * Outlook ews service functions.
 */
class outlook_ews
{
    private $user = null;
    private $password = null;
    private $known_ids = array();
    private $resolved_emails_cache = array();
    public function outlook_ews($User,$Password)
    {
        $this->user = $User;
        $this->password = $Password;
    }
    public function GetCalendarForEmail($Target,$Start,$End,$filter_screen = true)
    {
        // Replace with the date range you want to search in. As is, this will find all
        // events within the current calendar year.
        $start_date = new DateTime($Start);
        $end_date = new DateTime($End);
        $timezone = new DateTimeZone('UTC');
        
        // Set connection information.
        $host = 'outlook.office365.com';
        $username = $this->user;
        $password = $this->password;
        $version = "Exchange2016";

        $client = new Client($host, $username, $password, $version);
        //$client->setTimezone($timezone);

        $request = new FindItemType();
        $request->ParentFolderIds = new NonEmptyArrayOfBaseFolderIdsType();


        
        // Return all event properties.
        $request->ItemShape = new ItemResponseShapeType();
        $request->ItemShape->BaseShape = DefaultShapeNamesType::ALL_PROPERTIES;
        
        // We want to get the online meeting link in the response. Note that if this
        // property is not set on the event, it will not be included in the response.
        $property = new PathToExtendedFieldType();
       // $property->PropertyName = 'Meeting Category';
        $property->PropertyType = MapiPropertyTypeType::STRING;
        $property->PropertyTag = (int)0x6902;
        //$property->PropertySetId = '60FD9366-1E7A-42ea-9F25-1D557F25B85C';
 
        $additional_properties = new NonEmptyArrayOfPathsToElementType();
        $additional_properties->ExtendedFieldURI[] = $property;
        $request->ItemShape->AdditionalProperties = $additional_properties;
        
        $mailBox = new EmailAddressType();
        $mailBox->EmailAddress = $Target;

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
        $return = array();
        foreach ($response_messages as $response_message) {
            // Make sure the request succeeded.
            if ($response_message->ResponseClass != ResponseClassType::SUCCESS) {
                $code = $response_message->ResponseCode;
                $message = $response_message->MessageText;
                echo("Failed to search for events with \"$code: $message\"\n");
                continue;
            }
            
            // Iterate over the events that were found, printing some data for each.
            $items = $response_message->RootFolder->Items->CalendarItem;
            foreach ($items as $item) {
                if($filter_screen)
                {
                    if (strpos(strtolower($item->Location), 'skærm') !== false && !array_key_exists($item->UID,$this->known_ids) ) {
                        array_push($return,$item);
                        $this->known_ids[$item->UID] = true;
                    }
                }
                else
                {
                     if (!array_key_exists($item->UID,$this->known_ids) ) {
                        array_push($return,$item);
                        $this->known_ids[$item->UID] = true;
                    }
                }
                
            }
        }
        return $return;
    }
    public function lookup_outlook_smtp_email($outlook_string)
    {
        if(!isset($this->resolved_emails_cache[$outlook_string]))
        {
            $request = new ResolveNamesType();
            $request->UnresolvedEntry = $outlook_string;
            $request->ReturnFullContactData = true;
            // Set connection information.
            $host = 'outlook.office365.com';
            $username = $this->user;
            $password = $this->password;
            $version = "Exchange2016";

            $client = new Client($host, $username, $password, $version);

            $return = $client->ResolveNames($request);
            $forfatter_mail = null;
            if ($return->ResponseMessages->ResolveNamesResponseMessage[0]->ResponseCode == "NoError") {
                $test = $return->ResponseMessages->ResolveNamesResponseMessage[0]->ResolutionSet->Resolution[0]->Mailbox->EmailAddress;
                $this->resolved_emails_cache[$outlook_string] = $test;
                return  $return->ResponseMessages->ResolveNamesResponseMessage[0]->ResolutionSet->Resolution[0]->Mailbox->EmailAddress;
            }
            else
            {
                return null;
            }
        }
        else
        {
            return $this->resolved_emails_cache[$outlook_string];
        }
        
    }
    
}