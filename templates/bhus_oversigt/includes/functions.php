<?php
class bhus_oversigt
{
    public function enrich_with_obib_data($calendar_events)
    {
        /*
         * Get events from odensebib.dk
         */
        $data = file_get_contents("http://dev.obib.dk/events/feeds/json/68");
        $data = json_decode($data);
        if($data != null)
        {
            foreach($data->nodes as $i => $event)
            {
                $obj = new stdClass;
                $obj->Location = "HB" . " skærm";
                $obj->Start = $event->node->field_ding_event_date;
                $obj->End = $event->node->field_ding_event_date;
                $obj->Subject = $event->node->title;
                array_push($calendar_events, $obj);
            }
            usort($calendar_events, array('bhus_oversigt','sort_by_start'));
        }
        return $calendar_events;
    }
    public function enrich_with_område_booking_data($calendar_events)
    {
        try {
            $data = file_get_contents("http://it.odensebib.dk/bhus-events/export");
            $data = json_decode($data);
            if($data != null)
            {
                foreach($data->nodes as $i => $event)
                {
                    $obj = new stdClass;
                    $obj->Location = $event->node->field_event_location . " skærm";
                    $obj->Start =  date("Y-m-d H:i:s",strtotime($event->node->startdato));
                    $obj->End = date("Y-m-d H:i:s",strtotime($event->node->slutdato));
                    $obj->Subject = $event->node->title;
                    array_push($calendar_events, $obj);
                }
                usort($calendar_events, array('bhus_oversigt','sort_by_start'));
                return $calendar_events;
            }
        } catch (Exception $exc) {
            return $calendar_events;
        } 
    }
    function EnrichWithCustomEvents($calendar_events)
    {
        $string = '2018-02-14';//string variable
        $date = date('Y-m-d',time());//date variable
        if(strtotime($string) == strtotime('today'))
        {
            $obj = new stdClass;
            $obj->Location = "børn" . " skærm";
            $obj->Start = "14-02-2018 13:00";
            $obj->End = "14-02-2018 14:00";
            $obj->Subject = "Star Wars på Hovedbiblioteket: Lav dit eget lyssværd";
            array_push($calendar_events,$obj); 
        }
        usort($calendar_events, array('bhus_oversigt','sort_by_start'));
        return $calendar_events;
    }
    function isTodayWeekend() {
        $currentDate = new DateTime("now", new DateTimeZone("Europe/Amsterdam"));
        return $currentDate->format('N') >= 6;
    }
    function sort_by_start($a, $b)
        {
            $a = new DateTime($a->Start);
            $b = new DateTime($b->Start);
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        }
}