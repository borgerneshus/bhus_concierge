<?php
setlocale(LC_ALL, "da_DK.UTF-8");
include_once "includes/functions.php";
$b = new bhus_oversigt();
//$calendar_events = $b->enrich_with_obib_data($calendar_events);
$calendar_events = $b->enrich_with_område_booking_data($calendar_events);
$calenda//r_events = $b->EnrichWithCustomEvents($calendar_events);

?>
<div id="wrapper" class="col-sm-12 col-lg-12 col-md-12 " style="margin:0px;padding:5px;">
    <div id="inner-wrapper" class="col-sm-12 col-lg-12 col-md-12 " style="margin:0px;padding:5px;">
    <div id="top-bar" class="col-sm-12 col-lg-12 col-md-12 removemargin">
        <div class="col-sm-12 col-lg-col-md-12 "><h1 class="" style="" ><?php echo strtoupper("Aktiviteter og mØder i borgernes hus"); ?></h1></div>  
        <div id="logo" class="col-sm-12 col-lg-12 col-md-12 removepadding">
           <!--  <div class="col-sm-2 col-lg-2 col-md-2 removepadding"><img style="float:left;height: 100px;margin-top: 18px;"  src="bhus_booking_events(1).png"></div> -->
            <div class="col-sm-8 col-lg-8 col-md-8 "><h1 class="" style="" ><?php echo ucfirst(strftime("%A")) . strftime(" d. %d %B %Y") ?></h1></div>
            <div class="col-sm-4 col-lg-4 col-md-4 text-right"><h1 class="" style="" ><?php echo !$b->isTodayWeekend() ? "Åbent 8-21" : "Åbent 10-16"; ?></h1></div>
        </div>
    </div>
    <br/>
    <div class="col-md-12" style="margin-top:20px;">
        <?php
        $pagecount = 0;
        $total_show = 0;
        if (sizeof($calendar_events) != 0) {
            $calendar_events = array_chunk($calendar_events, $_GET['displaycount']);
            foreach ($calendar_events as $page => $events) {
                ?>
                <table id="main-table" class="table  table-striped <?php echo "page_" . $page ?>" <?php if ($page != 0) { echo "style='display:none'";} ?> >
                    <thead class="">
                        <tr>
                            <th scope="col">Tidspunkt</th>
                            <th scope="col">Titel</th>
                            <th scope="col">Lokale</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:22px !important;">
                        <?php
                        $show_count = 0;
                        foreach ($events as $event) {
                            $php_Funny = str_replace('Æ', 'æ', strtolower($event->Location));
                            if (strpos($php_Funny, 'skærm') !== false) {
                                $event->Location = str_replace('Æ', 'æ',$event->Location);
                                $event->Location = str_replace("skærm", "", strtolower($event->Location));
                                $event->Location = str_replace("()", "", strtolower($event->Location));
                                
                                date_default_timezone_set('Europe/Copenhagen');
                                $start = new DateTime($event->Start);
                                $start->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                                $end = new DateTime($event->End);
                                $end->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                                $locations = explode(';',$event->Location );
                                ?>
                                <tr>
                                    <td style="white-space: nowrap;border-top-style:none;" class="col-md-2"><?php echo $start->format('H:i') . " - " . $end->format('H:i') ?></td>
                                    <td class="col-md-5" style="border-top-style:none;" ><?php echo $event->Subject; ?></td>
                                    <td style="width:20%;border-top-style:none;" class="col-md-5"><?php foreach($locations as $index => $location){echo $index >= 1 ? "<br/>". $location : $location;} ?></td>
                                </tr>
                                <?php
                                $show_count++;
                            }
                        }
                        
                        ?>
                    </tbody>
                </table>
                <?php
                if($show_count != 0)
                {
                    $total_show += $show_count;
                    $pagecount++;
                }
            }
        }
        if($total_show == 0){
            ?>
         <div id="no-results"><br/><br/>Ingen aktiviter på nuværende tidspunkt.</div>
            <?php
        }
        ?>
        <input id="pagecount" value="<?php echo $pagecount ?>" type="hidden">
    </div>
    </div>
</div>
